<?php 

# from http://bakery.cakephp.org/articles/view/component-for-forcing-a-secure-connection

class SslComponent extends Object {
    
    var $components = array('RequestHandler');
    
    var $Controller = null;
    
    function initialize(&$Controller) {
        $this->Controller = $Controller;
    }
    
    function force() {
        if(!$this->RequestHandler->isSSL()) {
            //$this->Controller->redirect('https://'.$this->__url());
        }
    }
    
    function __url() {
        $port = env('SERVER_PORT') == 80 ? '' : ':'.env('SERVER_PORT');

        return env('SERVER_NAME').$port.env('REQUEST_URI');
    }
}
?> 
