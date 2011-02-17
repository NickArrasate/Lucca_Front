<?php

	class AddonController extends AppController {

		var $name = 'Addon';
		
		var $helpers = array('Ajax','Html');
		var $components = array('Session', 'Email');
		
		function admin_categories(){
		
			$admin_subnavigation = array(
					array(
						'link' => '/admin/item/grid/all/Unpublished/',
						'class' => '',
						'title' => 'New / Works in Progress',
					),
					array(
						'link' => '/admin/item/grid/all/Available/',
						'class' => '',
						'title' => 'Online Inventory',
					),
					array(
						'link' => '/admin/addon/categories/edit/',
						'class' => 'active',
						'title' => 'Manage Addons',
					),
					array(
						'link' => '/admin/autofill/edit',
						'class' => '',
						'title' => 'Autofill Text',
					)
			);
			$this->Session->write('admin_subnavigation',$admin_subnavigation);
			// displays the main page of addons
			$this->layout = 'admin_product_management';
			$action = $this->params['pass'][0];
			
			$addons = $this->Addon->find('all');

			switch($action) {
				case 'edit':
					if($this->Session->check('errors_addon_category')) {
						$this->set('errors_addon_category', $this->Session->read('errors_addon_category'));
						$this->Session->delete('errors_addon_category');
					}
				break;
				
				case 'add':
					if($this->data) {
						if($this->Addon->save($this->data)){
							$this->redirect('categories/edit/');
						} else {
							$this->Session->write('errors_addon_category', $this->Addon->invalidFields());
							$this->redirect('categories/edit/');
						}
					}
				break;
				
				case 'delete':
					$addon_id = $this->params['pass'][1];
					// hmm. does it delete the associated options too?
					$this->Addon->delete($id = $addon_id);
					$this->loadModel('Option');
					$this->Option->deleteAll(
						array('Option.addon_id' => $addon_id)
					);
					$this->redirect('categories/edit/');
				break;
				
				case 'save':
					$count = count($this->data['Addon']['name']);
					for ($i=0; $i< $count; $i++) {
						$this->Addon->id = $this->data['Addon']['id'][$i];
						$this->Addon->set('name', $this->data['Addon']['name'][$i]);
						$this->Addon->save();
					}
					$this->redirect('categories/edit/');
				break;

			}
			
			for($i=0; $i < count($addons); $i++) {
				$options = $this->Addon->Option->find('count', array(
						'conditions' => array(
							'Option.addon_id' => $addons[$i]['Addon']['id']
						)
				));
				$addons[$i]['Addon']['option_count'] = $options;
			}

			
			$this->set('addons', $addons);
			if(isset($options)) {
				$this->set('options', $options);
			}
		}
		
	function admin_options(){
	
		$admin_subnavigation = array(
					array(
						'link' => '/admin/item/grid/all/Unpublished/',
						'class' => '',
						'title' => 'New / Works in Progress',
					),
					array(
						'link' => '/admin/item/grid/all/Available/',
						'class' => '',
						'title' => 'Online Inventory',
					),
					array(
						'link' => '/admin/addon/categories/edit/',
						'class' => 'active',
						'title' => 'Manage Addons',
					),
					array(
						'link' => '/admin/autofill/edit',
						'class' => '',
						'title' => 'Autofill Text',
					)
			);
			
		$this->Session->write('admin_subnavigation',$admin_subnavigation);
		
		$this->loadModel('Option');
		
		$action = $this->params['pass'][0];
		
		//$option_sku = substr(md5(uniqid()), 0, 6);
		
		$option_sku = mt_rand(100000, 999999);
				
		if($this->Option->validates()) {
			$this->Option->set('option_sku', $option_sku);
		} else {
			$option_sku = mt_rand(100000, 999999);
		}
			
		
		switch($action) {
			case 'edit':
				$addon_id = $this->params['pass'][1];
				if($this->Session->check('errors_addon_option')) {
					$this->set('errors_addon_option', $this->Session->read('errors_addon_option'));
					$this->Session->delete('errors_addon_option');
				}

			break;
			
			case 'add':
				$addon_id = $this->params['pass'][1];
				if($this->data) {
					if($this->Option->save($this->data)) {
						$this->redirect('options/edit/' . $addon_id);
					} else {
						$this->Session->write('errors_addon_option', $this->Option->invalidFields());
						$this->redirect('options/edit/' . $addon_id);
					}
				}
				
			break;
			
			case 'delete':
				$option_id = $this->params['pass'][1];
				$addon_id = $this->params['pass'][2];
				$this->Option->delete($id = $option_id);
				$this->redirect('options/edit/' . $addon_id);
			break;
			
			case 'save':
				$options = $this->data;
				$option_id = $this->params['pass'][1];
				$count = count($this->data['Option']['name']);
				for ($i=0; $i< $count; $i++) {
					$this->Option->id = $this->data['Option']['id'][$i];
					$this->Option->set('name', $this->data['Option']['name'][$i]);
					$this->Option->set('price', $this->data['Option']['price'][$i]);
					$this->Option->save();
				}
				$this->redirect('options/edit/' . $option_id);
			break;
			
		}
		
		
		$addon_name = $this->Addon->find('first', array(
				'conditions' => array(
					'Addon.id' => $addon_id
				)
		));
		$options = $this->Addon->Option->find('all', array(
			'conditions' => array(
				'Option.addon_id' => $addon_id
			)
		));
		
		$this->layout = 'admin_product_management';
		
		$this->set('h3', 'Edit Options : ' . $addon_name['Addon']['name']);
		$this->set('options', $options);
		$this->set('addon_name', $addon_name);
		$this->set('addon_id', $addon_id);
		$this->set('option_sku', $option_sku);
		
	}
	
}

