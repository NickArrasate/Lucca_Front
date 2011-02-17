<?php

class ItemCategory extends AppModel {
    var $name = 'ItemCategory';
	var $hasMany = 'Item';
	var $cacheQueries = true;
	
	function get_category_name($id) {
		$this->id = $id;
		return $this->field('name');
	}
}

