<?php
// file: /app/models/trade.php
class Trade extends AppModel {
	var $name = 'Trade';
	var $validate = array(
		'name'=>array(
			'rule' => 'notEmpty',
			'required'=>true,
			'allowEmpty'=>false,
			'message'=>'Please enter your name'
		),
		'password' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please enter your Password',
			'allowEmpty' => false,
			'required' => true,
			),
			'isConfirmed' => array(
				'rule' => array('isConfirmed'),
				'message' => 'Password not equal with Password Confirm',
				'required' => true,
			),
		),
		'password_confirm' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Password Confirm is required',
			'allowEmpty' => false,
			'required' => true,
			),
			'isConfirmed' => array(
				'rule' => array('isConfirmed'),
				'message' => 'Password Confirm not equal with Password',
				'required' => true,
			),
		),
		'email' => array(
			'notempty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Email is required',
			),
			'email' => array(
				'rule' => array('email', true),
				'message' => 'Email is invalid',
			),
			'isUnique' => array(
				'rule' => array('isUnique'),
				'message' => 'This email has already been taken',
			),
		),
		'company_name' => array(
			'rule' => 'notEmpty',
			'message' => 'Please enter company name'
		),
		'address' => array(
			'rule' => 'notEmpty',
			'message' => 'Please enter address'
		),
//		'resale' => array(
//			'rule' => 'notEmpty',
//			'message' => 'Please enter resale #'
//		)
	);

	public function isConfirmed($check) {
		if (array_key_exists('password', $this->data[$this->alias]) && array_key_exists('password_confirm', $this->data[$this->alias]) &&
			$this->data[$this->alias]['password'] === $this->data[$this->alias]['password_confirm']
		) {
			return true;
		}
		return false;
	}
	
	public function changePassword($traderData) {
		$trader = $this->findById($traderData['Trade']['trader_id']);
		
		if(($trader['Trade']['restore_key'] == $traderData['Trade']['restore_key']) && 
			(time() - strtotime(LIFETIME_RECOVERY_LINK, 0) - 
				strtotime($trader['Trade']['modified']) <= 0))
		{
			$salt = Configure::read('Security.salt');
			
			$this->id = $trader['Trade']['id'];
			if ($this->save(array(
				'password' => md5($traderData['Trade']['password'] . $salt),
				'password_confirm' => md5($traderData['Trade']['password_confirm'] . $salt),
				'restore_key' => '',
				'name' => $trader['Trade']['name']
			))) {
				return true;
			}
		}
	}

}
