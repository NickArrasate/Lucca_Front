<?php
require_once('phing/Task.php');

class DbAutoUpdateTask extends Task
{
  const MODE = 'debug'; // all messages are viewed
  //const MODE = 'production'; // only info messages are viewed

  private $db_types = array();
  private $admin_dsn = array();

  //!! external properties
  private $method;
  private $path_to_deltas;

  //!! internal properties 
  private $db_to_update = array();
  private $properties = array();
  private $dsn_elements = array('db_driver', 'host', 'port', 'username', 'password', 'db_name');

  //!! methods params
  private $apply_first = null;
  private $apply_last = null;
  private $apply_single = null;
  private $apply_dsn = array();
  private $apply_db_types = array();
  private $undo = false;

  private $check_dbs_dsn = array();
  private $check_dbs_compare_mode = array();

  public function setMethod($method) 
  {
    $this->method = $method;
  }

  /**
   * The init method: Do init steps.
   */
  public function init()
  {
  }


  private function init_params()
  {
    $this->properties = $this->project->getProperties();
    // Initialize PDO for admin DB
    foreach ($this->dsn_elements as $dsn_element) 
    {
      if (isset($this->properties['dbautoupdate.dsn.'.$dsn_element.'.value'])) 
      {
        $this->admin_dsn[$dsn_element] = $this->properties['dbautoupdate.dsn.'.$dsn_element.'.value'];
      }
      else
      {
        throw new Exception('Property dbautoupdate.dsn.'.$dsn_element.'.value must be set to non-empty string'."\n");
      }
    }
    
    //!! init environment fields
    if ($this->project->getProperty('dbautoupdate.dir'))
    {
      $this->path_to_deltas = $this->project->getProperty('dbautoupdate.dir');
    }
    else
    {
      throw new Exception("Property 'dbautoupdate.dir' should be defined");
    }

    //!! init methods parameters
    $this->apply_first   = $this->project->getProperty('dbautoupdate.apply.first');
    $this->apply_last    = $this->project->getProperty('dbautoupdate.apply.last');
    if (!is_null($this->apply_first) &&  !is_null($this->apply_last) && $this->apply_first > $this->apply_last)
    {
      throw new Exception("Property 'apply.first' shoulde be less than 'apply.last'"."\n");
    }
    $this->apply_single  = $this->project->getProperty('dbautoupdate.apply.single');

    //!! init db_to_update field
    if ($this->__check_connection($this->admin_dsn))
    {
      $this->init_db_to_update();
    }
    else
    {
      $this->__echo("Can't connect to ".$this->admin_dsn['db_name']." database!", "error");
    }

    if ($this->project->getProperty('dbautoupdate.apply.dsn'))
    {
      throw new Exception("Property 'dbautoupdate.apply.dsn' not yet implemented");
      //!! here we should rewrite db_to_update propery
      $this->apply_dsn = array();
      $this->db_to_update = array();
    }

    if($this->project->getProperty('dbautoupdate.apply.db_types'))
    {
      $this->apply_db_types = explode(",", $this->project->getProperty('dbautoupdate.apply.db_types'));
    }

    //seems this not uses now cause use type parameter
    //$this->apply_db_type = $this->project->getProperty('dbautoupdate.apply.db_type');

    if ($this->project->getProperty('dbautoupdate.undo'))
    {
      $this->undo = true;
    }

    // get canonical DBs
    foreach ($this->db_types as $db_type) 
    {
      foreach ($this->dsn_elements as $dsn_element)
      {
        if ($dsn_value = $this->project->getProperty('dbautoupdate.check_dbs.dsn.'.$db_type.'.'.$dsn_element.'.value'))
        {
          $this->check_dbs_dsn[$db_type][$dsn_element] = $dsn_value;
        }
      }
    }

    // get compare mode for check DBs target
    if ($this->project->getProperty("dbautoupdate.check_dbs.compare_mode"))
    {
      $this->check_dbs_compare_mode = explode(",", $this->project->getProperty("dbautoupdate.check_dbs.compare_mode"));
    }
  }

