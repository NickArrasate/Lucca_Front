<?php


	class AutofillController extends AppController {

		var $name = 'Autofill';
		
		var $helpers = array('Ajax','Html');
		var $components = array('Session', 'Email');
		
		function admin_edit() {
		
			$this->layout = 'admin_product_management';
			
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
						'class' => '',
						'title' => 'Manage Addons',
					),
					array(
						'link' => '/admin/autofill/edit',
						'class' => 'active',
						'title' => 'Autofill Text',
					)
				);
			$this->Session->write('admin_subnavigation',$admin_subnavigation);
			
			$autofills = $this->Autofill->find('all');
			
			if($this->Session->check('autofill_feedback_message')) {
				$this->set('autofill_feedback_message', $this->Session->read('autofill_feedback_message'));
				$this->Session->del('autofill_feedback_message');
			}
			
			$this->set('autofills', $autofills);
		
		}
		
		function admin_save() {
		
			//$this->layout = 'admin_product_management';
			
			if($this->data) {
				//$this->set('data', $this->data);
				
				for($i=0; $i < count($this->data['Autofill']['content']); $i++) {
					$this->Autofill->set('content',$this->data['Autofill']['content'][$i]);
					if($this->Autofill->validates()) {
						$this->Autofill->updateAll(
						array(
							'Autofill.content' => "'" .$this->data['Autofill']['content'][$i] . "'"
						),
						array('Autofill.id' => $this->data['Autofill']['id'][$i]));
					} else {
						$this->redirect('/admin/autofill/edit');
						$this->Session->set('autofill_feedback_message', $this->Autofill->invalidFields());
					}
						
				}
				$this->Session->write('autofill_feedback_message', array('Autofills Saved'));
				$this->redirect('/admin/autofill/edit');
				
				
				
			}
		}
		
	}
		
?>