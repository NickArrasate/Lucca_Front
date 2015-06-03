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

	function item_quantities_tags() {
		$short_locations = $this->find('all', array(
				'recursive' => 0,
				'fields' => array('InventoryLocation.short')
			)
		);

		$list_tags = array();
		foreach ($short_locations as $short) {
			$list_tags['Item' . $short['InventoryLocation']['short'] . 'Quantity'] = $short['InventoryLocation']['id'];
		}

		return $list_tags;
	}

	function get_location_menu() {
		return $this->find('list', array(
			'fields' => array(
				'id',
				'display_name'
			)
		));
	}

}