  /**
   * The main entry point method.
   */
  public function main() 
  {
    $this->init_params();
    switch($this->method)
    {
      case 'create':
        $this->method_create();
      break;
      case 'init':
        $this->method_init();
      break;
      case 'apply':
        $this->method_apply();
      break;
      case 'print':
        $this->method_print();
      break;
      case 'mark':
        $this->method_mark();
      break;
      case 'check_dbs':
        $this->method_check_dbs();
      break;
    }
  }
    

  
  /*
   * Create all configured DBs
   */
  public function method_create()
  {
    $arr_types = $this->filter_by_apply_db_types($this->db_types);
    foreach($arr_types as $db_type)
    {
      foreach ($this->db_to_update[$db_type] as $ii => $dsn)
      {
        $dbh  = new PDO($this->admin_dsn['dsn'], $this->admin_dsn['username'], $this->admin_dsn['password']);
        try
        {
          $dbh->query('create database '.$dsn['db_name']);
        } 
        catch (PDOException $e)
        {
          print "Error: " . $e->getMessage() . "\n";
        }
      }
    }
    // We should also add 'grant' SQL here to initialize access to the freshly created DBs according to the rules
  }

  /*
   * Create empty changelog tables for all configured DBs
   */
  public function method_init()
  {
    $arr_types = $this->filter_by_apply_db_types($this->db_types);
    foreach($arr_types as $db_type)
    {
      foreach ($this->db_to_update[$db_type] as $ii => $dsn)
      {
        $this->__echo('Attempt to create changelog table in '.$dsn['db_name']);
        if ($dsn['db_driver'] != 'mysql')
        {
          throw new Exception("At the moment, only MySQL is supported\n");
        }
        if ($this->__check_connection($dsn))
        {
          $ret = $this->apply_sql($this->properties['dbautoupdate.dir.init'] . "/changelog." . $dsn['db_driver'] . ".sql", $dsn);
          if ($ret)
          {
            throw new Exception("The command failed with exit code $ret\nThe output was:\n".implode("\n",$out));
          }
        }
        else
        {
          $this->__echo("Skipped ".$dsn["db_name"], 'warning');
        }
      }
    }
  }

  /**
   * Method to apply delta scripts to database
   **/
  public function method_apply()
  {
    $deltas_list = $this->get_deltas($this->path_to_deltas, $this->undo);
    foreach($deltas_list as $delta_name)
    {
      $delta_info = $this->parse_delta_name($delta_name);
      $delta_info['db_type'] = $this->filter_by_apply_db_types($delta_info['db_type']);
      if (isset($delta_info['db_type']) && is_array($delta_info['db_type']))
      {
        foreach ($delta_info['db_type'] as $db_type)
        {
          if (isset($this->db_to_update[$db_type]))
          {
            foreach($this->db_to_update[$db_type] as $db_to_update)
            {
              $this->__echo('Attempt to apply '.$delta_name.' to '.$db_to_update['db_name']);
              if ($this->__check_connection($db_to_update) && $this->__check_changelog($db_to_update))
              {
                if (!$this->is_delta_applied($delta_name, $db_to_update, $this->undo))
                {
                  $result = $this->apply_delta_to_db($delta_name, $db_to_update);
                  $this->mark_delta($delta_name, $db_to_update, $this->undo);
                }
                else
                {
                  $this->__echo("Delta ".$delta_name." was already appplied", "warning");
                }
              }
              else
              {
                $this->__echo("Skipped ".$db_to_update["db_name"], 'warning');
              }
            }     
          }
        }
      }
    }
  }

