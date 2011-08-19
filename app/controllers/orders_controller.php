<?php

	class OrdersController extends AppController {

		var $name = 'Order';

		var $helpers = array('Ajax','Html','Form','Paginator', 'Fieldformatting', 'Resizeimage');
		var $components = array('Session', 'Email', 'Crypter', 'RequestHandler', 'Ssl');
		//var $components = array('Security', 'Session', 'Email', 'Crypter', 'RequestHandler');
		//var $components = array('Session', 'Email', 'Crypter', 'RequestHandler');

		var $paginate = array(
			'Order' => array(
				'fields' => array('Order.person_id', 'Order.id', 'Order.date', 'Order.status', 'Order.shipping_type', 'Order.discount'),
				'limit' => 14,
				'order' => array(
					'Order.date' => 'desc'
				)
			)
		);


		function beforeFilter() {

			// needed or else admin pages can be viewed by anyone.
			parent::beforeFilter();

			$session_details = $this->Session->read();
			$user_agent = $session_details['Config']['userAgent'];

			if($this->Session->check('user_agent')) {
				if($user_agent !== $this->Session->read('user_agent')) {
					$this->Session->write('user_agent', $user_agent);
					$this->redirect('empty_cart/');
				}
			}  else {
				$this->Session->write('user_agent', $user_agent);
				$this->Session->write('cart_count', 0);
			}


			#$this->Security->blackHoleCallback = 'forceSSL';
			#$this->Security->requireSecure();
			#$this->Security->validatePost = false;



		}

		function forceSSL() {
			$this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}


		function empty_cart() {
			//$this->set('order', $this->Session->read('order'));
		}

		function test() {
			$this->loadModel('Option');
			$option_details = $this->Option->get_details(7);
			$this->set('test', $option_details);
		}

		function view() {

			$this->Ssl->force();

			if($this->Session->check('order_error_messages')) {
				$this->set('order_error_messages', $this->Session->read('order_error_messages'));
				$this->Session->delete('order_error_messages');
			}

			if($this->Session->check('order')) {

				$order = $this->Session->read('order');
				$ordered_items_count = count($order['OrderedItem']);

				if ($ordered_items_count == 0) {
					$this->redirect('empty_cart/');
				}

				$this->loadModel('Item');

				if($ordered_items_count > 0) {

					$ordered_items = $this->__shopping_cart_details($order['OrderedItem']);

				}

				if(isset($order['OrderedItem']['addon']) && $order['OrderedItem']['addon']['option_id'] !== '') {
					$this->loadModel('Option');
					$option_details = $this->Option->get_details($order['OrderedItem']['addon']['option_id']);

					$ordered_items = array_merge($ordered_items, $order['OrderedItem']['addon']);
				}

				$shopping_cart = $this->Order->calculate_order_totals($order['OrderedItem'], $order['Person']);

				$this->set('order', $order);
				$this->set('shopping_cart', $shopping_cart);
				$this->set('ordered_items', $ordered_items);
				$this->set('subtotal',  $shopping_cart['subtotal']);
				$this->set('sales_tax_amount', $shopping_cart['sales_tax_amount']);
				$this->set('total',  $shopping_cart['total']);
				$this->set('trade_professional_discount',  $shopping_cart['trade_professional_discount']);

			} else {
				$this->redirect('empty_cart/');
			}
		}

		function add_item() {

			//$this->layout = "admin_o";
			$this->set('order', $this->Session->read('order'));

			if($this->data) {

				$this->Session->write('data', $this->data);

				// check to see if an order session array exists. if not, create one.
				if(!($this->Session->check('order'))) {
					$order = $this->Order->create();
				} else {
					$order = $this->Session->read('order');
				}

				//$this->set('order', $this->Session->read('order'));
				//$this->set('order', $order);

				if(isset($this->data['OrderedItem']['addon']) && $this->data['OrderedItem']['addon']['option_id'] !== '') {
					//update the cart count
					$current_cart_item_count = $this->Session->read('cart_count');
					$updated_cart_item_count = $current_cart_item_count + 1;
					$this->Session->write('cart_count', $updated_cart_item_count);

				}

				//$this->set('option', $option);

				$o = $this->data['OrderedItem'];
				// ordered items can include addon ids, but are mostly item variation ids, and requested quantities.
				// do the necessary checking - based on methods for this model. the checking should return the price of the item from the database, and if there are enough quantities left to sell.
				// duplicate check - if it returns true - doesnt return anything. if false - return the item variation information that was the duplicate.
				if($this->Order->duplicate_check($order['OrderedItem'], $o['item_variation_id']) == false) {
				// inventory check should return true or false.if false - it should return the item variation id information that caused the error within an array. if true - return an array that includes the price from the database in addition to the item variation id and quantity
					if($this->__inventory_check($o['quantity'], $o['item_variation_id']) == true) {
						// if inventory check returns true -- update the session array order with the item. + the price
						$this->loadModel('ItemVariation');
						$this->ItemVariation->id = $o['item_variation_id'];
						$o_price = $this->ItemVariation->field('price');


						if(isset($this->data['OrderedItem']['addon']) && $this->data['OrderedItem']['addon']['option_id'] !== '') {
							$this->loadModel('Option');
							$option_details = $this->Option->get_details($this->data['OrderedItem']['addon']['option_id']);

							$order['OrderedItem'][] = array (
								'price' => $o_price,
								'quantity' => $o['quantity'],
								'item_variation_id' => $o['item_variation_id'],
								'id' => $o['id'],
								'addon' => array(
									'option_id' => $o['addon']['option_id'],
									'price' => $option_details['price'],
									'quantity' => 1,
									'name' => $option_details['name'],
									'sku' => $option_details['sku']
								)
							);

						} else {
							$order['OrderedItem'][] = array (
									'price' => $o_price,
									'quantity' => $o['quantity'],
									'item_variation_id' => $o['item_variation_id'],
									'id' => $o['id']
								);
						}
						$cart_count_quantities = $o['quantity'];
						// then rewrite the session array
						$this->Session->write('order', $order);

						//update the cart count
						$current_cart_item_count = $this->Session->read('cart_count');
						$updated_cart_item_count = $current_cart_item_count + $cart_count_quantities;
						$this->Session->write('cart_count', $updated_cart_item_count);
					} else {
						// set any errors.
						$this->Session->write('order_error_messages', array('The quantity you requested exceeds the quantity available'));
					}
				} else {
					$this->Session->write('order_error_messages', array('The item you tried to add is already in your cart'));
				}



				$this->redirect('/orders/view/');
			}


			//$this->redirect('/orders/view/');

		}

		function update_view() {

			if($this->data) {
				// update quantities in the ordered item arrays.
				$order = $this->Session->read('order');

				$current_cart_item_count = 0;

				for($i=0; $i < count($this->data['OrderedItem']['quantity']); $i++) {
					$new_quantity = $this->data['OrderedItem']['quantity'][$i];
					// validate the new quantity. it cant for example - be a decimal number
					$this->loadModel('ShoppingCart');
					$this->ShoppingCart->create();
					$this->ShoppingCart->set('quantity', $new_quantity);

					if($this->ShoppingCart->validates()) {

						$item_variation_id = $this->data['OrderedItem']['item_variation_id'][$i];
						// assuming the array in the session is the same size as the array on the view page, and inthe same order
						// DO AN INVENTORY CHECK FOR EACH NEW REQUESTED QUANTITY
						if($this->__inventory_check($new_quantity, $item_variation_id )) {
							$order['OrderedItem'][$i]['quantity'] = $new_quantity;
							$quantities[] = $new_quantity;

							if(isset($this->data['OrderedItem']['addon']['quantity'])) {
								$new_addon_quantity = $this->data['OrderedItem']['addon']['quantity'][0];
								$order['OrderedItem'][$i]['addon']['quantity'] = $this->data['OrderedItem']['addon']['quantity'][0];
								$quantities[] = $new_addon_quantity;
							}

							$this->Session->write('order', $order);
							$this->Session->write('cart_count', array_sum($quantities));

						} else {
							$this->Session->write('order_error_messages', array('The quantity you requested exceeds the quantity available'));
							$this->redirect('view/');
						}
					} else {
						$this->Session->write('order_error_messages', $this->ShoppingCart->invalidFields());
					}
				}

				//$this->set();
				$this->redirect('view/');

			} else {
				$this->redirect('error/');
			}

		}

		function delete_item() {

			$key = $this->params['pass'][0];
			$quantity = $this->params['pass'][1];


			$order = $this->Session->read('order');
			$ordered_items = $order['OrderedItem'];
			$current_cart_item_count = $this->Session->read('cart_count');

			if(isset($order['OrderedItem'][$key]['addon'])) {
				$current_cart_item_count = $current_cart_item_count - 1;
			}

			unset($ordered_items[$key]);

			// reset the array keys
			$updated_items = array_values($ordered_items);
			$order['OrderedItem'] = $updated_items;
			$this->Session->write('order', $order);


			$current_cart_item_count = $current_cart_item_count  - $quantity;
			$this->Session->write('cart_count', $current_cart_item_count);

			$this->redirect('view/');

		}

		function delete_option() {

			$key = $this->params['pass'][0];
			$quantity = $this->params['pass'][1];


			$order = $this->Session->read('order');
			$ordered_items = $order['OrderedItem'];
			unset($ordered_items[$key]['addon']);

			// reset the array keys
			$updated_items = array_values($ordered_items);
			$order['OrderedItem'] = $updated_items;

			$this->Session->write('order', $order);

			$current_cart_item_count = $this->Session->read('cart_count');
			$current_cart_item_count = $current_cart_item_count  - $quantity;
			$this->Session->write('cart_count', $current_cart_item_count);

			$this->redirect('view/');

		}

		/***************************************************************************************
		functions associated with checking out
		***************************************************************************************/

		function billing() {

			$this->Ssl->force();

			//  check to see if there are any ordered items the order
			if($this->Session->check('order')) {

				$order = $this->Session->read('order');
				$ordered_items_count = count($order['OrderedItem']);

				if($ordered_items_count > 0) {

					if($this->Session->check('errors')) {
						// fill the array with the values of person
						$this->set('errors',$this->Session->read('errors'));
					}
					$this->Session->delete('errors');

					if(!isset($order['Person']['id'])) {

						$random_person_id = substr(md5(uniqid()), 6);
						$order['Person']['id'] = $random_person_id;

					}

					$this->Session->write('order', $order);

					$order = $this->Session->read('order');
					$this->set('person', $order['Person']);
					$this->set('order', $order);

				} else {
					$this->redirect('empty_cart/');
				}

			} else {
				$this->redirect('empty_cart/');
			}

		}

		function payment() {

			$this->Ssl->force();

			if($this->Session->check('errors')) {
				// fill the array with the values of person
				$this->set('errors',$this->Session->read('errors'));
			}
			$this->Session->delete('errors');

			if($this->Session->check('order')) {

				$this->loadModel('Creditcard');
				$this->set('creditcard', $this->Creditcard->create());

				if($this->data) {

					$order = $this->Session->read('order');

					$person = $this->data['Person'];

					if(!isset($this->data['Person']['trade_professional'])){
						$person['trade_professional'] = '0';
					} else {
						$person['trade_professional'] = '1';
					}


					$this->loadModel('Person');
					$this->Person->set($person);

					if($this->Person->validates()) {

						$order['Creditcard']['person_id'] = $order['Person']['id'];

						$order['Person'] = array_merge($order['Person'], $person);

						$shipping_type = array();


						$shipping_type['shipping_type'] = $this->data['Order']['shipping_type'];


						$order = array_merge($order, $shipping_type );

						$this->Session->write('order', $order);

					} else {

						$order['Creditcard']['person_id'] = $order['Person']['id'];

						$order['Person'] = array_merge($order['Person'], $person);
						$this->Session->write('order', $order);
						$errors = $this->Person->invalidFields();
						$this->Session->write('errors', $errors);


						$this->redirect('billing/');
					}
				}

				$order = $this->Session->read('order');

				$this->Session->write('order', $order);

				$this->set('order', $order);

			} else {
				$this->redirect('billing/');
			}

		}


		function place_order() {

			$this->Ssl->force();

			if($this->Session->check('errors')) {
				// fill the array with the values of person
				$this->set('errors',$this->Session->read('errors'));
			}
			$this->Session->delete('errors');

			if($this->data){

				// encrypting
				$order = $this->Session->read('order');


				$this->loadModel('Creditcard');
				$creditcard = $this->data['Creditcard'];

				$this->Creditcard->set($creditcard);

				if($this->Creditcard->validates()) {

					if(!($this->__expired_creditcard($creditcard['expiration_date_month'], $creditcard['expiration_date_year']))) {

						// one more check
						if($this->__check_cvv2($creditcard['number'], $creditcard['security_code'])) {

							$creditcard['type'] = $this->Crypter->enCrypt($creditcard['type']);
							$creditcard['number'] = $this->Crypter->enCrypt($creditcard['number']);
							$creditcard['security_code'] = $this->Crypter->enCrypt($creditcard['security_code']);

							$order['Creditcard'] = array_merge($order['Creditcard'], $creditcard);
							$this->Session->write('order', $order);
							/***************************************************************/
							$this->set('order', $order);

						} else {

							$order['Creditcard']= array_merge($order['Creditcard'], $creditcard);
							$this->Session->write('order', $order);


							$errors = array('Please check the cvv2 number');
							$this->Session->write('errors', $errors);
							$this->redirect('payment/');

						}

					} else {

						$order['Creditcard'] = array_merge($order['Creditcard'], $creditcard);
						$this->Session->write('order', $order);


						$errors = array('Expired credit card');
						$this->Session->write('errors', $errors);
						$this->redirect('payment/');

					}

				} else {

					$order['Creditcard'] = array_merge($order['Creditcard'], $creditcard);
					$this->Session->write('order', $order);

					$errors = $this->Creditcard->invalidFields();
					$this->Session->write('errors', $errors);
					$this->redirect('payment/');
				}



				$order = $this->Session->read('order');
				$display_creditcard = $order['Creditcard'];


				// still need to test if this is working correctly
				$display_creditcard['type'] = $this->Crypter->deCrypt($display_creditcard['type']);
				$display_creditcard['number'] = $this->Crypter->deCrypt($display_creditcard['number']);
				$display_creditcard['security_code'] = $this->Crypter->deCrypt($display_creditcard['security_code']);

				$display_creditcard['number'] = $this->Crypter->maskCardNumber($display_creditcard['number']);

				// write the display credit card details to a session for emailing
				$this->Session->write('creditcard_masked', $display_creditcard);

				// check that the credit card is not expired.

				$order_summary = $this->Order->calculate_order_totals($order['OrderedItem'], $order['Person']);
				$ordered_items = $this->__shopping_cart_details($order['OrderedItem']);

				$this->set('subtotal',  $order_summary['subtotal']);
				$this->set('sales_tax_amount', $order_summary['sales_tax_amount']);
				$this->set('total',  $order_summary['total']);
				$this->set('trade_professional_discount',  $order_summary['trade_professional_discount']);

				$this->set('creditcard', $display_creditcard);
				$this->set('person', $order['Person']);
				$this->set('ordered_items', $ordered_items);
				$this->set('order', $order);


			} else {
				$this->redirect('payment/');
			}

		}

		function add_order() {

			/***********************
			the function is blah and should be refactored extensively.
			************************/

			// if there isnt data - error out.
			// check to see if the data is saved successully into the database.  read the data from the session variables. appropriate data into the database. return thank you note. send out confirmation emails and notication emails
			if($this->Session->check('order')){

				$order_information = $this->Session->read('order');
				$order_id = mt_rand(100000, mt_getrandmax());

				$order;
				// put together the order array
				$order['Order']['status'] = 'Inventory Check';
				$order['Order']['id'] = $order_id;
				$order['Order']['date'] = date('Y-m-d h:i:s A');
				$order['Order']['person_id'] = $order_information['Person']['id'];
				// hm. not working.
				$order['Order']['shipping_type'] = $order_information['shipping_type'];
				$order['Order']['creditcard_id'] = '';

				// trade discount to save into the db
				if($order_information['Person']['trade_professional'] == 1) {
					$order['Order']['discount'] = 0.15;
				} else {
					$order['Order']['discount'] = 0;
				}

				// put together the ordereditem array for the emails
				for($i=0; $i< count($order_information['OrderedItem']); $i++) {
					$order_information['OrderedItem'][$i]['order_id'] = $order_id;
				}

				$order['OrderedItem'] = $order_information['OrderedItem'];
				/*************************************************************************************************************************************************/
				/*********************** REDO THIS PART ************************************************************************************************/
				$order_save['OrderedItem'] = $order_information['OrderedItem'];

				// the next bit is a little odd - but i need to change the id key of the ordered item array to item_id.or get rid of it since i dont need it for the db table. this way it doesn't get saved to the wrong table column. i guess i could have changed it in the first place onthe details.ctp page.. orwherever it was that i set the key name...
				$this->loadModel('ItemVariation');
				$this->loadModel('Option');
				$this->loadModel('Addon');

				$ordered_item_save_info = $this->__shopping_cart_details($order_save['OrderedItem']);

				for($i=0; $i< count($order_information['OrderedItem']); $i++) {
					// put together the ordereditem array for the database
					/********************************************************* if there is an addon **********************************************/
					if(isset($order_save['OrderedItem'][$i]['addon'])) {
						$order_save['OrderedItem'][$i]['option_id'] = $order_save['OrderedItem'][$i]['addon']['option_id'];
						$order_save['OrderedItem'][$i]['addon'] = $this->Option->get_details($order_save['OrderedItem'][$i]['option_id']);
						$order_save['OrderedItem'][$i]['addon_name'] = $this->Addon->get_name($order_save['OrderedItem'][$i]['addon']['addon_id']);

						// add in an option price index ************************************
						$order_save['OrderedItem'][$i]['option_price'] = $this->Option->get_price($order_save['OrderedItem'][$i]['option_id']);
						$order_save['OrderedItem'][$i]['addon_id'] = $order_save['OrderedItem'][$i]['addon']['addon_id'];
						$order_save['OrderedItem'][$i]['option_name'] = $order['OrderedItem'][$i]['addon']['name'];
						$order_save['OrderedItem'][$i]['option_sku'] = $order_save['OrderedItem'][$i]['addon']['sku'];
						// add an index for a default addon qty of 1 - to be changed later fromthe admin panel if needed.
						/************************************************* TEST ************************************************************/
						$order_save['OrderedItem'][$i]['option_quantity'] = $order['OrderedItem'][$i]['addon']['quantity'];


						unset($order_save['OrderedItem'][$i]['addon']);



					}
					/************************************************end if there is an addon *********************************************************/

					$order_save['OrderedItem'][$i]['item_id'] = $order_save['OrderedItem'][$i]['id'];
					unset($order_save['OrderedItem'][$i]['id']);

					// add in an item_variation_price index **************
					$order_save['OrderedItem'][$i]['item_variation_price'] = $order_save['OrderedItem'][$i]['price'];
					$order_save['OrderedItem'][$i]['item_variation_sku'] = $ordered_item_save_info[$i]['sku'];
					if(isset($order_save['OrderedItem'][$i]['item_variation_name'])) {
						$order_save['OrderedItem'][$i]['item_variation_name'] = $this->ItemVariation->get_name($ordered_item_save_info[$i]['item_variation_id']);
						// for the email
						$order['OrderedItem'][$i]['item_variation_name'] = $order_save['OrderedItem'][$i]['item_variation_name'];
					}

					//$test = $this->ItemVariation->get_name('135');

					$order_save['OrderedItem'][$i]['item_name'] = $ordered_item_save_info[$i]['name'];
					$order_save['OrderedItem'][$i]['item_variation_description'] = $ordered_item_save_info[$i]['description'];
					unset($order_save['OrderedItem'][$i]['price']);


				}


				/***************************************************************************************************************************************************************************/

				// put together the person array and credit card array

				$order['Person'] = $order_information['Person'];
				$order['Creditcard'] =  $order_information['Creditcard'];


				// put together information to be emailed
				$creditcard_masked = $this->Session->read('creditcard_masked');


				$this->loadModel('OrderedItem');
				$this->loadModel('Person');
				$this->loadModel('Creditcard');

				if($this->Creditcard->save($order['Creditcard'], array('validate' => false)) && $this->Person->save($order['Person'], array('validate' => false))) {


					for($o=0; $o < count($order_save['OrderedItem']); $o++) {
						$this->OrderedItem->create();
						$this->OrderedItem->save($order_save['OrderedItem'][$o], array('validate' => false));
					}

					// update the creditcard_id entry before saving the order record.
					$creditcard_id = $this->Creditcard->getLastInsertId();
					$order['Order']['creditcard_id'] = $creditcard_id;

					// maybe do one more inventory check right here.
					$this->Order->save($order['Order']);

					// need to decrease the quantity of each of the item variations ordered. (if any of the items were found, or antique)
					$this->__update_item_inventory_quantities($order['OrderedItem']);

					$email_totals = $this->Order->calculate_order_totals($order['OrderedItem'], $order['Person']);
					$ordered_items = $this->__shopping_cart_details($order['OrderedItem']);

					$this->__send_notification($order['Person'], $order['Order'], $ordered_items, $email_totals['subtotal'], $email_totals['total'], $email_totals['trade_professional_discount'], $email_totals['sales_tax_amount'], $creditcard_masked);

					$this->__send_receipt($order['Person'], $order['Order'], $ordered_items, $email_totals['subtotal'], $email_totals['total'], $email_totals['trade_professional_discount'], $email_totals['sales_tax_amount'], $creditcard_masked);
					$this->Session->write('order', $order);

					//then redirect

					$this->set('order_save', $order_save);
					//$this->set('test', $test);
					//$this->set('order', $order);
					//$this->set('ordered_item_save_info', $ordered_item_save_info);

					$this->redirect('thank_you/');


				} else {
					$this->redirect('error/');
				}






			} else {
				$this->redirect('error/');
			}


		}

		function thank_you() {

			$this->Ssl->force();

			// read thesession variables one more time to display the order id and email address  on the thank you page. notify that receipts has been sent to the email address -
			// then destroyt the session
			if($this->Session->check('order')){
				$order = $this->Session->read('order');

				$person = $order['Person'];
				$this->set('order_id', $order['Order']['id']);
				$this->set('email', $person['email']);

				$this->Session->delete('user_agent');
				$this->Session->delete('order');
				$this->Session->delete('cart_count');
				$this->Session->delete('creditcard_masked');

			} else {
				$this->redirect('error/');
			}

		}

		function __update_item_inventory_quantities($ordered_items) {

			$this->loadModel('ItemVariation');
			$this->loadModel('Item');

			foreach($ordered_items as $o) {

				// i need to check to see if any of these items are found or antiques. then i need to double check to see up the updated quantity is == 0. if it is - then the status changes to sold or sold out.
					$item_variation = $this->ItemVariation->find('all', array(
							'conditions' => array(
								'ItemVariation.id' => $o['item_variation_id']
							),
							'fields' => array('ItemVariation.quantity', 'ItemVariation.item_id')
					));

					$this->Item->id = $item_variation[0]['ItemVariation']['item_id'];

					$item_category_id = $this->Item->field('item_category_id');

					switch($item_category_id) {

						case '1':
						// antiques
							$quantity = $item_variation[0]['ItemVariation']['quantity'];
							$updated_quantity = $quantity - $o['quantity'];

							$this->ItemVariation->create();
							$this->ItemVariation->id = $o['item_variation_id'];
							$this->ItemVariation->set('quantity', $updated_quantity);
							$this->ItemVariation->save();

							$updated_quantity = (int)$updated_quantity;

							if($updated_quantity == 0) {
								$this->Item->create();
								$this->Item->id = $item_variation[0]['ItemVariation']['item_id'];
								$this->Item->set('status', 'Sold');
								$this->Item->set('sold_date', date('Y-m-d h:i:s A'));
								$this->Item->save();
							}

						break;

						case '3':
						// found
							$quantity = $item_variation[0]['ItemVariation']['quantity'];
							$updated_quantity = $quantity - $o['quantity'];

							$this->ItemVariation->create();
							$this->ItemVariation->id = $o['item_variation_id'];
							$this->ItemVariation->set('quantity', $updated_quantity);
							$this->ItemVariation->save();

							$updated_quantity = (int)$updated_quantity;

							if($updated_quantity == 0) {
								$this->Item->create();
								$this->Item->id = $item_variation[0]['ItemVariation']['item_id'];
								$this->Item->set('status', 'Sold');
								$this->Item->save();
							}

						break;

						case '2':
						// lucca studio
						break;

					}



			}

		}

		function __send_notification($person, $order, $ordered_items, $subtotal, $total, $trade_professional_discount, $sales_tax_amount, $creditcard) {

			$this->set('person', $person);
			$this->set('order', $order);
			$this->set('ordered_items', $ordered_items);
			$this->set('subtotal', $subtotal);
			$this->set('total', $total);
			$this->set('creditcard', $creditcard);
			$this->set('trade_professional_discount', $trade_professional_discount);
			$this->set('sales_tax_amount', $sales_tax_amount);

			$this->Email->charset = 'iso-8859-15';


			$this->Email->template = 'order_notification';
			$this->Email->sendAs = 'html';


			$this->Email->smtpOptions = array(
				'port' => '465',
				'timeout' => '30',
				'host' => 'ssl://mail.s78390.gridserver.com',
				'username' => 'anne@luccaantiques.com',
				'password' => 'queenanne1');


			$this->Email->delivery = 'smtp';
			$this->Email->from    = 'Lucca Antiques<no-reply@luccaantiques.com>';
			$this->Email->to = '<anne@luccaantiques.com>';
			$this->Email->subject = 'Order #'. $order['id'] .' placed by '. $person['first_name'] .' ' . $person['last_name'].' via LuccaAntiques.com';
			$this->Email->replyTo = 'no-reply@luccantiques.com';


			$this->Email->send();
			$this->Email->reset();



		}

		function __send_receipt($person, $order, $ordered_items, $subtotal, $total, $trade_professional_discount, $sales_tax_amount, $creditcard) {

			$this->set('person', $person);
			$this->set('order', $order);
			$this->set('ordered_items', $ordered_items);
			$this->set('subtotal', $subtotal);
			$this->set('total', $total);
			$this->set('creditcard', $creditcard);
			$this->set('trade_professional_discount', $trade_professional_discount);
			$this->set('sales_tax_amount', $sales_tax_amount);

			$this->Email->charset = 'iso-8859-15';

			$this->Email->smtpOptions = array(
				'port' => '465',
				'timeout' => '30',
				'host' => 'ssl://mail.s78390.gridserver.com',
				'username' => 'anne@luccaantiques.com',
				'password' => 'queenanne1');

			$this->Email->from    = 'Lucca Antiques <no-reply@luccaantiques.com>';
			$this->Email->to      = $person['first_name'] . ' ' . $person['last_name'] .  ' <'. $person['email'] .'>';
			$this->Email->subject = 'A receipt for your order #'. $order['id'] .' with LuccaAntiques.com';
			$this->Email->template = 'order_receipt';
			$this->Email->sendAs = 'html';
			$this->Email->delivery = 'smtp';


			$this->Email->send();
			$this->Email->reset();



		}

		function error() {

		}

		function __inventory_check($requested_quantity, $requested_item_variation_id) {


			// ONLY DO INVENTORY CHECKS FOR FOUND AND ANTIQUES
			$this->loadModel('ItemVariation');
			$this->loadModel('Item');
			// could use read method here
			$requested_item = $this->ItemVariation->find('all', array(
					'fields' => array('ItemVariation.quantity', 'ItemVariation.item_id'),
					'conditions' => array(
						'ItemVariation.id' => $requested_item_variation_id
					)
			));

			$this->Item->id = $requested_item[0]['ItemVariation']['item_id'];

			$item_category_id = $this->Item->field('item_category_id');

			switch($item_category_id) {

				case '1':
				// antiques
					if($requested_quantity <= $requested_item[0]['ItemVariation']['quantity']) {
						return true;
					} else {
						return false;
					}
				break;
				case '3':
				// found
					if($requested_quantity <= $requested_item[0]['ItemVariation']['quantity']) {
						return true;
					} else {
						return false;
					}
				break;
				case '2':
				// lucca studio
					return true;
				break;

			}

		}

		function __shopping_cart_details($a_ordered_items) {

			$this->loadModel('Item');
			$this->loadModel('ItemVariation');
			for($i=0; $i<count($a_ordered_items); $i++) {
				// could try using the read method here.
				$item_information = $this->Item->find('all', array(
					'fields' => array('description', 'name', 'item_category_id'),
					'conditions' => array(
						'Item.id' => $a_ordered_items[$i]['id']
					)
				));
				$item_variation_information = $this->ItemVariation->find('all', array(
					'fields' => array('sku','name'),
					'conditions' => array(
						'ItemVariation.id' => $a_ordered_items[$i]['item_variation_id']
					)
				));
				$a_ordered_items[$i]['name'] = $item_information[0]['Item']['name'];
				$a_ordered_items[$i]['description'] = $item_information[0]['Item']['description'];
				$a_ordered_items[$i]['sku'] = $item_variation_information[0]['ItemVariation']['sku'];
				// only set the name if it is a lucca studio item
				if($item_information[0]['Item']['item_category_id'] == '2') {
					$a_ordered_items[$i]['item_variation_name'] = $item_variation_information[0]['ItemVariation']['name'];
				}
			}

			return $a_ordered_items;
		}

		// this should be in the credit card model but im not sure how exactly
		function __expired_creditcard($creditcard_expiration_month, $creditcard_expiration_year) {

			$current_month = date('m');
			$current_year = date('Y');

			if ($creditcard_expiration_year < $current_year) {
				return true;
			} else {
				// Check if the same year, // if so, make sure month is current or later
				if ($creditcard_expiration_year == $current_year) {
					if ($creditcard_expiration_month < $current_month) {
						return true;
					} else {
						return false;
					}
				}
			}

		}

		function __check_cvv2($cc_number, $cvv2) {

			$result = true;

			$first_number = substr($cc_number, 0, 1);
			// If the first number is a '3 it's an American Express Card
			// And we need to verify the CVV2 number is four digits long
			// otherwise check to see if it is three digits long
			if ($first_number == 3) {
				if (!preg_match("/^\d{4}$/", $cvv2)) {
					$result = false;
				}
			} else {
				if (!preg_match("/^\d{3}$/", $cvv2)) {
					$result = false;
				}
			}

			return $result;
		}
		/*********************************************************************************************
		The functions below are admin functions related to the order model
		*********************************************************************************************/
		function admin_change_status() {

			if($this->data) {
				switch($this->params['pass'][0]) {
					case 'inventory_check':
						$new_page = 'payment_and_shipping';
					break;
					case 'payment_and_shipping':
						$new_page = 'shipped';
					break;
					case 'shipped':
						$new_page = 'returned';
					break;
				}

				foreach ($this->data['Order']['ids'] as $o ) {
					if(!($this->Order->change_status($o, $this->params['pass'][0]))) {
						$this->redirect('error/');
					}
				}
				$this->redirect('/admin/orders/process/' . $new_page);

			} else {
				$this->redirect('error/');
			}

		}

		function admin_process() {

			$this->Ssl->force();
			// will display all of the items in different stages of processing order (inventory check, payment shipping, returned). read the first url parameter to do so
			$this->layout = 'admin_orders_management';

			if($this->Session->check('order_management_feedback_message')) {
				$this->set('order_management_feedback_message', $this->Session->read('order_management_feedback_message'));
				$this->Session->delete('order_management_feedback_message');
			}

			// this is a little better than the menu done for orders, since the menu contents is in the order model file.
			switch($this->params['pass'][0]) {

				case 'inventory_check':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Inventory Check') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
					$this->set('process_page_note', 'Inventory needs to be checked before processing these orders.');

				break;

				case 'payment_and_shipping':

					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Payment and Shipping') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
					$this->set('process_page_note', 'Orders for which inventory has been checked,and need to be 1) charged and 2) shipped.');
				break;

				case 'shipped':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Shipped') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
					$this->set('process_page_note', 'Orders that have been completed and shipped');
				break;

				case 'returned':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Returned') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
					$this->set('process_page_note', 'Orders that have been shipped and returned');
				break;

			}

			$page = $this->Order->get_page($this->params['pass'][0]);

			if (isset($this->params['pass'][1]) && $this->params['pass'][1] == 'all') {
				$records = $this->Order->find('all', array(
					'conditions' => array(
						'Order.status' => $page['status']
						),

					'order' => array(
						'Order.date DESC'
					)
					)
				);
				$this->set('view_all', '');
			} else {
				$records = $this->paginate('Order', array(
						'Order.status' => $page['status']
						)
					);
			}

			/*
			need to  modify the array to
			make the person id into a name
			make the order date into something readable
			get the total amount of the ordered items. (this will depend on the TAX RATE, any TRADE DISCOUNTS, and the QUANTITY ORDERED based on the PRICE of each ITEM)
			*/
			$this->loadModel('Person');
			$this->loadmodel('ItemVariation');
			$this->loadmodel('Option');

			for($r=0; $r <count($records); $r++) {
				$person_info = $this->Person->find('all', array(
						'conditions' => array(
							'Person.id' => $records[$r]['Order']['person_id']
						),
						'fields' => array(
							'Person.trade_professional','Person.state'
						),
					)
				);

				$person = $person_info[0]['Person'];
				for ($k=0; $k <  count($records[$r]['OrderedItem']); $k++) {
					//$records[$r]['OrderedItem'][$k]['price'] = $this->ItemVariation->get_price($records[$r]['OrderedItem'][$k]['item_variation_id']);
					$records[$r]['OrderedItem'][$k]['price']  = $records[$r]['OrderedItem'][$k]['item_variation_price'];
					if(isset($records[$r]['OrderedItem'][$k]['option_id'])) {
						// what if the option is deleted after the item has been ordered?
						//$records[$r]['OrderedItem'][$k]['addon'] = $this->Option->get_details($records[$r]['OrderedItem'][$k]['option_id']);
						//$records[$r]['OrderedItem'][$k]['addon']['price'] = $records[$r]['OrderedItem'][$k]['option_price'];
						//$records[$r]['OrderedItem'][$k]['addon']['quantity'] = $records[$r]['OrderedItem'][$k]['option_quantity'];
						//unset($records[$r]['OrderedItem'][$k]['addon']);
					}

				}
				$records[$r]['Order']['person_name'] = $this->Person->get_customer_name($records[$r]['Order']['person_id']);

				$records[$r]['Order']['totals'] = $this->Order->calculate_order_totals($records[$r]['OrderedItem'],$person, $records[$r]['Order']);

			}

			$this->set('records', $records);
			$this->set('breadcrumbs', $page['breadcrumbs']);
			$this->set('status', $this->params['pass'][0]);

		}

		function admin_update_details() {

			// redirect to the updated detail page with feedback messages.
			if($this->data) {
				$order_id = $this->params['pass'][0];

				$person_id = $this->data['person_id'];
				$cc_id = $this->data['cc_id'];

				//creditcard
				// have to encrypt the data before saving it to the database.
				$this->data['Creditcard']['number'] = $this->Crypter->enCrypt($this->data['Creditcard']['number']);
				$this->data['Creditcard']['type'] = $this->Crypter->enCrypt($this->data['Creditcard']['type']);
				$this->data['Creditcard']['security_code'] = $this->Crypter->enCrypt($this->data['Creditcard']['security_code']);
				$this->data['Order']['discount'] = ($this->data['Order']['discount'] / 100);


				$cc = $this->data['Creditcard'];
				// person
				$person = $this->data['Person'];
				// ordered items
				$ordered_item = $this->data['OrderedItem'];
				// order
				$order = $this->data['Order'];
				$this->loadModel('Person');
				$this->loadModel('OrderedItem');
				$this->loadModel('Creditcard');

				if(!isset($this->data['ShippingInformation']['check'])) {
					// delete any existing records just to be sure.
					$this->Person->query('delete from people where order_id = "'. $order_id .'"');
					$shipping_information = $this->data['ShippingInformation'];
					$this->Person->create();
					$this->Person->set($shipping_information['Person']);
					$this->Person->set('order_id', $order_id);
					$this->Person->save();
				} else {
					// make sure no records exist.
					$this->Person->query('delete from people where order_id = "'. $order_id .'"');
				}
				/*
				$this->set('shipping_information', $shipping_information);
				$this->set('cc', $cc);
				$this->set('person', $person);
				$this->set('ordered_item', $ordered_item);
				$this->set('order', $order);
				*/

				if(!isset($this->data['ShippingInformation']['check'])) {
					// delete any existing records just to be sure.
					$order['shipping_type'] = 'Alternate';
				} else {
					$order['shipping_type'] = $this->data['Order']['shipping_method'];
				}
				$this->Order->id = $order_id;
				$this->Order->set($order);
				$this->Order->save();

				$this->Person->id = $person_id;
				$this->Person->set($person);
				$this->Person->save();

				$this->Creditcard->id = $cc_id;
				$this->Creditcard->set($cc);
				// not going to do validation on this end....would have to validate the creditcard before encrypting
				$this->Creditcard->save($cc, array('validate' => false));

				// if(!($this->Creditcard->save($cc, array('validate' => false)))) {
					// $this->Session->write('order_detail_feedback', $this->Creditcard->invalidFields());
				// }


				for ($i=0; $i < count($this->data['OrderedItem']['id']); $i++) {
					$this->OrderedItem->create();
					$this->OrderedItem->id = $this->data['OrderedItem']['id'][$i];
					$this->OrderedItem->set('quantity', $this->data['OrderedItem']['quantity'][$i]);
					if(isset($this->data['OrderedItem']['option_quantity'][$i])) {
						$this->OrderedItem->set('option_quantity', $this->data['OrderedItem']['option_quantity'][$i]);
					}
					$this->OrderedItem->save();
				}

				$this->Session->write('order_detail_feedback', array('Order details saved'));

				$this->redirect('detail/' . $order_id);




				// check to see if the shipping checkmark box is unchecked.  if it is - create an entry in the people table with that information and with a shipping flag of 1.  ********************************** TO DO

				/******************************
				data[Order][status]
				data[OrderedItem][addon_quantity] // need a new column in the db table well actually - if you can modify the addon - then that means that the calculation method needs to change as well......so remove it for now.
				data[OrderedItem][quantity]
				data[Order][shipping_method]
				data[Order][store_comments]
				data[Order][discount]

				data[Creditcard]
				data[Person]

				(Optional)

				data[People][ShippingInformation] (create an entry in the database table people - and give the record a flag of it being shipping information) modify the controller and view logic to check for this flag first. TO DO
				******************************/


			}

		}

		function admin_detail() {

			$this->Ssl->force();

			$this->layout = 'admin_orders_management';

			if($this->Session->check('order_detail_feedback')) {
				$this->set('order_detail_feedback', $this->Session->read('order_detail_feedback'));
				$this->Session->del('order_detail_feedback');
			}

			// need to do a check to see if there was a parameter passed in the url
			$order_id = $this->params['pass'][0];

			//i could do a check for whether the order id exists or not. that might be necessary at some point.
			$order = $this->Order->find('all', array(
					'conditions' => array(
						'Order.id' => $order_id
					)
			));

			switch($order[0]['Order']['status']) {

				case 'Inventory Check':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Inventory Check') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
				break;
				case 'Payment and Shipping':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Payment and Shipping') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
				break;
				case 'Shipped':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Shipped') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
				break;
				case 'Returned':
					$navigation = $this->Order->navigation_menu;
					for($n=0; $n < count($navigation); $n++) {
						if($navigation[$n]['title'] == 'Returned') {
							$navigation[$n]['class'] = 'active';
						}
					}
					$this->Session->write('admin_subnavigation', $navigation);
				break;

			}

			// build the products in order array *****************/
			$this->loadModel('ItemVariation');
			// still need item.name and item.item_category_id (name)
			$this->loadModel('Item');
			$this->loadModel('ItemCategory');
			$this->loadModel('Option');
			// sku, price, variation name
			foreach ($order[0]['OrderedItem'] as $o) {
				$item_variation = $this->ItemVariation->find('all', array(
							'fields' => array(
								'ItemVariation.item_id', 'ItemVariation.sku', 'ItemVariation.name'
							),
							'conditions' => array(
								'ItemVariation.id' => $o['item_variation_id']
							)
				));
				$item = $this->Item->find('all', array(
							'fields' => array(
								'Item.name','Item.item_category_id'
							),
							'conditions' => array(
								'Item.id' => $item_variation[0]['ItemVariation']['item_id']
							)
						));


				if($o['option_id'] !== '') {
					$option = $this->Option->find('all', array(
							'conditions' => array(
								'Option.id' => $o['option_id']
							)
					));
					// need this to calculate the totals
					// this is a silly if condition...should have to do it
					/*
					if(count($option) !== 0) {
						$option[0]['addon']['price'] = $option[0]['Option']['price'];
					}
					*/
					//$option[0]['addon']['price'] = $o['option_price'];
					//$option[0]['Option']['quantity'] = $o['option_quantity'];
					//$option[0]['Option']['price'] = o['option_price'];
				}
				$item_variation[0]['ItemVariation']['price'] = $o['item_variation_price'];
				$item[0]['Item']['item_name'] = $item[0]['Item']['name'];
				$item[0]['Item']['item_category_name'] = $this->ItemCategory->get_category_name($item[0]['Item']['item_category_id']);
				//$products_in_order[] = $item_variation[0];
				$products_in_order[] = array_merge($item_variation[0]['ItemVariation'], $item[0]['Item'], $o);

			}

			//build the totals array based on stored prices.
			$totals = $this->Order->calculate_order_totals($products_in_order, $order[0]['Person'], $order[0]['Order']);

			// billing and shipping
			$order[0]['Creditcard']['number'] = $this->Crypter->deCrypt($order[0]['Creditcard']['number']);
			$order[0]['Creditcard']['type'] = $this->Crypter->deCrypt($order[0]['Creditcard']['type']);
			$order[0]['Creditcard']['security_code'] = $this->Crypter->deCrypt($order[0]['Creditcard']['security_code']);

			$billing_information = array_merge($order[0]['Person'], $order[0]['Creditcard']);

			// this condition wont always be necessarily the case.
			if($order[0]['Order']['shipping_type'] == 'Alternate') {
				$this->loadModel('Person');
				$shipping_contact = $this->Person->find('all', array(
						'conditions' => array(
							'Person.order_id' => $order[0]['Order']['id']
						)
				));

				$shipping_information = $shipping_contact[0]['Person'];

			} else {
				$shipping_information = $order[0]['Person'];
			}

			$statuses = array(
				array(
					'name' => 'Inventory Check',
					'value' => 'inventory_check',
				),
				array(
					'name' => 'Payment and Shipping',
					'value' => 'payment_and_shipping',
				),
				array(
					'name' => 'Shipped',
					'value' => 'shipped',
				),
				array(
					'name' => 'Returned',
					'value' => 'returned',
				),
			);

			$this->set('statuses', $statuses);
			$this->set('order', $order[0]);
			$this->set('totals', $totals);

			$this->set('products_in_order', $products_in_order);
			$this->loadModel('Creditcard');
			$this->set('cc', $this->Creditcard->create());
			$this->set('billing_information', $billing_information);
			$this->set('shipping_information', $shipping_information);


		}

		function admin_delete() {

			$this->loadModel('OrderedItem');
			$this->loadModel('Person');
			$this->loadModel('Creditcard');

			//delete from the detail page
			if($this->params['pass'][0]) {
				$order_id = $this->params['pass'][0];
				$person_id = $this->params['pass'][1];

				$status = $this->Order->find('all', array(
					'fields' => 'Order.status',
					'conditions' => array(
						'Order.id' => $order_id
					)
				));

				switch($status[0]['Order']['status']) {
					case 'Inventory Check':
						$page = 'inventory_check';
					break;
					case 'Payment and Shipping':
						$page = 'payment_and_shipping';
					break;
					case 'Shipped':
						$page = 'shipped';
					break;
					case 'Returned':
						$page = 'returned';
					break;
				}

				// grab the person id and credit card info first. or from the parameters...
				if($this->Order->delete($order_id)) {
					$this->OrderedItem->query('delete from ordered_items where order_id = "'. $order_id .'"');
					$this->Creditcard->query('delete from creditcards where person_id = "'. $person_id .'"');
					$this->Person->query('delete from people where id = "'. $person_id .'"');
				}
				// redirect to the process action with the items status
				$this->redirect('process/' . $page);
			}

			// from the process action
			if ($this->RequestHandler->isAjax()) {
				Configure::write('debug', 0);
				//$this->layout = 'plain';
				$this->header('Pragma: no-cache');
				$this->header('Cache-control: no-cache');

				foreach($this->data['Order']['ids'] as $oid) {
					//$ids[] = $oid;

					$order = $this->Order->find('all', array(
							'fields' => array('Order.person_id'),
							'conditions' => array(
								'Order.id' => $oid
							)
					));

					$person = $this->Person->find('all', array(
							'fields' => array('Person.id'),
							'conditions' => array(
								'Person.id' => $order[0]['Order']['person_id']
							)
					));

					$person_id = $person[0]['Person']['id'];

					if($this->Order->delete($oid)) {

						$this->OrderedItem->query('delete from ordered_items where order_id = "'. $oid .'"');
						$this->Creditcard->query('delete from creditcards where person_id = "'. $person_id .'"');
						$this->Person->query('delete from people where id = "'. $person_id .'"');
						//$result['ids'] = $ids;
						// this isnt working for some reason
						/*
						$this->OrderedItem->deleteAll( array(
								//'OrderedItem.order_id' => '"'. $oid .'"'
								'OrderedItem.order_id' => '"'. $oid .'"'
								)
							);
						*/
						$this->Session->write('order_management_feedback_message', array('Orders deleted.'));

					} else {
						$this->Session->write('order_management_feedback_message', array('There was an error deleting these orders'));
					}


				}

				//$results = json_encode($result);
				echo $results;
				//$this->set('results', $results);
			}


		}

		function admin_index() {
			$this->redirect('process/inventory_check/');
		}

		function admin_process_lucca ($type = null, $isViewAll = false) {
			$this->Ssl->force();
			$this->layout = 'admin_orders_management';

			$this->loadModel('Item');
			$this->Item->processLuccaItems = true;
			$this->paginate = array(
				'Item' => array(
					'conditions' => array(
						'Item.lucca_original' => 1
					),
					'fields' => array('*'),
					'limit' => 8,
					'order' => 'Item.name ASC',
				),
			);
			if (intval($type) > 0) {
				$this->paginate['Item']['conditions']['Item.item_type_id'] = intval($type);
			}

			$this->set('selectedType', $type);

			if ($isViewAll) {
				$this->paginate();
				$this->set('luccaOriginalItems', $this->Item->luccaOriginalsItems(array('Item.lucca_original' => 1), array('*'), 'Item.name ASC', null, null, 1, array()));
			}	else {
				$this->set('luccaOriginalItems', $this->paginate('Item'));
			}

			$this->loadModel('InventoryLocation');
			$this->InventoryLocation->recursive = 0;
			$locations = $this->InventoryLocation->find('all');
			$locationsShortAndDisplayNames = array();
			foreach ($locations as $location) {
				$locationsShortAndDisplayNames[$location['InventoryLocation']['id']] = array(
					'shortName' => $location['InventoryLocation']['short'],
					'longName' => $location['InventoryLocation']['display_name']
				);
			}
			$this->set('locationsNames', $locationsShortAndDisplayNames);

			$this->loadModel('NoteStatus');
			$noteStatusesFilter['newest'] = 'newest';
			$noteStatusesFilter['oldest'] = 'oldest';
			$noteStatusesFilter = array_merge($noteStatusesFilter, $this->NoteStatus->find('list', array('fields' => array('NoteStatus.short', 'NoteStatus.name'))));
			$this->set('noteStatuses', $this->NoteStatus->find('list', array('fields' => array('NoteStatus.int', 'NoteStatus.name'))));
			$this->set('noteStatusesFilter', $noteStatusesFilter);

			$this->loadModel('ItemType');
			$this->ItemType->recursive = 0;
			$itemTypesFilter = array('all' => '-- All Categories --');
			$itemTypesFilter += $this->ItemType->find('list');
			$this->set('itemTypesFilter', $itemTypesFilter);

			$navigation = $this->Order->navigation_menu;
			for($n=0; $n < count($navigation); $n++) {
				if($navigation[$n]['title'] == 'Inventory Check') {
					$navigation[$n]['class'] = 'active';
				}
			}
			$this->Session->write('admin_subnavigation', $navigation);
		}

		function admin_save_note() {
			if (!empty($this->data)) {
				$this->loadModel('Note');
				$this->loadModel('NoteStatus');
				$this->loadModel('Item');
				$this->Note->create();
				if ($this->Note->save($this->data)) {
					$this->loadModel('InventoryLocation');
					if (isset($this->data['Note']['to']) && !empty($this->data['Note']['to'])) {
						$emails = $this->InventoryLocation->find('list', array('conditions' => array('InventoryLocation.id' => $this->data['Note']['to']), 'fields' => array('InventoryLocation.id', 'InventoryLocation.email')));
						$commentedItem = $this->Item->find('first', array('conditions' => array('Item.id' => $this->data['Note']['item'])));
						$noteStatus = $this->NoteStatus->find('first', array('conditions' => array('NoteStatus.int' => $this->data['Note']['status'])));

						foreach ($emails as $email) {
							$this->Email->replyTo = 'Lucca Antiques<info@luccaantiques.com>';
							$this->Email->from = 'Lucca Antiques<info@luccaantiques.com>';
							$this->Email->sendAs = 'html';
							$this->Email->template = 'new_comment';
							$this->Email->to = $email;
							$this->Email->subject = $noteStatus['NoteStatus']['name'] . ' - New comment for '.$commentedItem['Item']['name'];

							$this->set('itemId', $this->data['Note']['item']);
							$this->set('noteText', $this->data['Note']['note']);

							$this->Email->send();
						}
					}
				}
			}
			$this->redirect(array('controller' => 'orders', 'action' => 'process_lucca'));
		}

		function admin_delete_note($id) {
			if ($id) {
				$this->loadModel('Note');
				$note = $this->Note->find('first', array('conditions' => array('Note.id' => $id)));
				$this->Note->delete($id);
				$this->redirect(array('controller' => 'orders', 'action' => 'process_lucca', 'prefix' => 'admin', $note['Note']['item']));
			}
			$this->redirect(array('controller' => 'orders', 'action' => 'process_lucca', 'prefix' => 'admin'));
		}
		function admin_update_quantity($id) {
			if ($this->RequestHandler->isAjax()) {
				$this->loadModel('InventoryQuantity');
				$this->InventoryQuantity->deleteAll(array('InventoryQuantity.item' => intval($id)), false, false);
				foreach ($this->data['InventoryQuantity'] as $locationId => $itemQuantity) {
					if (is_numeric($itemQuantity)) {
						$uniqueKey['item'] = $id;
						$uniqueKey['location'] = $locationId;

						$extraFiels['quantity'] = intval($itemQuantity);

						$this->InventoryQuantity->create();
						$this->InventoryQuantity->save(array_merge($extraFiels, $uniqueKey));
					}
				}

				$this->layout = 'ajax';
				Configure::write('debug', 0);
				$this->set('response', json_encode(array('succes' => true, 'item_id' => $id)));
			} else {
				$this->redirect('/admin');
			}
		}
	}