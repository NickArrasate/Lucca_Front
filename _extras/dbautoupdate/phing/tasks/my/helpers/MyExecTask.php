<?php
require_once 'phing/tasks/system/ExecTask.php';

/**
 * Executes a command on the shell.
 *
 * @author   Andreas Aderhold <andi@binarycloud.com>
 * @author   Hans Lellelid <hans@xmpl.org>
 * @version  $Revision: 552 $
 * @package  phing.tasks.system
 */
class MyExecTask extends ExecTask
{
    private $mode;
    
    function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function main()
    {
      $this->execute();
    }

    /**
     * Executes a program and returns the return code.
     * Output from command is logged at INFO level.
     * @return int Return code from execution.
     */
    public function execute()
    {
        $log_mode = '';
        if($this->mode != 'debug')
        {
          $log_mode = Project::MSG_VERBOSE;
        }
        // test if os match
        $myos = Phing::getProperty("os.name");
        $this->log("Myos = " . $myos, Project::MSG_VERBOSE);
        if (($this->os !== null) && (strpos($this->os, $myos) === false))
        {
            // this command will be executed only on the specified OS
            $this->log("Not found in " . $this->os, Project::MSG_VERBOSE);
            return 0;
        }

        if ($this->dir !== null) {
            if ($this->dir->isDirectory()) {
                $currdir = getcwd();
                @chdir($this->dir->getPath());
            } else {
                throw new BuildException("Can't chdir to:" . $this->dir->__toString());
            }
        }


        if ($this->escape == true) {
            // FIXME - figure out whether this is correct behavior
            $this->command = escapeshellcmd($this->command);
        }

        if ($this->error !== null) {
            $this->command .= ' 2> ' . $this->error->getPath();
            $this->log("Writing error output to: " . $this->error->getPath());
        }

        if ($this->output !== null) {
            $this->command .= ' 1> ' . $this->output->getPath();
            $this->log("Writing standard output to: " . $this->output->getPath());
        } elseif ($this->spawn) {
            $this->command .= ' 1>/dev/null';
            $this->log("Sending ouptut to /dev/null");
        }

        // If neither output nor error are being written to file
        // then we'll redirect error to stdout so that we can dump
        // it to screen below.

        if ($this->output === null && $this->error === null) {
            $this->command .= ' 2>&1';
        }

        // we ignore the spawn boolean for windows
        if ($this->spawn) {
            $this->command .= ' &';
        }

        $this->log("Executing command: " . $this->command, $log_mode);

        $output = array();
        $return = null;

        if ($this->passthru)
        {
            passthru($this->command, $return);
        }
        else
        {
            exec($this->command, $output, $return);
        }

        if ($this->dir !== null) {
            @chdir($currdir);
        }

        foreach($output as $line) {
            $this->log($line,  ($this->logOutput ? Project::MSG_INFO : Project::MSG_VERBOSE));
        }

        if ($this->returnProperty) {
            $this->project->setProperty($this->returnProperty, $return);
        }

        if ($this->outputProperty) {
            $this->project->setProperty($this->outputProperty, implode("\n", $output));
        }

        if($return != 0 && $this->checkreturn) {
            throw new BuildException("Task exited with code $return");
        }

        return $return;
    }

}
