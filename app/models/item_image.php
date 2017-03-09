<?php

class ItemImage extends AppModel {
    var $name = 'ItemImage';
	var $belongsTo = 'Item';
	var $cacheQueries = true;
	
	/*
	var $validate = array(
		'filename' => array(
			'imageFile' => array(
				'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
				'message' => 'Please use either a gif, jpeg, png or jpg'
			)
	));
	*/
}
