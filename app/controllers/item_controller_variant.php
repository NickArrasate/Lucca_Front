<?php


	class ItemController extends AppController {

		var $name = 'Item';
		
		var $helpers = array('Form', 'Ajax', 'Html', 'Cropimage', 'Paginator', 'Fieldformatting', 'Resizeimage');
		//var $components = array('Session', 'Email', 'JqImgcrop', 'RequestHandler', 'Navigation', 'Security');
		var $components = array('Session', 'Email', 'JqImgcrop', 'RequestHandler', 'Navigation', 'Ssl');
		
		var $paginate = array(
			'fields' => array('Item.id', 'Item.name', 'Item.publish_date', 'Item.status', 'Item.item_type_id'),
			'limit' => 8,        
			'order' => array(
				'Item.publish_date' => 'desc'
			)
		);
		
		function test() {
			
		}
		
		function beforeFilter() {
		
			#$this->Security->requireSecure();
			#$this->Security->blackHoleCallback = 'forceSSL';
			#$this->Security->validatePost = false;

		}
		
		function forceSSL() {
			$this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}

		function grid() {
		
			// once the user starts viewing items - set the session id
			
			$session_details = $this->Session->read();
			$user_agent = $session_details['Config']['userAgent'];
		
			// this is assuming that a visitor goes to a grid page before anywhere else...
			$this->Session->write('user_agent', $user_agent);
			//set the cart session variable
			$this->Session->write('cart_item_count', '0');
			
			$item_type = $this->params['pass'][0];
			$item_category = $this->params['pass'][1];
			$inventory_location = $this->params['pass'][2];
			$pager = (isset($this->params['pass'][3]) ? $this->params['pass'][3] : "");
			
			// Category Summary display
			switch($item_category) {
				case 'all':
				$category_summary = 'All items including Antiques, Lucca Studio, and Found';
				break;
				case '1':
				$category_summary = 'Furniture, decorative arts and lighting ranging from the 17th to mid 20th Century';
				break;
				case '2':
				$category_summary = 'Furniture and lighting line designed, manufactured and sold exclusively by Lucca Antiques';
				break;
				case '3':
				$category_summary = 'Unique old elements incorporated into original, limited edition Lucca designed pieces';
				break;
			}
			
			$this->set('category_summary', $category_summary);

			// Set grid display conditions
			$conditions_array = array (
				'OR' => array (
					'Item.status' => 'Available',
					array (
						'AND' => array(
							'Item.status' => 'Sold',
							'Item.sold_date >' => date('Y-m-d', strtotime("-7 days"))
						)
					)
				)
			);
			
			if ($item_type == '' || $item_type == 'all') {
				$item_type == 'all';
			} else {
				$conditions_array = array_merge($conditions_array, array(
					'Item.item_type_id' => $item_type
					)
				);
			}

			if ($item_category != 'all') {
				$conditions_array = array_merge($conditions_array, array(
					'Item.item_category_id' => $item_category
					)
				);
			}

			if ($inventory_location == '' || $inventory_location == 'all' ) {
				$inventory_location = 'all';
			} else {
				$conditions_array = array_merge($conditions_array, array(
					'Item.inventory_location_id' => $inventory_location
					)
				);
			}

			if ($pager == 'all') {
			
				$items = $this->Item->find('all', array(
					'conditions' => $conditions_array,
					'fields' => array('Item.name', 'Item.status'),
					'order' => array('Item.publish_date' => 'desc'),
					)
				);
				$this->set('all_items', 'All');

			} else {
			
				$count = $this->Item->find('count', array(
					'conditions' => $conditions_array,
					//'fields' => array('Item.name', 'Item.status'),
					)
				);
				
				$this->set('count', $count);
				
				$items = $this->paginate('Item', array(
					$conditions_array,
					)
				);
			
			}
			
			$chunked_items = array_chunk($items, 4);
			
			$this->loadModel('ItemType');
			$this->loadModel('ItemCategory');
			$this->loadModel('InventoryLocation');
			
			//$item_types = $this->ItemType->find('list');
			
			$item_categories = $this->ItemCategory->find('list', array(
				'fields' => array('ItemCategory.id', 'ItemCategory.name',)
			));
			
			$inventory_locations = $this->InventoryLocation->find('list', array(
				'fields' => array('InventoryLocation.id', 'InventoryLocation.name', 'InventoryLocation.contact',)
			));
			
			$current_item_type = $this->ItemType->find('list', array(
					'conditions' => array(
						'ItemType.id' => $item_type
					)
				)
			);

			$this->set('items', $chunked_items);
			//$this->set('item_types', $item_types);
			$this->set('item_categories', $item_categories);
			$this->set('inventory_location', $inventory_location);
			$this->set('current_item_type_name', $current_item_type);
			$this->set('current_item_type_id', $item_type);
			$this->set('current_item_category', $item_category);

		}
		
		function details() {
			
			$item_id = $this->params['pass'][0];
			
			$item_details = $this->Item->find('all', array(
				'conditions' => array(
					'Item.id' => $item_id
				)
			));

			/*
			$item_details = $this->Item->find('all', array(
			
				'conditions' => array(
					'Item.id' => $item_id
				),
				'fields' => array(
					'Item.id',
					'Item.name',
					'Item.description',
					'Item.units',
					'Item.height',
					'Item.height_2',
					'Item.width',
					'Item.depth',
					'Item.diameter',
					'Item.materials_and_techniques',
					'Item.country_of_origin',
					'Item.creator',
					'Item.period',
					'Item.condition',
					'Item.item_category_id',
					'Item.item_type_id',
					'Item.inventory_location_id',
					'Item.addon_id',
					'Item.status',
				),
				)
				
			);
			*/
			
			
			$this->loadModel('Addon');
			
			if($item_details[0]['Item']['addon_id'] !== '') {
				$addon_options = $this->Addon->Option->find('all' , array(
						'conditions' => array(
							'Option.addon_id' => $item_details[0]['Item']['addon_id']
						)
				));
				$this->set('options', $addon_options);
			}
			
			foreach($item_details as $item_detail) {
				foreach($item_detail['ItemImage'] as $item_image) {
					if ($item_image['primary'] == 1) {
						$primary_image = $item_image['filename'];
					}
				}
			}
	
			$current_date_time = date('Y-m-d h:i:s A');
			
			if(isset($this->params['pass'][1]) && $this->params['pass'][1]  == 'print' ){
			
				if($item_details[0]['Item']['addon_id'] !== '') {
					$this->Session->write('print_option_details', $addon_options);
				}
				
				$this->Session->write('print_item_details', $item_details);
				$this->Session->write('print_primary_image', $primary_image);
				$this->Session->write('print_item_type_id', $item_details[0]['Item']['item_type_id']);
				$this->Session->write('print_item_category_id', $item_details[0]['Item']['item_category_id']);
				$this->redirect('print_version/');
			}
			
			
			$this->set('item_details', $item_details);
			$this->set('item_category_id', $item_details[0]['Item']['item_category_id']);
			//$this->set('inventory_location_id', $item_details[0]['Item']['inventory_location_id']);
			$this->set('inventory_location_id', $item_details[0]['InventoryQuantity'][0]['location']);
			$this->set('item_type_id', $item_details[0]['Item']['item_type_id']);
			$this->set('primary_image', $primary_image);
			$this->set('current_date_time', $current_date_time);
			
			
		}
		
		function print_version() {
		
			$this->layout = 'print';
			$print_item_details = $this->Session->read('print_item_details');
			$primary_image = $this->Session->read('print_primary_image');
			$item_type_id = $this->Session->read('print_item_type_id');
			$item_category_id = $this->Session->read('print_item_category_id');
			
			if($option_details = $this->Session->check('print_option_details')) {
				$option_details = $this->Session->read('print_option_details');
				$this->set('options', $option_details);
			}
			
			$this->set('primary_image', $primary_image);
			$this->set('item_details', $print_item_details);
			$this->set('item_type_id', $item_type_id);
			$this->set('item_category_id', $item_category_id);
		}
		
		function email_item() {

			$this->layout = 'plain';
		
			if($this->Session->check('email_result')) {
				$this->set('email_result', $this->Session->read('email_result'));
				$this->Session->delete('email_result');
			} 
		
			if($this->data) {

				$this->loadModel('EmailMessage');
				$this->EmailMessage->set($this->data['EmailMessage']);
			
				if($this->EmailMessage->validates()) {
			
					$admin_email = "archive@luccaantiques.com";
					
					if($this->__send_item_emails($this->data, $admin_email, $this->data['EmailMessage']['subject']) && $this->__send_item_emails($this->data, $this->data['EmailMessage']['address'], $this->data['EmailMessage']['subject'])) {
						$this->set('email_result', array('Success. Message sent.'));
					} else {
						$this->set('email_result', $this->Session->read('smtp_errors'));
					}
				} else {
					$this->set('email_item_errors', $this->EmailMessage->invalidFields());
					$this->set('temp_email_item', $this->data);
				}
			} else {
				$this->set('email_result', array('No Data'));
			}
		}
		
		/*********************************************************************************************
		*
		The functions below are admin functions related to the item model
		*
		*********************************************************************************************/
		
		function admin_create() {
		
			//$this->layout = 'admin_product_management';
		
			if($this->data) {
			
				// should do this in the model .....checking to see if there is a primary image...
				
				if($this->data['ItemImage'][0]['filename']['name'] == '') {
				
					$this->Session->write('errors_images', array('Please include a Main Image'));
					$this->redirect('/admin/item/image/create');
				
				} else {
				
					$this->loadModel('Autofill');
						

					$contact = $this->Autofill->find('all', array(
						'fields' => 'content',
						'conditions' => array(
							'name' => 'Contact'
						)
					));

					
					$status = 'Unsorted';
					$this->Item->set('status', $status);
					$this->Item->set('publish_date', date('Y-m-d h:i:s A'));
					$this->Item->set('contact', $contact[0]['Autofill']['content']);
					
					$this->Item->save();
					$item_id = $this->Item->getLastInsertId();
					
					// need to also create the rows for the item_images, and item variations. 
					
					$this->loadModel('ItemVariation');
					

					$item_variation_sku = mt_rand(100000, 999999);
					
					if($this->ItemVariation->validates()) {
						$this->ItemVariation->set('sku', $item_variation_sku);
					} else {
						$item_variation_sku = mt_rand(100000, 999999);
					}
					
					$this->ItemVariation->set('primary', 1);
					$this->ItemVariation->set('item_id', $item_id);
					$this->ItemVariation->save();
					
					$this->loadModel('ItemImage');
	
					foreach($this->data['ItemImage'] as $image) {

						if($image['filename']['error'] == 0) {
						
							$random_numbers = substr(md5(uniqid()), 0, 10);
							
							$basename = basename($image['filename']['name']);
							
							$file_ext = substr($basename, strrpos($basename, ".") + 1);
							
							$new_filename = $random_numbers . '.' . $file_ext;
						
							$this->ItemImage->create();
							$this->ItemImage->set('filename', $new_filename);
							$this->ItemImage->set('primary', $image['primary']);
							$this->ItemImage->set('item_id', $item_id);
					
							$uploaded = $this->JqImgcrop->uploadImage($image['filename'], 'files');

							$this->ItemImage->save();
							
							// rename file
							$path = WWW_ROOT . '/files/';
							rename($path . $basename, $path . $new_filename);
							
						
						} else {
						
							$this->ItemImage->create();
							$this->ItemImage->set('filename', '');
							$this->ItemImage->set('primary', 0);
							$this->ItemImage->set('item_id', $item_id);
							
							$this->ItemImage->save();
						
						}
						
						
					}

					//$this->set('images', $this->data['ItemImage']);
					//$this->set('uploaded', $uploaded);

					$this->redirect('/admin/item/image/edit/'. $item_id);
					
				}
			}
		
		}
		
		function admin_index() {
			$this->redirect('grid/all/Unpublished');
		}
		
		function admin_image() {
		
			$this->Ssl->force(); 
			
			// displays form fields for uploading images. initialzes a session array for creating a new item
			$this->layout = 'admin_product_management';
			
			if($this->Session->check('errors_images')) {
				$this->set('errors_images', $this->Session->read('errors_images'));
				$this->Session->delete('errors_images');
			}
			
			if($this->params['pass'][0] == 'create') {
			
				$navigation = array(
					array(
						'link' => '/admin/item/image/create',
						'class' => 'active',
						'title' => 'Create New Product',
					),
					array(
						'link' => '/admin/item/grid/all/Unpublished',
						'class' => '',
						'title' => 'Works in Progress', 'count' => $this->Item->get_status_count('all','Unpublished')
					),
					array(
						'link' => '/admin/item/grid/all/Unsorted',
						'class' => '',
						'title' => 'Unsorted', 'count' => $this->Item->get_status_count('all','Unsorted')
					)
				);
				
				$admin_subnavigation = array(
					array(
						'link' => '/admin/item/grid/all/Unpublished/',
						'class' => 'active',
						'title' => 'New / Works in Progress',
					),
					array(
						'link' => '/admin/item/grid/all/Available/',
						'class' => '',
						'title' => 'Online Inventory',
					),
					array(
						'link' => '/admin/addon/categories/edit/',
						'class' => '',
						'title' => 'Manage Addons',
					),
					array(
						'link' => '/admin/autofill/edit',
						'class' => '',
						'title' => 'Autofill Text',
					)
				);
				$this->Session->write('admin_subnavigation',$admin_subnavigation);
				
				$this->set('h3','Create New Item: Add Images');
				$this->set('navigation', $navigation);
				$this->set('action', $this->params['pass'][0]);
				
				//$this->set('main_image', $main_image);
				//$this->set('detail_images', $detail_images);
				//$this->set('item_id', $item_id);
			
			} elseif($this->params['pass'][0] == 'edit') {
			
				if($this->Session->check('errors_images')) {
					$this->set('errors_images', $errors_images);
					$this->Session->delete('errors_images');
				}
			
				$item_id = $this->params['pass'][1];
				
				if(isset($this->params['pass'][2])) {
					if($this->params['pass'][2] == 'create') {
						$this->set('create_buttons', '');
						$this->set('item_id', $item_id);
					}
				}
				
				$item_details = $this->Item->find('all', array(
						'conditions' => array(
							'Item.id' => $item_id
						)
				));
				
				foreach($item_details[0]['ItemImage'] as $i) {
					if($i['primary'] == 1) {
						$main_image = $i;
					} else {
						$detail_images[] = $i;
					}
				}
				
				$item_type_id = $item_details[0]['ItemType']['id'];
				$status = $item_details[0]['Item']['status'];
				
				$navmenu = $this->Navigation->navigation($status, $item_type_id);
				$this->set('navigation', $navmenu['navigation']);
				$this->Session->write('admin_subnavigation', $navmenu['subnavigation']);
				
				$this->set('h3','Edit Images : ' . $item_details[0]['Item']['name']);
				
				$this->set('item_details', $item_details);
				$this->set('main_image', $main_image);
				$this->set('detail_images', $detail_images);
				$this->set('settings', array('h' => 120, 'w'=>120,'crop'=>1));
				$this->set('action', $this->params['pass'][0]);
			
			} else {
				$this->redirect('error/');
			}
		}
		
		function admin_variations() {
		
			$this->Ssl->force(); 
		
			$item_id = $this->params['pass'][1];
			$action = $this->params['pass'][0];
			
			$this->layout = 'admin_product_management';
			$this->loadModel('ItemVariation');
			
			switch($action) {
				case 'edit':
					if($this->Session->check('errors_item_variation')) {
						$this->set('errors_item_variation', $this->Session->read('errors_item_variation'));
						$this->Session->delete('errors_item_variation');
					}
				break;
				
				case 'add':
					if($this->data) {
						if($this->ItemVariation->save($this->data)) {
							$this->redirect('variations/edit/' . $item_id);
						} else {
							$this->Session->write('errors_item_variation', $this->ItemVariation->invalidFields());
							$this->redirect('variations/edit/' . $item_id);
						}
					}
				break;
				
				case 'delete':
					$variation_id = $this->params['pass'][2];
					$item_id = $this->params['pass'][1];
					$this->ItemVariation->delete($id = $variation_id);
					$this->redirect('variations/edit/' . $item_id);
				break;
				
				case 'save':
				
					$item_id = $this->params['pass'][1];
					$count = count($this->data['ItemVariation']['name']);
					for ($i=0; $i< $count; $i++) {
					
						$this->ItemVariation->id = $this->data['ItemVariation']['id'][$i];
						$this->ItemVariation->set('name', $this->data['ItemVariation']['name'][$i]);
						$this->ItemVariation->set('price', $this->data['ItemVariation']['price'][$i]);
						$this->ItemVariation->save();
					}
					$this->redirect('variations/edit/' . $item_id);
					
				break;
			
			}
			
			$item = $this->Item->find('all', array(
					'fields' => array('name', 'description', 'item_type_id', 'item_category_id', 'status'),
					'conditions' => array(
						'Item.id' => $item_id
					)
			));
		
			
			$status = $item[0]['Item']['status'];
			$item_type_id = $item[0]['Item']['item_type_id'];
			
			/********************** using the navigation component *********************/
			
			$navmenu = $this->Navigation->navigation($status, $item_type_id);
			
			$this->Session->write('admin_subnavigation',$navmenu['subnavigation']);
			//$this->set('navigation', $navigation);
			$this->set('navigation', $navmenu['navigation']);
			//$this->Session->write('admin_subnavigation',$admin_subnavigation);
			
			/*********************** end using the navigation compontent **************/
			
			$item_variations = $this->ItemVariation->find('all', array(
					'fields' => array('id', 'sku','name', 'price', 'quantity', 'primary'),
					'conditions' => array(
						//'ItemVariation.primary' => 0,
						'ItemVariation.item_id' => $item_id
					)
			));
			
			$this->loadModel('ItemCategory');
			$this->loadModel('ItemType');
			$this->loadModel('InventoryLocation');
			
			$item_category = $this->ItemCategory->find('list', array(
					'fields' => array('name'),
					'conditions' => array(
						'ItemCategory.id' => $item[0]['Item']['item_category_id']
					)
			));
			
			$item_type = $this->ItemType->find('list', array(
					'fields' => array('name'),
					'conditions' => array(
						'ItemType.id' => $item[0]['Item']['item_type_id']
					)
			));

			$inventory_location = $this->InventoryLocation->find('list', array(
					'fields' => array('name','contact'),
					'conditions' => array(
						'InventoryLocation.id' => $item[0]['Item']['inventory_location_id']
					)
			));

			$this->set('item_type', $item_type);
			$this->set('item', $item);
			$this->set('item_id', $item_id);
			$this->set('item_category', $item_category);
			$this->set('inventory_location', $inventory_location);
			$this->set('item_variations', $item_variations);
			
			$this->set('h3','Edit Variations : ' . $item[0]['Item']['name'] );
			
			
			
			//$this->set('unique_sku', substr(md5(uniqid()), 0, 6));
			
			$unique_sku = mt_rand(100000, 999999);
				
			if($this->ItemVariation->validates()) {
				$this->ItemVariation->set('sku', $unique_sku);
			} else {
				$item_variation_sku = mt_rand(100000, 999999);
			}
			
			$this->set('unique_sku', $unique_sku);
			
			$this->set('$item_variations', $item_variations);
			
		}
				
		function admin_details() {
		
			$this->Ssl->force(); 
			
			// requires the item id inthe first url parameter .
			$this->layout = 'admin_product_management';
			
			$this->loadModel('ItemCategory');
			$this->loadModel('ItemType');
			$this->loadModel('InventoryLocation');
			
			$item_category = $this->ItemCategory->find('list', array(
				'fields' => array(
					'ItemCategory.id',
					'ItemCategory.name',
				)
			));
			$item_type = $this->ItemType->find('list', array(
				'fields' => array(
					'ItemType.id',
					'ItemType.name',
				)
			));
			$inventory_location = $this->InventoryLocation->find('list', array(
				'fields' => array(
					'InventoryLocation.id',
					'InventoryLocation.name',
				)
			));
			
			$this->loadModel('Addon');
			$addons = $this->Addon->find('list');
			
			if($this->Session->check('errors_item_variation')) {
				$this->set('errors_item_variation', $this->Session->read('errors_item_variation'));
				$this->Session->delete('errors_item_variation');
			}
			
			if($this->Session->check('errors_item')) {
				$this->set('errors_item', $this->Session->read('errors_item'));
				$this->Session->delete('errors_item');
			}
			
			if($this->params['pass'][0] == 'create') {
			
				if($this->Session->check('images')){
					
					$navigation = array(
						array(
							'link' => '/admin/item/image/create',
							'class' => 'active',
							'title' => 'Create New Product',
						),
						array(
							'link' => '/admin/item/grid/all/Unpublished',
							'class' => '',
							'title' => 'Works in Progress', 'count' => $this->Item->get_status_count($item_type[0]['ItemType']['id'], 'Unpublished')
						)
					);
					
					$admin_subnavigation = array(
						array(
							'link' => '/admin/item/grid/all/Unpublished/',
							'class' => 'active',
							'title' => 'New / Works in Progress',
						),
						array(
							'link' => '/admin/item/grid/all/Available/',
							'class' => '',
							'title' => 'Online Inventory',
						),
						array(
							'link' => '/admin/addon/categories/edit/',
							'class' => '',
							'title' => 'Manage Addons',
						),
						array(
							'link' => '/admin/autofill/edit',
							'class' => '',
							'title' => 'Autofill Text',
						)
					);
					$this->Session->write('admin_subnavigation',$admin_subnavigation);
					
					$this->loadModel('Autofill');
					
					$autofills = $this->Autofill->find('list', array(
						'fields' => array('name', 'content')
					));
					
					$random_variation_id = substr(md5(uniqid()), 0, 6);
					$another_random_variation_id = substr(md5(uniqid()), 0, 6);
					
					$this->set('h3','Create New Item: Add Item Information');
					$this->set('navigation', $navigation);
					// do i need this? itsnot in the templates
					$this->set('images', $this->Session->read('images'));
					$this->set('autofills', $autofills);
					$this->set('random_variation_id', $random_variation_id);
					$this->set('another_random_variation_id', $another_random_variation_id);
				
				} else {
					$this->redirect('error/');
				}
				
				
			} else {
			
				$item_id = $this->params['pass'][1];
				
				$item_details = $this->Item->find('all', array(
						'conditions' => array(
							'Item.id' => $item_id
						)
				));
				
				$item_variations = array();
				
				foreach($item_details[0]['ItemVariation'] as $i) {
					//if($i['primary'] == 0 ) {
					$item_variations[] = $i;
					//}
				}
				
				$item_type_id = $item_details[0]['ItemType']['id'];
				$status = $item_details[0]['Item']['status'];
				
				$navmenu = $this->Navigation->navigation($status, $item_type_id);
				
				$this->Session->write('admin_subnavigation',$navmenu['subnavigation']);
				$this->set('navigation', $navmenu['navigation']);
				$this->set('item_statuses', $navmenu['item_statuses']);
				
				$this->set('h3','Edit Details : ' . $item_details[0]['Item']['name']);
				$this->set('item_details', $item_details);
				$this->set('item_variations', $item_variations);
				$this->set('settings', array('h' => 100, 'w'=>100,'crop'=>1));
				
				if($this->Session->check('data')) {
					$this->set('data', $this->Session->read('data'));
					$this->Session->delete('data');
				}
				if($this->Session->check('details_feedback_message')) {
					$this->set('details_feedback_message', $this->Session->read('details_feedback_message'));
					$this->Session->delete('details_feedback_message');
				}
				
			
			}
			
			$this->set('item_type', $item_type);
			$this->set('item_category', $item_category);
			$this->set('inventory_location', $inventory_location);
			$this->set('addons', $addons);
		
		}
		
		function admin_preview() {
			// requires the item id inthe first url parameter. 
		}
		
		function admin_email() {
		
			$this->Ssl->force(); 
			// requires the item id inthe first url parameter. 
			if(isset($this->params['pass'][0])) {
				$this->layout = 'admin_product_management';
				$item_id = $this->params['pass'][0];
				$item_details = $this->Item->find('all', array(
					'conditions' => array(
						'Item.id' => $item_id
					)
				));
				/*
				$item_details = $this->Item->find('all', array(
						'fields' => array(
							'Item.name',
							'Item.description',
							'Item.condition',
							'Item.height',
							'Item.height_2',
							'Item.width',
							'Item.depth',
							'Item.diameter',
							'Item.materials_and_techniques',
							'Item.creator',
							'Item.country_of_origin',
							'Item.period',
							'Item.status',
							'Item.item_type_id',
							'Item.units'
						),
						'conditions' => array(
							'Item.id' => $item_id
						)
				));
				*/
				
				$item_type_id = $item_details[0]['Item']['item_type_id'];
				$status = $item_details[0]['Item']['status'];
				
				$navmenu = $this->Navigation->navigation($status, $item_type_id);
	
				$this->Session->write('admin_subnavigation',$navmenu['subnavigation']);
				$this->set('navigation', $navmenu['navigation']);
				$this->set('item_statuses', $navmenu['item_statuses']);
				
				$this->set('item_details', $item_details);
				
				$this->set('settings', array('h' => 74, 'w'=>74,'crop'=>1));
				
				$this->loadModel('ItemType');
				$this->set('itemtype', $this->ItemType->get_name($item_details[0]['Item']['item_type_id']));
				if($this->Session->check('email_result')) {
					$this->set('email_result', $this->Session->read('email_result'));
					$this->Session->del('email_result');
				}
				if($this->Session->check('email_item_errors')) {
					$this->set('email_item_errors', $this->Session->read('email_item_errors'));
					$this->Session->del('email_item_errors');
				}
				if($this->Session->check('temp_email_item')) {
					$this->set('temp_email_item', $this->Session->read('temp_email_item'));
					$this->Session->del('temp_email_item');
				}
			} else {
				$this->redirect('error/');
			}
		}
		
		function admin_grid() {
		
			$this->Ssl->force(); 
			// will return a list/grid of unpublished items
			$this->layout = 'admin_product_management';
			//$type_id = $this->params['pass'][0]; // or the data that is passed...throgh $this->data...
			
			$type_id = $this->params['pass'][0];
			$status = $this->params['pass'][1];
			$inventory_location = $this->params['pass'][3];
			
			$navmenu = $this->Navigation->navigation($status, $type_id);
			
			$this->set('navigation', $navmenu['navigation']);
			$this->set('h3', $navmenu['h3']);
			$this->Session->write('admin_subnavigation', $navmenu['subnavigation']);
			
			$this->loadModel('ItemType');
			
			
			$item_types = $this->ItemType->find('list', array(
					'fields' => array('id', 'name')
			));
			
			$item_types['all'] = '-- All Categories --';
			
			if ($inventory_location == '' || $inventory_location == 'all' ) {
				$inventory_location = 'all';
			} 			
			
			if(isset($this->params['pass'][2]) && ($this->params['pass'][2] == 'all')) {
				// view all button is clicked

				if($type_id == 'all') {

					$items = $this->Item->find('all', array(
							'conditions' => array(
								'Item.status' => $status,
								'Item.inventory_location_id' => $inventory_location,
								),
							'order' => array('Item.publish_date' => 'desc')
							)
						);
						
					$count = $this->Item->find('count', array(
							'conditions' => array(
								'Item.status' => $status,
								'Item.inventory_location_id' => $inventory_location,
								),
							'order' => array('Item.publish_date' => 'desc')
							)
						);
				} else {

					$items = $this->Item->find('all', array(
							'conditions' => array(
								'Item.status' => $status,
								'Item.item_type_id' => $type_id,
								'Item.inventory_location_id' => $inventory_location,
								),
							'order' => array('Item.publish_date' => 'desc')
							)
						);
						
					$count = $this->Item->find('count', array(
							'conditions' => array(
								'Item.status' => $status,
								'Item.item_type_id' => $type_id,
								'Item.inventory_location_id' => $inventory_location,
								),
							'order' => array('Item.publish_date' => 'desc')
							)
						);
				}
				
				$this->set('all_items','all_items');
			
			} else {
			
				if($type_id == 'all') {
					$items = $this->paginate('Item', array(
						'Item.status' => $status,
						'Item.inventory_location_id' => $inventory_location
						)
					);
					
					$count = $this->Item->find('count', array(
						'conditions' => array(
							'Item.status' => $status,
							'Item.inventory_location_id' => $inventory_location
							)
						)
					);

				} else {
					$items = $this->paginate('Item', array(
						'Item.status' => $status,
						'Item.item_type_id' => $type_id,
						'Item.inventory_location_id' => $inventory_location,
						)
					);
					
					$count = $this->Item->find('count', array(
						'conditions' => array(
							'Item.status' => $status,
							'Item.item_type_id' => $type_id,
							'Item.inventory_location_id' => $inventory_location,
							)
						)
					);
					
				}
			}
			
			
			
			$this->set('count',$count);
			// this little extra bit of transforming is a little unecessary 
			foreach($item_types as $key => $name) {
				if($key == $type_id) {
					$type_name = $name;
					$type_id = $key;
				}
				
			}
			
			if(!isset($type_name)) {
				$type_name = 'All';
				$type_id = 'all';
			}
			$this->set('type_name', $type_name);
			$this->set('type_id', $type_id);
					
			$chunked_items = array_chunk($items, 4);
			
			
			$this->set('chunked_items', $chunked_items);
			$this->set('item_types', $item_types);
			$this->set('status', $status);
			$this->set('settings', array('w'=>142, 'h' => 142, 'crop'=>1));
			
			//$this->set('item_category', $item_category);
		}
		
		function admin_summary() {
		
			$this->Ssl->force(); 
		
			$this->layout = 'admin_product_management';
			$item_id = $this->params['pass'][0];
			$item_details = $this->Item->find('all', array(
					'conditions' => array(
						'Item.id' => $item_id
					)
			));
			
			$item_variations = array();
				
			foreach($item_details[0]['ItemVariation'] as $i) {
				//if($i['primary'] == 0 ) {
					$item_variations[] = $i;
				//}
			}
				
			$item_type_id = $item_details[0]['ItemType']['id'];
			
			$item_status = $item_details[0]['Item']['status'];
			
			$navmenu = $this->Navigation->navigation($item_status, $item_type_id);
			
			$this->set('navigation', $navmenu['navigation']);
			$this->set('item_statuses', $navmenu['item_statuses']);
			$this->Session->write('admin_subnavigation', $navmenu['subnavigation']);
			
			$this->loadModel('Addon');
			$addons = $this->Addon->find('list', array(
					'conditions' =>array(
						'Addon.id' => $item_details[0]['Item']['addon_id']
					)
			));	
			
			if($item_status == 'Unpublished') {
				$item_status = 'Works in Progress';
			}
			
			
			$this->set('addons', $addons);
			$this->set('h3','Item Summary: ');
			
			$this->set('item_details', $item_details);
			
			$this->set('item_variations', $item_variations);
			$this->set('status', $item_status);
			$this->set('main_settings', array('w'=>230,'crop'=>1));
			$this->set('thumb_settings', array('h' => 100, 'w'=>100,'crop'=>1));

		}
					
		function admin_error() {
		
			$this->Ssl->force(); 
			
			$this->layout = 'admin_product_management';
		}

		
		function admin_update_details() {
			
			if(!empty($this->data)) {
				$item_id = $this->params['pass'][0];
				
				$this->loadModel('ItemVariation');
				$this->ItemVariation->set($this->data['ItemVariation']);
				
				$this->Item->id = $item_id;
				
				if( $this->Item->save($this->data) && $this->ItemVariation->validates() )  {
				
				
					if($this->data['Item']['item_category_id'] == '1' ) {
						// 1 is an antique
						$quantity = '"' . '1' . '"';
					} elseif ($this->data['Item']['item_category_id'] == '2') {
						// 2 is lucca studio. doesnt matter what qty i set because it's not subtracted from when orders are placed.
						$quantity = '"' . ' 9999999 ' . '"';
					} elseif ($this->data['Item']['item_category_id'] == '3') {
						// 3 is a found item
						if(isset($this->data['ItemVariation']['quantity'])) {
							$quantity = $this->data['ItemVariation']['quantity'];
						} else {
							$quantity = '"' . '0000000000' . '"';
						}
					} else {
						$quantity = '"' . '  ' . '"';
					}
					
					$this->ItemVariation->updateAll(
						array(
							'price' => $this->data['ItemVariation']['price'],
							'quantity' => $quantity,
							'name' => '"' .$this->data['Item']['name'] . ' (Main)' . '"'
						),
						array(
							'item_id' => $item_id,
							'primary' => '1'
						)
					);
					
					
					$this->Session->write('details_feedback_message', 'Details saved.');
					$this->redirect('details/edit/' . $item_id);
				} else {
					$this->Session->write('data', $this->data);
					$this->Session->write('errors_item', $this->Item->invalidFields());
					$this->Session->write('errors_item_variation', $this->ItemVariation->invalidFields());
					//$this->redirect('details/edit/' . $item_id);
					$this->redirect('details/edit/' . $item_id);
				}
				
				
			}
			
		}
	
		function admin_update_status() {
			// redirects to view_details with the item id
			if(!empty($this->data)) {
				$item_id = $this->params['pass'][0];
				$this->Item->id = $item_id;
				$this->Item->saveField('status', $this->data['Item']['status']);
				// Update sold_date field - set if Sold, unset if Available, don't touch if Hidden
				if ($this->data['Item']['status'] == 'Sold') {
					$this->Item->saveField('sold_date', date("Y-m-d"));
				} elseif ($this->data['Item']['status'] == 'Available') {
					$this->Item->saveField('sold_date', null);
				}
			}
			
			$this->redirect('summary/'. $item_id);
		}
	
		function admin_publish() {
			//  redirects to view all
			$item_id = $this->params['pass'][0];
			$item_type_id = $this->params['pass'][1];
			$item_status = $this->params['pass'][2];
			
			$this->Item->id = $item_id;
			$this->Item->saveField('status', 'Available');

			/*
			$this->Item->updateAll(
				//fields to be updated
				array('Item.variation_id' => $item_variation_id ),
				// conditions
				array('Item.id' => $item_id)
			);
			*/
			$this->redirect('grid/'. $item_type_id .'/Available/');
		}
		
		function admin_save() {
		
			if(!empty($this->data)) {

				$this->loadModel('ItemVariation');
				$this->data['Item']['publish_date'] = date('Y-m-d h:i:s A');
				$this->data['Item']['status'] = 'Unpublished';

				// need to do validation before saving. 
				$this->Item->set($this->data['Item']);
				$this->ItemVariation->set($this->data['ItemVariation']);
				
				if($this->Item->validates() && $this->ItemVariation->validates()) {
					
					$this->Item->save($this->data['Item'], array('validate' => false));
					$this->ItemVariation->save($this->data['ItemVariation'], array('validate' => false));
					
					$item_variation_id = $this->ItemVariation->getLastInsertId();
					$item_id = $this->Item->getLastInsertId();
					
					$this->Item->updateAll(
						//fields to be updated
						array('Item.variation_id' => $item_variation_id ),
						// conditions
						array('Item.id' => $item_id)
					);
					$this->ItemVariation->updateAll(
						//fields to be updated
						array( 'ItemVariation.item_id' => $item_id ),
						// conditions
						array('ItemVariation.id' => $item_variation_id)
					);
					
					// then save the next variation if it is around
					
					$this->ItemVariation->create();
					$this->ItemVariation->set($this->data['AnotherItemVariation']);
					
					if($this->ItemVariation->validates() ) {
						$this->data['AnotherItemVariation']['item_id'] = $item_id;
						$this->ItemVariation->save($this->data['AnotherItemVariation'], array('validate' => false));
					} else {
						$this->Session->write('errors_item_variation', $this->ItemVariation->invalidFields());
						$this->redirect('details/create/');
					}
					
						
					$this->redirect('summary/' . $item_id);

				
				} else {
					$this->Session->write('errors_item', $this->Item->invalidFields());
					$this->Session->write('errors_item_variation', $this->ItemVariation->invalidFields());
					$this->redirect('details/create/');
					//$this->set('data', $this->data);
				}
						
			}
		}
		
		function admin_email_item() {
		
			$this->layout = "admin_product_management";
		
			if($this->Session->check('email_result')) {
				$this->set('email_result', $this->Session->read('email_result'));
				$this->Session->delete('email_result');
			} 
			
		
			// take the data from the form and send it in an email 
			
			if($this->data) {
				//$this->set('data', $this->data['Item']['details']);
			
				$this->loadModel('EmailMessage');
				$this->EmailMessage->set($this->data['EmailMessage']);
			
				if($this->EmailMessage->validates()) {
			
					$admin_email = "archive@luccaantiques.com";
					
					if($this->__send_item_emails($this->data, $admin_email, $this->data['EmailMessage']['subject']) && $this->__send_item_emails($this->data, $this->data['EmailMessage']['address'], $this->data['EmailMessage']['subject'])) {
						
						$this->Session->write('email_result', array('Success. Message sent.'));
						$this->redirect('email/' . $this->params['pass'][0] .'/'); 
						
					} else {
						$this->Session->write('email_result', $this->Session->read('smtp_errors'));
						$this->redirect('email/' . $this->params['pass'][0] . '/'); 
					
					}
				} else {
					$this->Session->write('email_item_errors', $this->EmailMessage->invalidFields());
					$this->Session->write('temp_email_item', $this->data);
					$this->redirect('email/' . $this->params['pass'][0] . '/'); 
				}
			
			}
			
		
		}
		
		function admin_delete() {
		
			//$this->layout = 'admin_orders_management';
			
			$item_id = $this->params['pass'][0];
			$item_type_id = $this->params['pass'][1];
			$status =  $this->params['pass'][2];
			
			// need filenames to unlink
			$image_filenames = $this->Item->ItemImage->find('list', array(
				'conditions' => array(
					'ItemImage.item_id' => $item_id
				),
				'fields' => array(
					'filename'
				)
			));
			
			foreach($image_filenames as $i) {
				if($i !== '') {
					$path = WWW_ROOT . '/files/' . $i;
					unlink($path);
				}
			}
			// need to delete from itemImage too
			
			// .....and the item variations...am i setting the model associations up right???
			
			if($this->Item->delete($item_id) && $this->Item->ItemImage->deleteAll(
					array(
						'ItemImage.item_id' => $item_id 
					)
			) && $this->Item->ItemVariation->deleteAll(
					array(
						'ItemVariation.item_id' => $item_id 
					)
			)) {
				// maybe redirect to the orginal url?
				$this->redirect('grid/'. $item_type_id .'/' . $status);
			} else {
				$this->redirect('error/');
			}
			
		
		}
		
		function admin_delete_image(){
			// deletes an image. redirects to edit images
			$item_id = $this->params['pass'][0];
			$image_id = $this->params['pass'][1];
			$filename = $this->params['pass'][2];
			
			$this->loadModel('ItemImage');

			if($this->ItemImage->updateAll(
				array('ItemImage.filename' => "'". '' . "'"),
				array('ItemImage.id' => $image_id)
			
				)
			) {
				// dont forget to delete the images
				unlink(WWW_ROOT . '/files/'. $filename);
				$this->redirect('image/edit/' . $item_id);
			} else {
				$this->redirect('error/');
			}
		}
		
		function admin_upload_images() {
		
			$this->loadModel('ItemImage');
			
			//$this->layout = 'admin_product_management';
			
			$item_id = $this->params['pass'][0];
			
			if($this->data) {

				
				foreach($this->data['ItemImage'] as $image) {
				
					$random_numbers = substr(md5(uniqid()), 0, 10);

					if($image['filename']['error'] == 0) {
					
						// delete file being replaced
						
						$filename[] = $this->ItemImage->find('first', array(
								'fields' => array('filename'),
								'conditions' => array(
									'ItemImage.id' => $image['id']
								)
						));
						
						$this->set('filename', $filename);
						
						unlink(WWW_ROOT . '/files/'. $filename[0]['ItemImage']['filename']);	
						
						// update filenames
						$basename = basename($image['filename']['name']);	
						$file_ext = substr($basename, strrpos($basename, ".") + 1);
						$new_filename = $random_numbers . '.' . $file_ext;
						
						if($this->ItemImage->updateAll(
								array(
									'ItemImage.filename' => "'" . $new_filename . "'",
									'ItemImage.primary' => $image['primary'],
								),
								array('ItemImage.id' => $image['id'])
						)) {
						
							
							$uploaded = $this->JqImgcrop->uploadImage($image['filename'], 'files');
							
							$path = WWW_ROOT . '/files/';
							rename($path . $basename, $path . $new_filename);
					

						} else {

							$this->Session->write('errors_images', $this->ItemImage->invalidFields());
							$this->redirect('image/edit/'. $item_id);
						}
						
					
					}
					
				}
				
				$this->redirect('image/edit/'. $item_id);

				
			}
			
			
		
		}
		
		function __send_item_emails($data, $email, $subject) {

			$item_details = $this->Item->find('first', array(
				'conditions' => array(
					'Item.id' => $this->params['pass'][0]
				)
			));

			$this->set('item_details', $item_details);
			
			/*
			if(isset($data['Item']['details'])) {
				foreach($data['Item']['details'] as $d) {
					if($d !== 'id') {
						$fields[] = $d;
					}
				}
				
				$item_details = $this->Item->find('first', array(
							'fields' => $fields,
							'conditions' => array(
									'Item.id' => $this->params['pass'][0]
							),
				));

				$this->set('item_details', $item_details);
			}
			*/
				
				if(isset($data['Item']['images'])) {
					$this->set('images', $data['Item']['images']);
				}
				
				$this->Email->charset = 'iso-8859-15';
		
				$this->Email->template = 'email_item';
				$this->Email->sendAs = 'html'; 
				
				/*
				$this->Email->smtpOptions = array(
				'port' => '25',
				'timeout' => '30',
				'host' => 'localhost',
				'username' => 'anne@luccaantiques.com',
				'password' => 'queenanne1'); 
				
				$this->Email->smtpOptions = array(
					'port' => '465',
					'timeout' => '30',
					'host' => 'ssl://smtp.gmail.com',
					'username' => 'info@@luccaantiques.com.test-google-a.com',
					'password' => 'lucca1908'); 
				
				$this->Email->delivery = 'smtp';
				*/
				$this->Email->to = '<'. $email .'>';
				$this->Email->subject = $subject;
				$this->Email->replyTo = $item_details['InventoryLocation']['email'];  // 'no-reply@luccantiques.com';
				$this->Email->from = 'Lucca Antiques<'.$item_details['InventoryLocation']['email'].'>';  
		

				if(isset($data['EmailMessage']['message'])) {
					$email_body = $data['EmailMessage']['message'];
					$this->set('email_body', $email_body);
				}
				
				
				if($data['EmailMessage']['asking_price'] !== '') {
					$asking_price = $data['EmailMessage']['asking_price'];
					$this->set('asking_price', $asking_price);
				}
				
				
				if(isset($item_details)) {
					$this->set('item_details', $item_details);
				}
				
				
				if($this->Email->send()) {
					return true;
				} else {
					echo $this->Email->smtpError();
					return false;
					$this->Session->write('smtp_errors', $this->Email->smtpError());

				} 
		
		}
		
	}
	
?>