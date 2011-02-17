<?php

class OrderedItem extends AppModel {

    var $name = 'OrderedItem';
	var $belongsTo = 'Order';
	var $hasOne = 'ItemVariation';
	var $cacheQueries = true;
	
	var $validate = array(
	);
	
}
