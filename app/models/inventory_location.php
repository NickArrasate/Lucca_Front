<?php

class InventoryLocation extends AppModel {
    var $name = 'InventoryLocation';
	var $hasMany = 'Item';
	var $cacheQueries = true;
	
	function get_email($id) {
		$this->id = $id;
		return $this->field('email');
	}

	function get_all() {
		$locations = $this->find('list');
		$locations[0] = 'All';

		return $locations;
	}

}
