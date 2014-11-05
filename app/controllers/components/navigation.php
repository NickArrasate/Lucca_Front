<?php 

	class NavigationComponent extends Object {

		/*

		Status can either be:

		Hidden, 
		Unsorted,
		Unpublished
		Available,
		Sold


		Item type id (optional) can either be

		all
		1
		2
		3
		4
		5
		6

		The class will be active depending on which subnav /nav is active

		*/

		function navigation($status, $item_type_id = '', $additional_conditions = array()) {

			$item = new Item();


			if ($status == 'Hidden' || $status == 'Available' || $status == 'Sold') {
			
				switch($status) {
				
				case 'Available':
			
					$navigation = array(
					
						'h3' => 'Available Items',
						
						'subnavigation' => array(
						
							'New' => array(
								'link' => '/admin/item/grid/all/Unpublished/',
								'class' => '',
								'title' => 'New / Works in Progress',
							),
							'Online Inventory' => array(
								'link' => '/admin/item/grid/all/Available/',
								'class' => 'active',
								'title' => 'Online Inventory',
							),
							'Addons' => array(
								'link' => '/admin/addon/categories/edit/',
								'class' => '',
								'title' => 'Manage Addons',
							),
							'Autofill' => array(
								'link' => '/admin/autofill/edit',
								'class' => '',
								'title' => 'Autofill Text',
							)
						),
						
						'navigation' => array(
						
							'Available' => array(
									'link' => '/admin/item/grid/'. $item_type_id  .'/Available/',
									'class' => 'active',
									'title' => 'Available', 'count' => $item->get_status_count($item_type_id, 'Available')
								),
							'Hidden' => array(
								'link' => '/admin/item/grid/'. $item_type_id  .'/Hidden/',
								'class' => '',
								'title' => 'Hidden', 'count' => $item->get_status_count($item_type_id, 'Hidden')
							),
							'Sold' => array(
								'link' => '/admin/item/grid/'. $item_type_id  .'/Sold/',
								'class' => '',
								'title' => 'Sold', 'count' => $item->get_status_count($item_type_id, 'Sold')
							)
						
						),
						
						'item_statuses' => array(
							'Available', 'Hidden', 'Sold'
						)
						
						);
						
						break;
						
						case 'Hidden':
						
						$navigation = array(
						
						'h3' => 'Hidden Items',
						
						'subnavigation' => array(
						
							'New' => array(
								'link' => '/admin/item/grid/all/Unpublished/',
								'class' => '',
								'title' => 'New / Works in Progress',
							),
							'Online Inventory' => array(
								'link' => '/admin/item/grid/all/Available/',
								'class' => 'active',
								'title' => 'Online Inventory',
							),
							'Addons' => array(
								'link' => '/admin/addon/categories/edit/',
								'class' => '',
								'title' => 'Manage Addons',
							),
							'Autofill' => array(
								'link' => '/admin/autofill/edit',
								'class' => '',
								'title' => 'Autofill Text',
							)
							
						),
						
						'navigation' => array(
						
							'Available' => array(
									'link' => '/admin/item/grid/'. $item_type_id  .'/Available/',
									'class' => '',
									'title' => 'Available', 'count' => $item->get_status_count($item_type_id, 'Available')
								),
							'Hidden' => array(
								'link' => '/admin/item/grid/'. $item_type_id  .'/Hidden/',
								'class' => 'active',
								'title' => 'Hidden', 'count' => $item->get_status_count($item_type_id, 'Hidden')
							),
							'Sold' => array(
								'link' => '/admin/item/grid/'. $item_type_id  .'/Sold/',
								'class' => '',
								'title' => 'Sold', 'count' => $item->get_status_count($item_type_id, 'Sold')
							)
						
						),
						
						'item_statuses' => array(
							'Available', 'Hidden', 'Sold'
						)
						
						);
						
						break;
						
						case 'Sold':
						
						$navigation = array(
						
						'h3' => 'Sold Items',
						
						'subnavigation' => array(
						
							'New' => array(
								'link' => '/admin/item/grid/all/Unpublished/',
								'class' => '',
								'title' => 'New / Works in Progress',
							),
							'Online Inventory' => array(
								'link' => '/admin/item/grid/all/Available/',
								'class' => 'active',
								'title' => 'Online Inventory',
							),
							'Addons' => array(
								'link' => '/admin/addon/categories/edit/',
								'class' => '',
								'title' => 'Manage Addons',
							),
							'Autofill' => array(
								'link' => '/admin/autofill/edit',
								'class' => '',
								'title' => 'Autofill Text',
							)
							
						),
						
						'navigation' => array(
						
							'Available' => array(
									'link' => '/admin/item/grid/'. $item_type_id  .'/Available/',
									'class' => '',
									'title' => 'Available', 'count' => $item->get_status_count($item_type_id, 'Available')
								),
							'Hidden' => array(
								'link' => '/admin/item/grid/'. $item_type_id  .'/Hidden/',
								'class' => '',
								'title' => 'Hidden', 'count' => $item->get_status_count($item_type_id, 'Hidden')
							),
							'Sold' => array(
								'link' => '/admin/item/grid/'. $item_type_id  .'/Sold/',
								'class' => 'active',
								'title' => 'Sold', 'count' => $item->get_status_count($item_type_id, 'Sold')
							)
						
						),
						
						'item_statuses' => array(
							'Available', 'Hidden', 'Sold'
						)
						
						);
						
						break;
				}
				
			} else {
			
			// else it's either Unpublished or Unsorted
			
			switch($status) {
			
				case 'Unpublished':
			
				$navigation = array(
				
					'h3' => 'Works in Progress',
					
					'subnavigation' => array(
					
						'New' => array(
							'link' => '/admin/item/grid/all/Unpublished/',
							'class' => 'active',
							'title' => 'New / Works in Progress',
						),
						'Online Inventory' => array(
							'link' => '/admin/item/grid/all/Available/',
							'class' => '',
							'title' => 'Online Inventory',
						),
						'Addons' => array(
							'link' => '/admin/addon/categories/edit/',
							'class' => '',
							'title' => 'Manage Addons',
						),
						'Autofill' => array(
							'link' => '/admin/autofill/edit',
							'class' => '',
							'title' => 'Autofill Text',
						)
						
					),
					
					'navigation' => array(
					
						'Create' => array(
							'link' => '/admin/item/image/create',
							'class' => '',
							'title' => 'Create New Product',
						),
						'In Progress' => array(
							'link' => '/admin/item/grid/all/Unpublished',
							'class' => 'active',
							'title' => 'Works in Progress', 'count' => $item->get_status_count($item_type_id, 'Unpublished', $additional_conditions)
						),
						'Unsorted' => array(
							'link' => '/admin/item/grid/all/Unsorted',
							'class' => '',
							'title' => 'Unsorted', 'count' => $item->get_status_count($item_type_id, 'Unsorted', $additional_conditions)
						)
						
					),
					
					'item_statuses' => array(
							'Works in Progress', 
							'Unsorted'
						)
					
					);
					
					break;
					
					case 'Unsorted':
					
					$navigation = array( 
					
					'h3' => 'Unsorted - Images that don\'t have details yet',
					
					'subnavigation' => array(
					
						'New' => array(
							'link' => '/admin/item/grid/all/Unpublished/',
							'class' => 'active',
							'title' => 'New / Works in Progress',
						),
						'Online Inventory' => array(
							'link' => '/admin/item/grid/all/Available/',
							'class' => '',
							'title' => 'Online Inventory',
						),
						'Addons' => array(
							'link' => '/admin/addon/categories/edit/',
							'class' => '',
							'title' => 'Manage Addons',
						),
						'Autofill' => array(
							'link' => '/admin/autofill/edit',
							'class' => '',
							'title' => 'Autofill Text',
						)
						
					),
					
					'navigation' => array(
					
						'Create' => array(
							'link' => '/admin/item/image/create',
							'class' => '',
							'title' => 'Create New Product',
						),
						'In Progress' => array(
							'link' => '/admin/item/grid/all/Unpublished',
							'class' => '',
							'title' => 'Works in Progress', 'count' => $item->get_status_count($item_type_id, 'Unpublished')
						),
						'Unsorted' => array(
							'link' => '/admin/item/grid/all/Unsorted',
							'class' => 'active',
							'title' => 'Unsorted', 'count' => $item->get_status_count($item_type_id, 'Unsorted')
						)
						
					),
					
					'item_statuses' => array(
							'Works in Progress', 
							'Unsorted'
						)
					
					);
					
					break;
				}

			}
			
			return $navigation;

		}

	}

?>