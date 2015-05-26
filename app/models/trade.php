<?php
// file: /app/models/trade.php
class Trade extends AppModel {
	var $name = 'Trade';
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