  /**
   * Method which just print applicable changes
   **/
  public function method_print()
  {
    $deltas_list = $this->get_deltas($this->path_to_deltas, $this->undo);
    foreach($deltas_list as $delta_name)
    {
      $delta_info = $this->parse_delta_name($delta_name);
      if (isset($delta_info['db_type']) && is_array($delta_info['db_type']))
      {
        $delta_info['db_type'] = $this->filter_by_apply_db_types($delta_info['db_type']);
        foreach ($delta_info['db_type'] as $db_type)
        {
          if (isset($this->db_to_update[$db_type]))
          {
            foreach($this->db_to_update[$db_type] as $db_to_update)
            {
              $this->__echo('Attempt to apply '.$delta_name.' to '.$db_to_update['db_name']);
              if ($this->__check_connection($db_to_update) && $this->__check_changelog($db_to_update))
              {
                if (!$this->is_delta_applied($delta_name, $db_to_update, $this->undo))
                {
                  $this->print_delta($delta_name, $db_to_update);
                }
                else
                {
                  $this->__echo("Delta ".$delta_name." was already appplied", "warning");
                }
              }
              else
              {
                $this->__echo("Skipped ".$db_to_update["db_name"], 'warning');
              }
            }     
          }
        }
      }
    }
  }

  // filter array of db types according to dbautoupdate.apply.db_types property
  private function filter_by_apply_db_types($arr_db_types)
  {
    $types = $arr_db_types;
    if(is_array($arr_db_types) && $arr_db_types && $this->apply_db_types)
    {
      $types = array();
      foreach($arr_db_types as $k => $db_type)
      {
        if(in_array($db_type, $this->apply_db_types))
        {
          $types[] = $db_type;
        }
      }
    }
    return $types;
  }

  public function method_mark()
  {
    $deltas_list = $this->get_deltas($this->path_to_deltas, $this->undo);
    foreach($deltas_list as $delta_name)
    {
      $delta_info = $this->parse_delta_name($delta_name);
      if (isset($delta_info['db_type']) && is_array($delta_info['db_type']))
      {
        $delta_info['db_type'] = $this->filter_by_apply_db_types($delta_info['db_type']);
        foreach ($delta_info['db_type'] as $db_type)
        {
          if (isset($this->db_to_update[$db_type]))
          {
            foreach($this->db_to_update[$db_type] as $db_to_update)
            {
              if (!$this->is_delta_applied($delta_name, $db_to_update, $this->undo))
              {
                $this->mark_delta($delta_name, $db_to_update, $this->undo);
              }
              else
              {
                $this->__echo("Delta ".$delta_name." was already appplied", "warning");
              }
            }     
          }
        }
      }
    }
  }

  public function method_check_dbs()
  {
    if (!empty($this->check_dbs_dsn))
    {
      foreach ($this->check_dbs_dsn as $db_type => $dsn_values)
      {
        if (isset($this->db_to_update[$db_type]) && !empty($this->db_to_update[$db_type]))
        {
          $this->__echo("Compare ".$db_type." databases with ".$dsn_values['db_name']." schemas");
          $db_check_task = $this->project->createTask("dbcheck");
          $db_check_task->setCompareMode($this->check_dbs_compare_mode);
          $db_check_task->setDsnStandard($dsn_values);
          foreach ($this->db_to_update[$db_type] as $db_to_compare)
          {
            $this->__echo("Compare schemas of ".$db_to_compare['db_name']." with ".$dsn_values['db_name']. " database");
            $db_check_task->setDsnCurrent($db_to_compare);
            $db_check_task->method_check_dbs();
          }
        }
      }
    }
  }

  /**
   * apply delta to database
   *
   * @param string $delta_name name of delta
   * @param array $db_to_update database configuration
   * @return boolean
   **/
  public function apply_delta_to_db($delta_name, $db_to_update)
  {
    $delta_name_with_path = $this->path_to_deltas.DIRECTORY_SEPARATOR.$delta_name;
    $has_successful = ($this->apply_sql($delta_name_with_path, $db_to_update)) ? false : true;
    return $has_successful;
  }

