<?php
require_once('phing/Task.php');

class DbCheckTask extends Task
{
  private $method;
  private $properties = array();
  private $dsn_elements = array('db_driver', 'host', 'port', 'username', 'password', 'db_name');
  private $dsn_standard = array();
  private $dsn_current = array();
  private $compare_mode = array();
  
  public function setMethod($method)
  {
    $this->method = $method;
  }

  public function setDsnStandard($dsn_standard)
  {
    $this->dsn_standard = $dsn_standard;
  }

  public function setDsncurrent($dsn_current)
  {
    $this->dsn_current = $dsn_current;
  }

  public function setCompareMode($compare_mode)
  {
    $this->compare_mode = $compare_mode;
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
    $this->db_driver = $this->project->getProperty('dbcheck.db_driver');

    // Initialize dsn for standart db
    foreach ($this->dsn_elements as $dsn_element)
    {
      if (isset($this->properties['dbcheck.dsn_standard.'.$dsn_element.'.value']))
      {
        $this->dsn_standard[$dsn_element] = $this->properties['dbcheck.dsn_standard.'.$dsn_element.'.value'];
      }
      else
      {
        throw new Exception('Property dbcheck.dsn_standard.'.$dsn_element.'.value must be set to non-empty string'."\n");
      }
    }
    
    // Initialize dsn for current db
    foreach ($this->dsn_elements as $dsn_element)
    {
      if (isset($this->properties['dbcheck.dsn_current.'.$dsn_element.'.value']))
      {
        $this->dsn_current[$dsn_element] = $this->properties['dbcheck.dsn_current.'.$dsn_element.'.value'];
      }
      else
      {
        throw new Exception('Property dbcheck.dsn_current.'.$dsn_element.'.value must be set to non-empty string'."\n");
      }
    }

    //test connection
    if (!$this->__check_connection($this->dsn_standard))
    {
      $this->__echo("Can't connect to ".$this->dsn_standard['db_name']." database!", "error");
    }
    if (!$this->__check_connection($this->dsn_current))
    {
      $this->__echo("Can't connect to ".$this->dsn_current['db_name']." database!", "error");
    }

  }

   /**
   * The main entry point method.
   */
  public function main()
  {
    $this->init_params();
    $this->method_check_dbs();
  }

  public function method_check_dbs()
  {
		if (($obj_PDO_schema_standard = $this->__check_connection($this->dsn_standard)) && 
			($obj_PDO_schema_current = $this->__check_connection($this->dsn_current)))
    {
      $db_schema_standard = $this->__create_schema($obj_PDO_schema_standard, $this->dsn_standard['db_name']);
      $db_schema_current = $this->__create_schema($obj_PDO_schema_current, $this->dsn_current['db_name']);

      if (is_array($this->compare_mode) && in_array("data", $this->compare_mode))
      {
        $db_schema_standard['obj_PDO'] = $obj_PDO_schema_standard;
        $db_schema_current['obj_PDO'] = $obj_PDO_schema_current;
      }

      $result = $this->__compare($db_schema_standard, $db_schema_current);
      if ($result)
      {
        $this->__echo("Databases ".$this->dsn_standard['db_name']." and ".$this->dsn_current['db_name']." are equal");
      }
    }
  }

  private function __create_schema($obj_PDO, $name)
  {
    $result = array();

    if (is_object($obj_PDO))
    {
      $obj_PDOStatement = $obj_PDO->query("SHOW TABLES");
      $obj_PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
      $tables_list = $obj_PDOStatement->fetchAll();
      foreach ($tables_list as $tables_list_row)
      {
        $table_name = reset($tables_list_row);
        $result[$table_name] = array();

        $obj_PDOStatement = $obj_PDO->query("SHOW COLUMNS FROM `".$table_name."`");
        $obj_PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
        $fields_list = $obj_PDOStatement->fetchAll();
        foreach ($fields_list as $fields_list_row)
        {
          $result[$table_name][$fields_list_row['Field']] = $fields_list_row;
        }
      }
    }

    $data = array(
      'db_name' => $name,
      'tables' => $result
    );

    return $data;
  }

