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
	var $components = array('RequestHandler', 'Email');

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
					$this->Session->setFlash('Welcome to Lucca Antiques');
					$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
				} else {
					$this->Session->setFlash('Either your Email or Password is incorrect');
				}
			}
		}
	}

    function register() {
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
					$this->message_for_inform_registration($this->data['Trade']);
					$this->Session->setFlash("Welcome to Lucca Antiques");
					$this->Session->write('Trade', $this->data);
					$this->redirect(array('controller'=>'trade', 'action'=>'login'));
				} else {
					$this->Session->setFlash("Error, please check below");
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
	
	function request_reset_pwd() {
		if ($this->RequestHandler->isAjax() && isset($this->data['Trade']['email'])) {
			Configure::write('debug', 0);
			$this->layout = 'ajax';
			$trader = $this->Trade->findByEmail($this->data['Trade']['email']);
			if ($trader) {
				$restore_key = md5(time());
				$reset_link = Router::url(
					array(
						'controller' => 'trade', 
						'action' => 'reset_password', 
						'trader_id' => $trader['Trade']['id'], 
						'restore_key' => $restore_key
					), true
				);
				$this->Trade->id = $trader['Trade']['id'];
				if($this->Trade->saveField('restore_key', $restore_key, false)){
					// sending email
					$send_result = $this->password_reset_email_send($trader, $reset_link);
					if ($send_result) {
						$this->set('status', 'success');
						$this->set('message', 'Password reset email sent');
					} else {
						$this->set('status', 'error');
						$this->set('message', 'Password reset email not sent');
					}
				} else {
					$this->set('status', 'error');
					$this->set('message', 'Password reset error');
				}
			} else {
				$this->set('status', 'error');
				$this->set('message', 'Email does not exist');
			}
		} else {
			$this->set('status', 'error');
			$this->set('message', 'Password reset error');
		}
		$this->render('request_reset_pwd');
	}

	public function reset_password() {
		// redirect user if already logged in
		if( $this->Session->check('Trade') ) {
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}

		if($this->RequestHandler->isGet() 
			&& array_key_exists('restore_key', $this->params['named']) 
			&& array_key_exists('trader_id', $this->params['named'])
		) {
			$trader = $this->Trade->findByRestore_key($this->params['named']['restore_key']);
			if(!$trader || $trader['Trade']['id'] != $this->params['named']['trader_id']) {
				$this->Session->setFlash("Sorry, invalid link");
			} else {
				$this->set('trader_id', $trader['Trade']['id']);
				$this->set('restore_key', $trader['Trade']['restore_key']);
			}
		} elseif($this->RequestHandler->isPost() && !empty($this->data['Trade'])) {
			$this->set('trader_id', $this->data['Trade']['trader_id']);
			$this->set('restore_key', $this->data['Trade']['restore_key']);
			$trader = $this->Trade->findById($this->data['Trade']['trader_id']);
			if ($trader) {
				$this->data['Trade']['name'] = $trader['Trade']['name'];
				$this->Trade->set($this->data);
				if ($this->Trade->validates(array('fieldList' => array('password', 'password_confirm'))) 
					&& $this->Trade->changePassword($this->data)) {
					$this->Session->setFlash('Password has been changed');
					$this->Session->write('Trade', $this->data);
					$this->redirect(array('controller' => 'trade', 'action' => 'login'));
				}
			} else {
				$this->Session->setFlash("Sorry, invalid request");
			}
		} else {
			$this->Session->setFlash("Sorry, invalid request");
			$this->redirect(array('controller' => 'pages', 'action' => 'display', 'home'));
		}
	}

	private function password_reset_email_send($trader, $reset_link) {
		$this->Email->sendAs= 'html';
		$this->Email->template = 'password_reset_email';
		$this->Email->to = $trader['Trade']['email'];
		$this->Email->from = NOREPLY_EMAIL;
		$this->Email->subject = 'Lucca Trade Password Reset';
		$this->set('email', $trader['Trade']['email']);
		$this->set('reset_link', $reset_link);
		return $this->Email->send();
	}

	private function message_for_inform_registration($trader) {
		$this->Email->sendAs= 'html';
		$this->Email->template = 'message_for_inform_registration';
		$this->Email->to = EMAIL_FOR_INFORM_REGISTRATION;
		$this->Email->from = NOREPLY_EMAIL;
		$this->Email->subject = 'Information about the new registration trader on Lucca';
		$this->set('trader', $trader);
		return $this->Email->send();
	}

}