  /**
   * check applying delta to database
   *
   * @param string $delta_name name of delta
   * @param array $db_to_update database configuration
   * @param booleand $is_undo true if checked in changelog delta is undo
   * @return boolean
   **/
  private function is_delta_applied($delta_name, $db_to_update, $is_undo = false)
  {
    $delta_info = $this->parse_delta_name($delta_name);
    if (!$is_undo)
    {
      $sql = "
        SELECT
          COUNT(*) AS `is_applied`
        FROM
          `changelog`
        WHERE
          `update_number` = '".$delta_info['number']."' AND
          `dt_undo` IS NULL
      ";
    }
    else
    {
      $sql = "
        SELECT
          COUNT(*) AS `is_applied`
        FROM
          `changelog`
        WHERE
          `update_number` = '".$delta_info['number']."' AND
          `dt_undo` IS NOT NULL 
      ";
    }
    $obj_PDOStatement = $this->PDO_execute($db_to_update, $sql);
    if ($obj_PDOStatement)
    {
      $result = $obj_PDOStatement->fetch();
    }
    else
    {
      $this->__echo("Can't access to changelog table");  
    }

    return ($result['is_applied'] == 0) ? false : true;
  }

  public function print_delta($delta_name, $db_to_update)
  {
    $message = $db_to_update['db_name']." <- ".$delta_name;
    $this->__echo($message);
  }

  private function __echo($message, $level = "info")
  {
    $echo_task = $this->project->createTask("echo");
    $echo_task->setLevel($level);
    $echo_task->setMessage($message);
    $echo_task->main();
  }

    /**
     * mark delta has applied in changelog
     *
     * @param string $delta_name name of delta
     * @param array $db_to_update database configuration
     * @param boolean $is_undo true if marked delta is undo
     * @return void
     **/
    public function mark_delta($delta_name, $db_to_update, $is_undo = false)
    {
      $delta_info = $this->parse_delta_name($delta_name);
      $sql = "
        SELECT
          `update_number`
        FROM
          `changelog`
        WHERE
          `update_number` = '".$delta_info['number']."'
      ";
      $obj_PDOStatement = $this->PDO_execute($db_to_update, $sql);
      if ($obj_PDOStatement)
      {
        $result = $obj_PDOStatement->fetch();
      }
      else
      {
        $this->__echo("Can't access to changelog table");  
      }

      $dt = "NOW()";
      $dt_undo = "NULL";
      if ($is_undo)
      {
        $dt = "`dt`";
        $dt_undo = "NOW()";
      }

      if (isset($result['update_number']) && intval($result['update_number']) > 0)
      {
        $sql = "
          UPDATE 
            `changelog`
          SET
            `dt` = ".$dt.",
            `dt_undo` = ".$dt_undo."
          WHERE
            `update_number` = '".$result['update_number']."'
        ";
      }
      else
      {
        $sql = "
          INSERT INTO
            `changelog` (`update_number`, `db_type`, `skipped`, `dt`, `dt_undo`, `description`)
          VALUES 
            ('".$delta_info['number']."', '".implode("-", $delta_info['db_type'])."', 0, ".$dt.", ".$dt_undo.",'".$delta_info['name']."')
        ";
      }
      $this->PDO_execute($db_to_update, $sql);
    }


    /**
     * generate deltas list by path and return sorted by alphabetical
     * by default return common deltas (not undo)
     *
     * @param string $path_to_deltas full path to deltas
     * @param boolean $get_undo if need return undo deltas
     * @return array deltas list
     **/

    public function get_deltas($path_to_deltas = "", $get_undo = false)
    {
      $deltas_list = array();
      if (file_exists($path_to_deltas)) 
      {
        $deltas_files_list = scandir($path_to_deltas, $get_undo);
        if (!empty($deltas_files_list))
        {
          foreach ($deltas_files_list as &$delta_name)
          {
            $delta_to_install = true;
            if ($delta_name_pieces = $this->parse_delta_name($delta_name))
            {
              if (!is_null($this->apply_single) && $delta_name_pieces['number'] != $this->apply_single)
              {
                $delta_to_install = false;
              } 
              else
              {
                if (!is_null($this->apply_first) && $delta_name_pieces['number'] < $this->apply_first)
                {
                  $delta_to_install = false;
                }
                if (!is_null($this->apply_last) && $delta_name_pieces['number'] > $this->apply_last)
                {
                  $delta_to_install = false;
                }
              }

              if (($get_undo && !$delta_name_pieces['is_undo']) || (!$get_undo && $delta_name_pieces['is_undo']))
              {
                $delta_to_install = false;
              }
            }

            if ($delta_to_install)
            {
              array_push($deltas_list, $delta_name);
            }
          }
        }
      }

      return $deltas_list;
    }

