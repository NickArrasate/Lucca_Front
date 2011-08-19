<?php

class Item extends AppModel {

  var $name = 'Item';
	var $actsAs = array('Containable');
	var $hasMany = array(
		'ItemVariation',
		'ItemImage',
		'InventoryQuantity' => array(
			'foreignKey' => 'item',
			'conditions' => 'InventoryQuantity.quantity >= 0 AND InventoryQuantity.quantity IS NOT NULL'
		)
	);
	var $belongsTo = array('ItemType', 'ItemCategory', 'InventoryLocation');
	var $cacheQueries = true;

	var $processLuccaItems = false;

	var $validate = array(
		'name' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'The name field cannot be left blank'
			)
		),
		'item_type_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please choose an item category'
			)
		),
		'item_category_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please choose an item subcategory'
			)
		),
		'inventory_location_id' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Please choose a location'
			)
		)

	);

	function get_status_count($item_type_id ='', $status) {

		$count = $this->find('count', array(
			'conditions' => array(
				'Item.status' => $status
			)
		));


		return $count;
	}

	function paginate($conditions, $fields, $order, $limit, $page = 1, $recursive = null, $extra = array()) {
		if ($this->processLuccaItems) {
			$results = $this->luccaOriginalsItems($conditions, $fields, $order, $limit, $page, $recursive, $extra);
		} else {
			$parameters = compact('conditions', 'fields', 'order', 'limit', 'page');
			if ($recursive != $this->recursive) {
				$parameters['recursive'] = $recursive;
			}
			$type = (isset($extra['type']) ? $extra['type'] : 'all');
			$results = $this->find($type, array_merge($parameters, $extra));
		}

		return $results;
	}

	function luccaOriginalsItems($conditions, $fields, $order, $limit, $page, $recursive, $extra) {
		$page = ($page) ? $page : 1 ;
		$dbo = $this->getDataSource();
		$subQuery = $dbo->buildStatement(
			array(
				'fields' => $fields,
				'table' => $dbo->fullTableName($this->useTable),
				'alias' => $this->name,
				'limit' => $limit,
				'offset' => ($page - 1) * $limit,
				'joins' => array(
					array(
						'table' => 'item_images',
						'alias' => 'ItemImage',
						'type' => 'LEFT',
						'conditions' => array(
							'ItemImage.item_id = Item.id AND ItemImage.primary = 1',
						)
					),
				),
				'conditions' => $conditions,
				'order' => $order,
				'group' => null
			),
			$this
    );
    $query = $subQuery;

		$result = $this->query($query);

		$this->Note = ClassRegistry::init('Note');
		foreach ($result as &$row) {
			$row['NoteCount'] = $this->Note->find('count', array('conditions' => array('Note.item' => $row['Item']['id'], '(Note.parent IS NULL OR Note.parent = 0)')));
			$row['OrderCount'] = $this->Note->find('count', array('conditions' => array('Note.item' => $row['Item']['id'], '(Note.parent IS NULL OR Note.parent = 0)', 'Note.status' => 3)));
			$row['Note'] = $this->Note->find('all', array('conditions' => array('Note.item' => $row['Item']['id'], '(Note.parent IS NULL OR Note.parent = 0)'), 'limit' => 10, 'order' => array('Note.created DESC')));
			$row['Order'] = $this->Note->find('all', array('conditions' => array('Note.item' => $row['Item']['id'], '(Note.parent IS NULL OR Note.parent = 0)', 'Note.status' => 3), 'limit' => 10, 'order' => array('Note.created DESC')));
			$row['ItemLA'] = null;
			$row['ItemNY'] = null;
			$row['ItemWH'] = null;
			$row['Children'] = $this->find(
				'all',
				array(
					'fields' => array(
						'Item.*',
						'ItemLA.*',
						'ItemNY.*',
						'ItemWH.*'
					),
					'conditions' => array(
						'Item.parent_id' => $row['Item']['id']
					),
					'joins' => array(
						array(
							'table' => 'inventory_quantity',
							'alias' => 'ItemLA',
							'type' => 'LEFT',
							'conditions' => array(
								'ItemLA.item = Item.id AND ItemLA.location = 1',
							)
						),
						array(
							'table' => 'inventory_quantity',
							'alias' => 'ItemNY',
							'type' => 'LEFT',
							'conditions' => array(
								'ItemNY.item = Item.id AND ItemNY.location = 2',
							)
						),
						array(
							'table' => 'inventory_quantity',
							'alias' => 'ItemWH',
							'type' => 'LEFT',
							'conditions' => array(
								'ItemWH.item = Item.id AND ItemWH.location = 3',
							)
						),
					),
					'contain' => array()
				)
			);
			$row['ChildrenCount'] = count($row['Children']);
			foreach ($row['Children'] as &$children) {
				$row['ItemLA'] += $children['ItemLA']['quantity'];
				$row['ItemNY'] += $children['ItemNY']['quantity'];
				$row['ItemWH'] += $children['ItemWH']['quantity'];

				$children['NoteCount'] = $this->Note->find('count', array('conditions' => array('Note.item' => $children['Item']['id'], '(Note.parent IS NULL OR Note.parent = 0)')));
				$children['Note'] = $this->Note->find('all', array('conditions' => array('Note.item' => $children['Item']['id'], '(Note.parent IS NULL OR Note.parent = 0)'), 'limit' => 10, 'order' => array('Note.created DESC')));
			}
		}

		return $result;
	}
}