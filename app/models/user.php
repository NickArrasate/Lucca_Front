<?php
// file: /app/models/user.php
class User extends AppModel {
	var $name = 'User';
	var $validate = array(
		'username'=>array(
			'rule'=>VALID_NOT_EMPTY,
			'required'=>true,
			'allowEmpty'=>false,
			'message'=>'Please enter your Username'
		),
		'password'=>array(
			'rule'=>VALID_NOT_EMPTY,
			'required'=>true,
			'allowEmpty'=>false,
			'message'=>'Please enter your Password'
		)
	);
}
?>