    /**
     * validate delta filename and parse to pieces 
     * return array with keys:
     *  is_undo - true if delta is undo
     *  number - number of delta
     *  db_type - database type of delta
     *  name - delta name
     *
     * @param string delta filename
     * @return array if delta name is valid or 
     *         false if delta name is invalid
     **/
    public function parse_delta_name($delta_name)
    {
      $result_delta_name_pieces = false;

      if (preg_match('/([0-9]{3})\.([a-zA-Z\-0-9_]+)\.([\w]+)\.((undo)(\.))?sql/i', $delta_name, $delta_name_pieces))
      {
        $result_delta_name_pieces['is_undo'] = (isset($delta_name_pieces[5]) && $delta_name_pieces[5] == "undo") ?
          true :
          false;
        $result_delta_name_pieces['number'] = intval($delta_name_pieces[1]);
        $result_delta_name_pieces['db_type'] = explode("-", $delta_name_pieces[2]);
        $result_delta_name_pieces['name'] = $delta_name_pieces[3];
      }
      
      return $result_delta_name_pieces;
    }

    private function PDO_execute($database_configure, $sql_query)
    {
      $result = false;

      try
      {
        $connection_string = $database_configure['db_driver'].":".
          "dbname=".$database_configure['db_name'].";".
          "host=".$database_configure['host'].";".
          "port=".$database_configure['port'];
        $obj_PDO = new PDO($connection_string, $database_configure["username"], $database_configure["password"]);
        $result = $obj_PDO->query($sql_query);
      }
      catch (PDOException $e)
      {
        print "Error: " . $e->getMessage() . "\n";
        $result = false;
      }

      return $result;
    }

    /**
     * Apply sql via mysql command. Implementation use phing own task ExecTask
     **/
    public function apply_sql($path_to_sql_file, $database_configure)
    {
      $command_stack = array();
      array_push($command_stack, 'mysql');
      array_push($command_stack, '--host='.$database_configure['host']);
      array_push($command_stack, '--port='.$database_configure['port']);
      array_push($command_stack, '--user='.$database_configure['username']);
      array_push($command_stack, '--password='.$database_configure['password']);
      array_push($command_stack, '--database='.$database_configure['db_name']);
      array_push($command_stack, '--default-character-set=utf8');
      array_push($command_stack, '<');
      array_push($command_stack, $path_to_sql_file);

      $command = implode(" ", $command_stack);
      $exec_task = $this->project->createTask('myexec');
      $exec_task->setMode(self::MODE);
      $exec_task->setCommand($command);
      $exec_task->setOutputProperty('dbautoupdate.command_output');
      $exec_task->setReturnProperty('dbautoupdate.command_return');
      $exec_task->execute();
      return $this->project->getProperty('dbautoupdate.command_return'); 
    }

