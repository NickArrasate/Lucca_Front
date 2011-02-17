<?php

class Order extends AppModel {

    var $name = 'Order';
	var $hasMany = 'OrderedItem';
	var $belongsTo = array('Person', 'Creditcard');
	
	var $cacheQueries = true;
	
	var $validate = array(
	);
	
	var $navigation_menu = array(
		array(
			'title' => 'Inventory Check',
			'class' => '',
			'link' => '/admin/orders/process/inventory_check/'
		),
		array(
			'title' => 'Payment and Shipping',
			'class' => '',
			'link' => '/admin/orders/process/payment_and_shipping/'
		),
		array(
			'title' => 'Shipped',
			'class' => '',
			'link' => '/admin/orders/process/shipped/'
		),
		array(
			'title' => 'Returned',
			'class' => '',
			'link' => '/admin/orders/process/returned/'
		)
	);
	
	function get_page($status) {
	
		switch($status) {
			case 'inventory_check':
				$status = 'Inventory Check';
				//combine breadcrumbs for now
				$breadcrumbs = array(
					array(
						'title' => 'Order',
						'link' => '/admin/order/inventory_check'
					),
					array(
						'title' => $status,
						'link' => '/admin/order/inventory_check'
					),
				);
				break;
			case 'payment_and_shipping':
				$status = 'Payment and Shipping';
				$breadcrumbs = array(
					array(
						'title' => 'Order',
						'link' => '/admin/order/inventory_check'
					),
					array(
						'title' => $status,
						'link' => '/admin/order/payment_and_shipping'
					),
				);
				break;
			case 'shipped':
				$status = 'Shipped';
				$breadcrumbs = array(
					array(
						'title' => 'Order',
						'link' => '/admin/order/inventory_check'
					),
					array(
						'title' => $status,
						'link' => '/admin/order/shipped'
					),
				);
				break;
			case 'returned':
				$status = 'Returned';
				$breadcrumbs = array(
					array(
						'title' => 'Order',
						'link' => '/admin/order/inventory_check'
					),
					array(
						'title' => $status,
						'link' => '/admin/order/returned'
					),
				);
				break;
		}
		/*
		$records = $this->find('all', array(
			'conditions' => array(
				'Order.status' => $status
			)
		));
		
		return $page = array(
			'records' => $records,
			'breadcrumbs' => $breadcrumbs
		);
		*/
		return $page = array(
			'status' => $status,
			'breadcrumbs' => $breadcrumbs
		);
	}
	
	function format_date($date) {
		return $formatted_date;
	}
	
	function order_amount($order_id) {
		return $order_amount;
	}
	
	function calculate_order_totals($ordered_items, $person, $order = array()) {
	
		$tax_rate = .09750;
		$tax_percentage = round($tax_rate, 2);
		
		// the order array exists when calculating discounts from the admin panel
		if (isset($order['discount'])) {
			$discount = $order['discount'];
		} else {
			if($person['trade_professional'] == 1) {
				$discount = 0.15;
			} else {
				$discount = 0;
			}
		}
		
		$ordered_items_subtotals = array();
		
		foreach($ordered_items as $o) {
			
			
			/*
			if(isset($o['Option']['price']) && isset($o['addon'])) {
				$ordered_items_subtotals[] = $o['Option']['price'] * $o['Option']['quantity'];
			}
			*/
			// 148 - 154 isiffy. issue between ordered items being calculated when ordered vs when being viewed from the admin panel. - and messy array formation
			
			if(isset($o['option_price'])) {
				// for the admin order detail pages
				$ordered_items_subtotals[] = $o['option_price'] * $o['option_quantity'];
				
			} elseif(isset($o['addon']['price']) &&!isset($o['Option'])) {
				// for theshopping cart and place order pages
				$ordered_items_subtotals[] = $o['addon']['price'] * $o['addon']['quantity'];
				
			} else{
				$ordered_items_subtotals[] = 0;
			}
		
			$ordered_items_subtotals[] = $o['quantity'] * $o['price'];
		}
		
		$ordered_items_subtotal = array_sum($ordered_items_subtotals);
		
		// remember to number format the results.
		//if(strtolower($person['state']) == 'ca' ){
			$sales_tax_amount = $tax_rate * $ordered_items_subtotal;
			//$sales_tax_amount = number_format($sales_tax_amount, 2);
		//} else {
		//	$sales_tax_amount = 0;
		//}
		
		// apply trade discount after tax
		$trade_professional_discount = ($sales_tax_amount + $ordered_items_subtotal) * $discount;
		
		$total = ($sales_tax_amount + $ordered_items_subtotal) *(1 - $discount);
		
		$total = round($total, 2);
		
		//$total = number_format($total, 2);
		//$ordered_items_subtotal = number_format($ordered_items_subtotal, 2);
		//$trade_professional_discount = number_format($trade_professional_discount, 2);
		
		
		$totals = array(
			'shipping_cost' => '0',
			'subtotal' => $ordered_items_subtotal,
			'sales_tax_amount' => $sales_tax_amount,
			'total' => $total,
			'trade_professional_discount' => $trade_professional_discount,
			'trade_professional_percentage' => $discount * 100
		);
		
		return $totals;
	}
	
	function create() {
		
		// create an array to store into a session variable with just enough field keys to calculate shopping cart totals, and later to update during the checkout process.
		$order = array(
			//'date' => date('Y-m-d h:i:s A'),
			//'id' => '',
			'Person' => array(
				// 'id => '', 
				'trade_professional' => '',
				'state' => '',
				'zipcode' => '',
			),
			'OrderedItem' => array(
				// fill this array up only when creating an item
				/*
				array(
					'item_variation_id' => '',
					'quantity' => '',
					'price' => ''
					'addon_id' => '',
				),
				*/
			),
			'Creditcard' => array(
				'person_id' => ''
			)
			
		);
		
		return $order;
	}

	function duplicate_check($ordered_items, $requested_item_variation_id) {
		$count_ordered_items = count($ordered_items);
		$test = false;
		if ($count_ordered_items > 0) {
			for($i=0; $i < $count_ordered_items; $i++ ) {
			
				if($ordered_items[$i]['item_variation_id'] == $requested_item_variation_id) {
					$test = true;
				}
			}
		}
		return $test;
	}
	
	function order_errors() {
		// store any errors from inventory or duplicate checks here.
	}
	
	function change_status($order_id, $status) {
		
		switch($status) {
			
			case 'inventory_check':
				$new_status = 'Payment and Shipping';
			break;
			case 'payment_and_shipping':
				$new_status = 'Shipped';
			break;
			case 'shipped':
				$new_status = 'Returned';
			break;
		}
	
		$this->id = $order_id;
		$this->set('status', $new_status);
		
		if ($this->save()) {
			return true;
		} else {
			return false;
		}
		
	}
}

