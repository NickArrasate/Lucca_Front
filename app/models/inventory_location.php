<?php

class InventoryLocation extends AppModel {
    var $name = 'InventoryLocation';
	var $hasMany = 'Item';
	var $cacheQueries = true;
	
	function get_email($id) {
		$this->id = $id;
		return $this->field('email');
	}

}
