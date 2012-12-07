<?php
// /app/controllers/users_controller.php

class UsersController extends AppController {
	var $name = 'Users';
	var $helpers = array('Html', 'Form');

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
		//$salt = Configure::read('Security.salt');
		//echo md5('password'.$salt);

		// redirect user if already logged in
		if( $this->Session->check('User') ) {
			$this->redirect(array('controller'=>'item','action'=>'index','admin'=>true));
		}

		if(!empty($this->data)) {
			// set the form data to enable validation
			$this->User->set( $this->data );
			// see if the data validates
			if($this->User->validates()) {
				// check user is valid
				$result = $this->check_user_data($this->data);

				if( $result !== FALSE ) {
					// update login time
					$this->User->id = $result['User']['id'];
					$this->User->saveField('last_login',date("Y-m-d H:i:s"));
					// save to session
					$this->Session->write('User',$result);
					$this->Session->setFlash('You have successfully logged in');
					$this->redirect(array('controller'=>'item','action'=>'index','admin'=>true));
				} else {
					$this->Session->setFlash('Either your Username of Password is incorrect');
				}
			}
		}
	}

	/**
	 * Checks User data is valid before allowing access to system
	 * @param array $data
	 * @return boolean|array
	 */
	function check_user_data($data) {
		// init
		$return = FALSE;

		// find user with passed username
		$conditions = array(
			'User.username'=>$data['User']['username'],
			'User.status'=>'1'
		);
		$user = $this->User->find('first',array('conditions'=>$conditions));

		// not found
		if(!empty($user)) {
			$salt = Configure::read('Security.salt');
			// check password
			// yea -- i just md5ed my password in the database without the salt. eh.
			if($user['User']['password'] == md5($data['User']['password'] . $salt)) {
				$return = $user;
			}
		}

		return $return;
	}


	/**
	 * Logs out a User
	 */
	function logout() {
		if($this->Session->check('User')) {
			$this->Session->delete('User');
			$this->Session->setFlash('You have successfully logged out','flash_good');
		}
		$this->redirect(array('action'=>'login'));
	}
}
?>
