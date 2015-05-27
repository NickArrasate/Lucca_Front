<?php
// /app/controllers/trade_controller.php

class TradeController extends AppController {
    /**
     * This controller does not use a model
     *
     * @var array
     * @access public
     */
    var $uses = array('Trade');
	var $name = 'Trade';
	var $helpers = array('Html', 'Form');
	var $components = array('RequestHandler');

	/**
	 * Before any Controller Action
	 */
	function beforeFilter() {
		parent::beforeFilter();
	}

	/**
	 * Logs in a User
	 */
	function login() {
		// redirect user if already logged in
		if( $this->Session->check('Trade') ) {
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		if ($this->RequestHandler->isPost()) {
			if(!empty($this->data)) {
				$result = $this->check_trader_data($this->data);

				if( $result !== FALSE ) {
					// update login time
					$this->Trade->id = $result['Trade']['id'];
					$this->Trade->saveField('last_login', date("Y-m-d H:i:s"));
					// save to session
					$this->Session->write('Trade', $result);
					$this->Session->setFlash('Successfully logged in');
					$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
				} else {
					$this->Session->setFlash('Either your Email or Password is incorrect');
				}
			}
		}
	}

    function register() {
        # Nothing yet
		// redirect user if already logged in
		if( $this->Session->check('Trade') ) {
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		if ($this->RequestHandler->isPost()) {
			if(!empty($this->data)) {
				$salt = Configure::read('Security.salt');
				if (!empty($this->data['Trade']['password'])) {
					$this->data['Trade']['password'] = md5($this->data['Trade']['password'] . $salt);
				}
				if (!empty($this->data['Trade']['password'])) {
					$this->data['Trade']['password_confirm'] = md5($this->data['Trade']['password_confirm'] . $salt);
				}

				if($this->Trade->save($this->data)) {
					$this->Session->setFlash("Successfully logged in");
					$this->Session->write('Trade', $this->data);
					$this->redirect(array('controller'=>'trade', 'action'=>'login'));
				} else {
					$this->Session->setFlash("Error while registration");
				}
			}
		}
    }

    function custom() {
        # Nothing yet
    }
	
	/**
	 * Checks User data is valid before allowing access to system
	 * @param array $data
	 * @return boolean|array
	 */
	function check_trader_data($data) {
		// init
		$return = FALSE;

		// find user with passed username
		$conditions = array(
			'Trade.email' => $data['Trade']['email']
		);
		$trader = $this->Trade->find('first', array('conditions' => $conditions));

		// not found
		if(!empty($trader)) {
			$salt = Configure::read('Security.salt');
			// check password
			// yea -- i just md5ed my password in the database without the salt. eh. 
			if($trader['Trade']['password'] == md5($data['Trade']['password'] . $salt)) {
				$return = $trader;
			}
		}

		return $return;
	}


	/**
	 * Logs out a User
	 */
	function logout() {
		if($this->Session->check('Trade')) {
			$this->Session->delete('Trade');
			$this->Session->setFlash('You have successfully logged out');
		}
		$this->redirect(array('action'=>'login'));
	}
}
