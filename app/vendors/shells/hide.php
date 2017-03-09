<?php

class HideShell extends Shell {
	var $uses = array('Item');

	function daily() {
	
		// get all of the items with an item_category_id of 1 that have a status of sold. 
		$sold_items = $this->Item->find('all', array(
				'fields' => array('Item.id', 'Item.sold_date'),
				'conditions' => array(
					'Item.item_category_id' => array('1','3'),
					'Item.status' => 'Sold'
				)
		));
		
		$current_date = date('Ymd');
		
		foreach($sold_items as $s) {
		
			$sold_date = preg_replace('/-/', '', $s['Item']['sold_date']);
		
			$number_of_days_sold = ($sold_date - $current_date);
			$number_of_days_sold = preg_replace('/-/', '', $number_of_days_sold);
			
			if($number_of_days_sold > 10) {
				
				$this->Item->id = $s['Item']['id'];
				$this->Item->set('status', 'Hidden');
				
				if($this->Item->save()) {
					print('Item status for Item #'.  $s['Item']['id'].' changed');
				} else {
					print('Error. Sorry. Not sure what happened');
				}
				
			} else {
				print('No antiques found sold for more than 10 days   '. $number_of_days_sold);
			}
		}
	
	}
}