  /**
   * init db_to_update property
   *
   **/
  private function init_db_to_update()
  {

    // List of all properties defined in Phing
    $this->properties = $this->project->getProperties();
    if (!isset($this->properties['dbautoupdate.db_types']) || $this->properties['dbautoupdate.db_types'] == '')
    {
      throw new Exception('Property dbautoupdate.db_types must be set to non-empty string: list of DB types separtated by comma'."\n");
    }
    $this->db_types = explode (',', $this->properties['dbautoupdate.db_types']);

    $this->admin_dsn['dsn'] = $this->admin_dsn['db_driver'].":dbname=".$this->admin_dsn['db_name'].";host=".$this->admin_dsn['host'].";port=".$this->admin_dsn['port'];
    $dbh = new PDO($this->admin_dsn['dsn'], $this->admin_dsn['username'], $this->admin_dsn['password']);

    // Initialize DNS elements for all DB types with those defined in properties
    foreach ($this->db_types as $db_type) 
    {
      foreach ($this->dsn_elements as $dsn_element) 
      {
        $this->db_to_update[$db_type][0][$dsn_element] = $this->properties['dbautoupdate.dsn.'.$dsn_element.'.value'];
      }
    }
    // Extract customized parameters from the admin DBs, multiply DSNs for supported DB typos and set them in $this->db_to_update
    $multiple_dbs = array();
    foreach ($this->db_types as $db_type) 
    {
      if (isset($this->properties['dbautoupdate.dsn.'.$db_type.'.query'])) 
      {
        $rslt = $dbh->query($this->properties['dbautoupdate.dsn.'.$db_type.'.query']);
        foreach ($rslt as $num => $row) 
        {
          foreach ($row as $key => $value) 
          {
            if (in_array(strval($key), $this->dsn_elements))
            {
               $multiple_dbs[$db_type][$num][$key] = $value;
            }
          }
        }
        if (isset($multiple_dbs[$db_type]))
        {
          # validate them
          $valid = true;
          foreach ($multiple_dbs[$db_type] as $db_instance) 
          {
            if (count($db_instance) != count($multiple_dbs[$db_type][0]))
            {
              $valid = false;
              break;
            }
            foreach ($multiple_dbs[$db_type][0] as $key => $value1)
            {
              if (!isset($db_instance[$key]) || $db_instance[$key] == '')
              {
                $valid = false;
                break;
              }
            }
          }
          if (!$valid)
          {
            throw new Exception('For DB type "'.$db_type.'", the select you configured in dbautoupdate.dsn.'.$db_type.'.query returns inconsistent results. All values should be non-empty, the same values must be specified for every DB instance'."\n");
          }
          # populate db_instance
          $cnt = 1;
          foreach ($multiple_dbs[$db_type] as $db_instance) 
          {
            $this->db_to_update[$db_type][$cnt] = $this->db_to_update[$db_type][0];
            foreach ($db_instance as $key => $value)
            {
              $this->db_to_update[$db_type][$cnt][$key] = $value;
            }
            $cnt++;
          }
          if ($cnt > 1) 
          {
            unset($this->db_to_update[$db_type][0]);
          }
        }
      }
    }
  }


  /**
   * Check connection and changelog table to db from db_to_update 
   **/
  function __check_connection($database_configure)
  {
    $result = true;
    try
    {
      $connection_string = $database_configure['db_driver'].":".
        "dbname=".$database_configure['db_name'].";".
        "host=".$database_configure['host'].";".
        "port=".$database_configure['port'];
      $obj_PDO = new PDO($connection_string, $database_configure["username"], $database_configure["password"]);
    }
    catch (PDOException $e)
    {
      $message = "Can't connect to ".$database_configure['db_name']." database: ".$connection_string;
      $this->__echo($message, 'error');
      $result = false;
    }
    return $result;
  }


  /**
   * Check connection and changelog table to db from db_to_update 
   **/
  function __check_changelog($database_configure)
  {
    $result = true;
    try
    {
      $connection_string = $database_configure['db_driver'].":".
        "dbname=".$database_configure['db_name'].";".
        "host=".$database_configure['host'].";".
        "port=".$database_configure['port'];
      $obj_PDO = new PDO($connection_string, $database_configure["username"], $database_configure["password"]);
      $obj_PDOStatement = $obj_PDO->query("SHOW TABLES LIKE 'changelog'");
      if (!$obj_PDOStatement->fetch())
      {
        $message = "Table changelog do not exists in database ".$database_configure['db_name'];
        $this->__echo($message, 'warning');
        $result = false;
      }
    }
    catch (PDOException $e)
    {
      $message = "Can't connect to ".$database_configure['db_name']." database: ".$connection_string;
      $this->__echo($message, 'error');
      $result = false;
    }
    return $result;
  }





}
