<?php
class RestApiController extends AppController {
	var $name = 'RestApi';
	var $uses = array();
	var $components = array(
		'RequestHandler',
	);
	var $helpers = array(
		'Xml',
	);

	function beforeFilter() {
		parent::beforeFilter();
	}

/*	function item_index() {
		$data = $this->data;

		exit();
	} */
/*	function item_view() {
		$data = $this->data;

		exit();
	} */
	function item_add() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$response = array();

		$isDataValid = $this->__checkData($data, 'Item', 'Item');

		$this->loadModel('Item');
		if (!$isDataValid || !$this->Item->save($data)) {
			$response = array(
				'status' => array(
					'error' => 'Invalid data',
				),
				'item' => $this->Item->validationErrors,
			);
		} else {
			$itemQuantities = array(
				'ItemLAQuantity' => 1,
				'ItemNYQuantity' => 2,
				'ItemWHQuantity' => 3,
			);

			$this->loadModel('ItemImage');
			$this->loadModel('ItemVariation');
			$this->loadModel('ItemOccurrence');
			$this->loadModel('InventoryQuantity');

			$itemId = $this->Item->id;

			$categoryId = (array_key_exists('item_type_id', $data['Item'])) ? $data['Item']['item_type_id'] : null;
			$subcategoryId = (array_key_exists('item_category_id', $data['Item'])) ? $data['Item']['item_category_id'] : null;
			$locationIds = array();

			$this->InventoryQuantity->deleteAll(array('InventoryQuantity.item' => $itemId), false, false);
			foreach ($itemQuantities as $dataField => $locationId) {
				if (array_key_exists($dataField, $data['Item']) && is_numeric($data['Item'][$dataField])) {
					$uniqueKey['item'] = $itemId;
					$uniqueKey['location'] = $locationId;

					$extraFiels['quantity'] = intval($data['Item'][$dataField]);

					$this->InventoryQuantity->create();
					$this->InventoryQuantity->save(array_merge($extraFiels, $uniqueKey));

					array_push($locationIds, $locationId);
				}
			}

			$this->ItemOccurrence->createItemOccurrences($itemId, $categoryId, $subcategoryId, $locationIds);

			$imagesList = array();
			if (array_key_exists('ItemImage', $data['Item']) && !empty($data['Item']['ItemImage']) && is_array($data['Item']['ItemImage'])) {
				if (in_array(key($data['Item']['ItemImage']), array('id', 'item_id', 'data', 'filename', 'primary'), true)) {
					$data['Item']['ItemImage'] = array($data['Item']['ItemImage']);
				}
				foreach ($data['Item']['ItemImage'] as $itemImage) {
					$imageData = base64_decode($itemImage['data']);

					$fileParsedData = pathinfo($itemImage['filename']);
					$filename = substr(md5($imageData . time() . rand()), 0, 8) . '.' . $fileParsedData['extension'];

					if (file_put_contents(WWW_ROOT . '/files/' . $filename, $imageData)) {
						$this->ItemImage->create();
						$itemImage['filename'] = $filename;
						$itemImage['item_id'] = $itemId;
						if ($this->ItemImage->save($itemImage)) {
							array_push($imagesList, array('id' => $this->ItemImage->id, 'name' => $filename));
						}
					}
				}
			}

			if (array_key_exists('ItemVariation', $data['Item']) && !empty($data['Item']['ItemVariation']) && is_array($data['Item']['ItemVariation'])) {
				if (in_array(key($data['Item']['ItemVariation']), array('id', 'item_id', 'sku', 'price', 'name', 'quantity', 'primary'), true)) {
					$data['Item']['ItemVariation'] = array($data['Item']['ItemVariation']);
				}
				foreach ($data['Item']['ItemVariation'] as $itemVariation) {
					$this->ItemVariation->create();
					$itemVariation['item_id'] = $itemId;
					if (!array_key_exists('primary', $itemVariation) || !is_numeric($itemVariation['primary'])) {
						$itemVariation['primary'] = 0;
					}
					if (!intval($itemVariation['sku'])) {
						$itemVariation['sku'] = substr(time(), 4, 6);
					}
					$this->ItemVariation->save($itemVariation);
				}
			}

			$response = array(
				'status' => array(
					'success' => 'Item successful added',
				),
				'item' => array(
					'id' => $this->Item->id,
				),
				'images' => array(
					'image' => $imagesList,
				),
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function item_edit() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$response = array();

		$isDataValid = $this->__checkData($data, 'Item', 'Item');

		if ($isDataValid && !array_key_exists('id', $data['Item']) && array_key_exists('id', $this->params) && intval($this->params['id'])) {
			$data['Item']['id'] = intval($this->params['id']);
		}

		$this->loadModel('Item');
		if (!$isDataValid || !$this->Item->save($data)) {
			$response = array(
				'status' => array(
					'error' => 'Invalid data',
				),
				'item' => $this->Item->validationErrors,
			);
		} else {
			$itemQuantities = array(
				'ItemLAQuantity' => 1,
				'ItemNYQuantity' => 2,
				'ItemWHQuantity' => 3,
			);

			$this->loadModel('ItemImage');
			$this->loadModel('ItemVariation');
			$this->loadModel('ItemOccurrence');
			$this->loadModel('InventoryQuantity');

			$itemId = $this->Item->id;

			$categoryId = (array_key_exists('item_type_id', $data['Item'])) ? $data['Item']['item_type_id'] : null;
			$subcategoryId = (array_key_exists('item_category_id', $data['Item'])) ? $data['Item']['item_category_id'] : null;
			$locationIds = array();

			$this->InventoryQuantity->deleteAll(array('InventoryQuantity.item' => $itemId), false, false);
			foreach ($itemQuantities as $dataField => $locationId) {
				if (array_key_exists($dataField, $data['Item']) && is_numeric($data['Item'][$dataField])) {
					$uniqueKey['item'] = $itemId;
					$uniqueKey['location'] = $locationId;

					$extraFiels['quantity'] = intval($data['Item'][$dataField]);

					$this->InventoryQuantity->create();
					$this->InventoryQuantity->save(array_merge($extraFiels, $uniqueKey));

					array_push($locationIds, $locationId);
				}
			}

			$this->ItemOccurrence->createItemOccurrences($itemId, $categoryId, $subcategoryId, $locationIds);

			$imagesList = array();
			if (array_key_exists('ItemImage', $data['Item']) && !empty($data['Item']['ItemImage']) && is_array($data['Item']['ItemImage'])) {
				if (in_array(key($data['Item']['ItemImage']), array('id', 'item_id', 'data', 'filename', 'primary'), true)) {
					$data['Item']['ItemImage'] = array($data['Item']['ItemImage']);
				}
				foreach ($data['Item']['ItemImage'] as $itemImage) {
					$filename = false;
					if (array_key_exists('data', $itemImage) && array_key_exists('filename', $itemImage)) {
						$imageData = base64_decode($itemImage['data']);

						$fileParsedData = pathinfo($itemImage['filename']);
						$filename = substr(md5($imageData . time() . rand()), 0, 8) . '.' . $fileParsedData['extension'];

						if (file_put_contents(WWW_ROOT . '/files/' . $filename, $imageData)) {
							$itemImage['filename'] = $filename;
						}
					} elseif (array_key_exists('id', $itemImage)) {
						$existsImageData = $this->ItemImage->find('first', array('conditions' => array('ItemImage.id' => $itemImage['id']), 'recursive' => -1));
						$itemImage = array_merge($existsImageData['ItemImage'], $itemImage);
						$filename = $itemImage['filename'];
					}

					$this->ItemImage->create();
					$itemImage['item_id'] = $itemId;
					if ($this->ItemImage->save($itemImage)) {
						array_push($imagesList, array('id' => $this->ItemImage->id, 'name' => $filename));
					}
				}
			}

			if (array_key_exists('ItemVariation', $data['Item']) && !empty($data['Item']['ItemVariation']) && is_array($data['Item']['ItemVariation'])) {
				if (in_array(key($data['Item']['ItemVariation']), array('id', 'item_id', 'sku', 'price', 'name', 'quantity', 'primary'), true)) {
					$data['Item']['ItemVariation'] = array($data['Item']['ItemVariation']);
				}
				foreach ($data['Item']['ItemVariation'] as $itemVariation) {
					$this->ItemVariation->create();
					$itemVariation['item_id'] = $itemId;
					if (!array_key_exists('primary', $itemVariation) || !is_numeric($itemVariation['primary'])) {
						$itemVariation['primary'] = 0;
					}
					$this->ItemVariation->save($itemVariation);
				}
			}

			$response = array(
				'status' => array(
					'success' => 'Item successful updated',
				),
				'item' => array(
					'id' => $this->Item->id,
				),
				'images' => array(
					'image' => $imagesList,
				),
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function item_delete() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data;//->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$id = $this->params['id'];

		$response = array();

		$this->loadModel('Item');
		if (!$this->Item->delete($id)) {
			$response = array(
				'status' => array(
					'error' => 'Item can not be deleted',
				),
				'item' => array(
					'id' => $id,
				),
			);
		} else {
			$this->loadModel('ItemImage');
			$this->loadModel('ItemVariation');

			$itemPhotos = $this->ItemImage->find(
				'list',
				array(
					'conditions' => array(
						'ItemImage.item_id' => $id
					),
					'fields' => array(
						'ItemImage.id',
						'ItemImage.filename',
					)
				)
			);

			if ($itemProhotos) {
				foreach ($itemPhotos as $imageId => $filename) {
					if (
						!empty($filename) &&
						file_exists(WWW_ROOT . '/files/' . $filename) &&
						$this->ItemImage->delete($imageId)
					) {
						unlink(WWW_ROOT . '/files/' . $filename);
					}
				}
			}

			$this->ItemVariation->deleteAll(array('ItemVariation.item_id' => $id), false, false);

			$response = array(
				'status' => array(
					'success' => 'Item successful deleted',
				),
				'item' => array(
					'id' => $id,
				)
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function item_image_delete() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data;//->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$id = $this->params['id'];

		$response = array();

		$this->loadModel('ItemImage');
		$photo = $this->ItemImage->find('first', array('conditions' => array('ItemImage.id' => $id)));

		if (!$photo || !($deletionStatus = $this->ItemImage->delete($id))) {
			$response = array(
				'status' => array(
					'error' => 'Photo can not be deleted',
				),
				'item' => array(
					'id' => $id,
				),
			);
		} else {
			if (!empty($photo['ItemImage']['filename']) && file_exists(WWW_ROOT . '/files/' . $photo['ItemImage']['filename'])) {
				unlink(WWW_ROOT . '/files/' . $photo['ItemImage']['filename']);
			}

			$response = array(
				'status' => array(
					'success' => 'Photo successful deleted',
				),
				'item' => array(
					'id' => $id,
				)
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function category_add() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$response = array();

		$isDataValid = $this->__checkData($data, 'Category', 'ItemCategory');

		$this->loadModel('ItemCategory');
		if (!$isDataValid || !$this->ItemCategory->save($data['Category'])) {
			$response = array(
				'status' => array(
					'error' => 'Invalid data',
				),
				'category' => $this->ItemCategory->validationErrors,
			);
		} else {
			$response = array(
				'status' => array(
					'success' => 'Category successful added',
				),
				'category' => array(
					'id' => $this->ItemCategory->id,
				),
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function category_edit() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$response = array();

		$isDataValid = $this->__checkData($data, 'Category', 'ItemCategory');

		if ($isDataValid && !array_key_exists('id', $data['Category']) && array_key_exists('id', $this->params) && intval($this->params['id'])) {
			$data['Category']['id'] = intval($this->params['id']);
		}

		$this->loadModel('ItemCategory');
		if (!$isDataValid || !$this->ItemCategory->save($data['Category'])) {
			$response = array(
				'status' => array(
					'error' => 'Invalid data',
				),
				'category' => $this->ItemCategory->validationErrors,
			);
		} else {
			$response = array(
				'status' => array(
					'success' => 'Category successful updated',
				),
				'category' => array(
					'id' => $this->ItemCategory->id,
				),
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function category_delete() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data;//->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$id = $this->params['id'];

		$response = array();

		$this->loadModel('ItemCategory');
		if (!$this->ItemCategory->delete($id)) {
			$response = array(
				'status' => array(
					'error' => 'Category can not be deleted',
				),
				'item' => array(
					'id' => $id,
				),
			);
		} else {
			$response = array(
				'status' => array(
					'success' => 'Category successful deleted',
				),
				'category' => array(
					'id' => $id,
				)
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function type_add() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$response = array();

		$isDataValid = $this->__checkData($data, 'Type', 'ItemType');

		$this->loadModel('ItemType');
		if (!$isDataValid || !$this->ItemType->save($data['Type'])) {
			$response = array(
				'status' => array(
					'error' => 'Invalid data',
				),
				'type' => $this->ItemType->validationErrors,
			);
		} else {
			$response = array(
				'status' => array(
					'success' => 'Type successful added',
				),
				'type' => array(
					'id' => $this->ItemType->id,
				),
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function type_edit() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$response = array();

		$isDataValid = $this->__checkData($data, 'Type', 'ItemType');

		if ($isDataValid && !array_key_exists('id', $data['Type']) && array_key_exists('id', $this->params) && intval($this->params['id'])) {
			$data['Type']['id'] = intval($this->params['id']);
		}

		$this->loadModel('ItemType');
		if (!$isDataValid || !$this->ItemType->save($data['Type'])) {
			$response = array(
				'status' => array(
					'error' => 'Invalid data',
				),
				'type' => $this->ItemType->validationErrors,
			);
		} else {
			$response = array(
				'status' => array(
					'success' => 'Type successful updated',
				),
				'type' => array(
					'id' => $this->ItemType->id,
				),
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	function type_delete() {
		$this->layout = 'rest';
		Configure::write('debug', 0);

		$data = $this->data;//->toArray();

		$this->log($this->params, 'rest_log');
		$this->log($data, 'rest_log');

		$id = $this->params['id'];

		$response = array();

		$this->loadModel('ItemType');
		if (!$this->ItemType->delete($id)) {
			$response = array(
				'status' => array(
					'error' => 'Type can not be deleted',
				),
				'item' => array(
					'id' => $id,
				),
			);
		} else {
			$response = array(
				'status' => array(
					'success' => 'Type successful deleted',
				),
				'type' => array(
					'id' => $id,
				)
			);
		}

		$this->log($response, 'rest_log');

		$this->set(compact("response"));
	}

	private function __checkData($data, $key, $model) {
		if (!property_exists($this, $model)) {
			$this->loadModel($model);
		}

		if (!array_key_exists($key, $data)) {
			$this->{$model}->validationErrors = array('XML is invalid or empty');
			return false;
		}

		return true;
	}

	/*
	 * generate XML for testing tool
	 */
	function generate_tests() {
	}

	/*
	 * simple testing tool
	 */
	function run_tests() {
	}
}