  private function __compare($schema_standard, $schema_current)
  {
    $compare_result = true;

    $tables1 = array_keys($schema_standard['tables']);
    $tables2 = array_keys($schema_current['tables']);
    
    $tables = array_unique(array_merge($tables1, $tables2));
    
    $results = array();

    foreach ($tables as $table_name)
    {
      $compare_table_result = true;
      // Check tables exist in both databases
      
      if (!isset($schema_standard['tables'][$table_name])) 
      {
        if ($compare_table_result)
        {
          $this->__echo("Table ".$table_name.":", "error");
          $compare_table_result = false;
        }
        $this->__echo(" - ".$schema_current['db_name'].': unknown table '.$table_name, "warning");
        $compare_result = false;
        continue;
      }
      
      if (!isset($schema_current['tables'][$table_name])) 
      {
        if ($compare_table_result)
        {
          $this->__echo("Table ".$table_name.":", "error");
          $compare_table_result = false;
        }
        $this->__echo(" - ".$schema_current['db_name'].': missing table '.$table_name, "warning");
        $compare_result = false;
        continue;
      }
      
      // Check fields exist in both tables
      
      $fields = array_merge($schema_standard['tables'][$table_name],
        $schema_current['tables'][$table_name]);

      $has_equal_fields_list = true;  
      foreach ($fields as $field_name => $field) 
      {
        
        if (!isset($schema_standard['tables'][$table_name][$field_name])) 
        {
          if ($compare_table_result)
          {
            $this->__echo("Table ".$table_name.":", "error");
            $compare_table_result = false;
          }
          $this->__echo(" - ".$schema_current['db_name'].': unknown field '.$field_name, "warning");
          $has_equal_fields_list = false;            
          $compare_result = false;
          continue;
        }
        
        if (!isset($schema_current['tables'][$table_name][$field_name])) 
        {
          if ($compare_table_result)
          {
            $this->__echo("Table ".$table_name.":", "error");
            $compare_table_result = false;
          }
          $this->__echo( " - ".$schema_current['db_name'].': missing field '.$field_name, "warning");
          $has_equal_fields_list = false;
          $compare_result = false;
          continue;
        }
        
        // Check that the specific parameters of the fields match
        if (is_array($this->compare_mode) && in_array("structure", $this->compare_mode)) 
        {
          $s1_params = $schema_standard['tables'][$table_name][$field_name];
          $s2_params = $schema_current['tables'][$table_name][$field_name];
          
          foreach ($s1_params as $name => $details) 
          {
            if ($s1_params[$name] != $s2_params[$name]) 
            {
              if ($compare_table_result)
              {
                $this->__echo("Table ".$table_name.":", "error");
                $compare_table_result = false;
              }
              $this->__echo(' - Field ' . $field_name
                . ' differs between databases for parameter \''
                . $name . '\'. ' . $schema_standard['db_name']
                . ' has \'' . $s1_params[$name]
                . '\' and ' . $schema_current['db_name']
                . ' has \'' . $s2_params[$name] . '\'.', "warning");
              $compare_result = false;
            }
          }
        }
      }

      if (is_array($this->compare_mode) && in_array("data", $this->compare_mode) && $has_equal_fields_list &&
        isset($schema_standard['obj_PDO']) && isset($schema_current['obj_PDO'])) 
      {
        $obj_PDO_schema_standard = $schema_standard['obj_PDO'];
        $obj_PDO_schema_current = $schema_current['obj_PDO'];

        $field_fist = array();
        foreach ($schema_standard['tables'][$table_name] as $table_field) 
        {
          if ($table_field['Key'] != "PRI" && $table_field['Extra'] != "auto_increment") 
          {
            array_push($field_fist, $table_field['Field']);
          }
        }
        
        if (!empty($field_fist)) {
          $sql_select_from_schema_standard = "SELECT ".implode(", ", $field_fist)." FROM `".$table_name."`";
          $obj_PDOStatement = $obj_PDO_schema_standard->query($sql_select_from_schema_standard);

          if ($obj_PDOStatement)
          {
            $obj_PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
            $schema_standard_table_data = $obj_PDOStatement->fetchAll();

            $is_missing_data = false;  
            foreach ($schema_standard_table_data as $query_result)
            {
              $compare_data = array();
              foreach ($query_result as $field_name => $value) 
              {
                array_push($compare_data, "`".$field_name."` = '".$value."'");
              }

              $sql_select_from_schema_current = "SELECT COUNT(*) as `is_exists` FROM `".$table_name."` WHERE ".implode(" AND ", $compare_data);
              $obj_PDOStatement = $obj_PDO_schema_current->query($sql_select_from_schema_current);
              if ($obj_PDOStatement)
              {
                $obj_PDOStatement->setFetchMode(PDO::FETCH_ASSOC);
                $compare_result = $obj_PDOStatement->fetch();
                if ($compare_result['is_exists'] == 0) 
                {
                  if (!$is_missing_data) 
                  {
                    if ($compare_table_result)
                    {
                      $this->__echo("Table ".$table_name.":", "error");
                      $compare_table_result = false;
                    }
                    $this->__echo(" - Data from canonical database ".$schema_standard['db_name']." not found in ".$schema_current['db_name'].":",
                      "warning");
                    $is_missing_data = true;
                  }
                  $this->__echo("  - ".implode(", ", $compare_data), "warning");
                  $compare_result = false;
                }
              }
            }
          }
        }
      }
    }

    return $compare_result;
  }

  private function view_compare_results($data)
  {
    if($data && is_array($data))
    {
      foreach ($data as $k => $values)
      {
        $this->__echo("Table ". $k.":", "error");
        foreach($values as $value)
        {
          $this->__echo($value, "warning");
        }
      }
    }
  }


  private function __echo($message, $level = "info")
  {
    $echo_task = $this->project->createTask("echo");
    $echo_task->setLevel($level);
    $echo_task->setMessage($message);
    $echo_task->main();
  }

  private function PDO_execute($database_config, $sql_query)
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
      $result->query('SET NAMES UTF8');
    }
    catch (PDOException $e)
    {
      print "Error: " . $e->getMessage() . "\n";
      $result = false;
    }

    return $result;
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
      $result = new PDO($connection_string, $database_configure["username"], $database_configure["password"]);
      $result->query('SET NAMES UTF8');
    }
    catch (PDOException $e)
    {
      $message = "Can't connect to ".$database_configure['db_name']." database: ".$connection_string.
      ", username:".$database_configure["username"].", password:".$database_configure["password"];
      $this->__echo($message, 'error');
      $result = false;
    }
    return $result;
  }

}
