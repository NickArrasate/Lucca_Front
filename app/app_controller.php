<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 */
class AppController extends Controller {

	//var $components = array('Security');
	var $helpers = array('Javascript', 'Html'); 
	var $_User = array();
	var $persistModel = true;
	var $components = array('Cookie' => array(
		'key' => SECURITY_COOKIES_KEY,
		'time' => LIFETIME_AUTHORIZATION_COOKIES
	));
	
	# goes with line 67 and 68
	function forceSSL() {
		$this->redirect('https://' . $_SERVER['SERVER_NAME'] . $this->here);
	}
	
	function beforeFilter() {
		// $this->loadModel('ItemOccurrence');
		// $this->ItemOccurrence->fixOccurrences();
	
		/*
		11-26-2009 10:13pm
		
		This is one way to force https:// only for orders/ and admin/ alongside the CakePHP framework. The code below makes use of the security component to apply SSL to only particular controllers. The code below forces https:// when particular controllers are requested. In this case - we only want it for "orders"
		
		Using the code below requires that all of the forms throughout the site that interacts through https:// be created using the form helper that is included with CakePHP. If not then things like the scenario described below happens:
		-- click on purchase button of an item detail page
		-- page stops at orders/add_item, and an empty page(request got blackholed)
		
		changing all of the form elements on an item detail page to use the cakephp form helper will avoid the blackhole / blank page and allow the item to be added successfully to the shopping cart
		
		References:
		http://book.cakephp.org/view/175/Security-Component
		http://techno-geeks.org/2009/03/using-the-security-component-in-cakephp-for-ssl/
		http://api.cakephp.org/class/security-component-in-cakephp-for-ssl/
		
		The form helper however is slightly difficult to work with -- rendering the form elements EXACTLY as in the specs proves difficult. Example - creating select forms WITHOUT an empty option. By default all select elements created with the form helper includes an empty option. 
		
		Another way to force SSL for orders/ and admin is through the .htaccess file at /html/app/webroot/.htaccess
		
		Note* : Using the security component adds an extra layer of security to help fend off CSRF attacks etc, as mentioned in CakePHP documentation
		
		Note*: Adding the Security component causes a blank page tobe shown when logging into the admin panel
		
		*/
		# referenced code
		#$this->Security->blackHoleCallback = 'forceSSL';
		#$this->Security->requireSecure('admin', 'orders');
		
		
		

		// if admin url requested

		if(isset($this->params['admin']) && $this->params['admin']) {
			// check user is logged in
			if( !$this->Cookie->read('User') ) {
				$this->Session->setFlash('You must be logged in for that action.','flash_bad');
				$this->redirect('/login');
			}

			// save user data
			$user = array('User' => $this->Cookie->read('User'));
			$this->_User = $user;
			$this->set('user', $this->_User);

			// change layout
			$this->layout = 'admin';
		}
		$this->loadModel('ItemType');
		$item_types = $this->ItemType->get_menu_items();
		$this->set('item_types',$item_types);

		$searchString = '';
		if (array_key_exists("search", $this->params['named']) && !empty($this->params['named']['search'])) {
			$searchString = $this->params['named']['search'];
		}

		$this->set('searchString', $searchString);

		$this->loadModel('InventoryLocation');
		$list_location_menu = $this->InventoryLocation->get_location_menu();
		$this->set('list_location_menu', $list_location_menu);

		$isTrader = false;
		if ($this->Cookie->read('Trade')) {
			$isTrader = true;
		}
		$this->set('isTrader', $isTrader);

		$isUser = false;
		if ($this->Cookie->read('User')) {
			$isUser = true;
		}
		$this->set('isUser', $isUser);

	}
	
	function beforeRender() {
	
		if($this->Session->check('cart_count')) {
			$cart_count = $this->Session->read('cart_count');
			$this->set('cart_count', $cart_count);
		}
		if($this->Session->check('admin_subnavigation')) {
			$admin_subnavigation = $this->Session->read('admin_subnavigation');
			$this->set('admin_subnavigation', $admin_subnavigation);
		}
		// breadcrumbs
		if ($this->params['controller'] == 'item' && $this->params['action'] == 'grid') {
			$categories = $this->ItemType->find('list', array('fields' => array('ItemType.id', 'ItemType.name')));
			$categories['all'] = $categories[0] = 'All Inventory';
			
			$subcategories = $this->ItemCategory->find('list', array('fields' => array('ItemCategory.id', 'ItemCategory.name')));
			$subcategories['all'] = $subcategories[0] = 'All';

			$type = array_key_exists($this->params['pass'][0], $categories) ? $categories[$this->params['pass'][0]] : 'Unknown';
			$category = array_key_exists($this->params['pass'][1], $subcategories) ? $subcategories[$this->params['pass'][1]] : 'Unknown';
			
			$breadcrumbs = array($type, $category);
			
			$this->set('breadcrumbs', $breadcrumbs);
		}
		
		if ($this->params['controller'] == 'item' && $this->params['action'] == 'details') {
			$this->loadModel('Item');
			
			$itemBreadcrumb = $this->Item->find('first', array(
				'conditions' => array(
					'Item.id' => $this->params['pass'][0]
				),
				'fields' => array(
					'Item.id',
					'Item.item_type_id',
					'Item.item_category_id',
				),
				'contain' => array(
					'ItemType' => array(
						'fields' => array('name')
					),
					'ItemCategory' => array(
						'fields' => array('name')
					)
				)
			));
			
			$breadcrumbs = array($itemBreadcrumb['ItemType']['name'], $itemBreadcrumb['ItemCategory']['name']);
			$this->set('breadcrumbs', $breadcrumbs);
			$this->set('item_category_id', $itemBreadcrumb['ItemCategory']['id']);
			$this->set('item_type_id', $itemBreadcrumb['ItemType']['id']);
		}
	}
}
