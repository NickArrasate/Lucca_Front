<?php
/* SVN FILE: $Id$ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/admin/logout', array('controller' => 'users', 'action' => 'logout'));

	Router::connect('/admin/item/grid/*', array('controller' => 'item', 'action' => 'grid', 'prefix' => 'admin', 'admin' => true));
	Router::connect('/admin/item/search/*', array('controller' => 'item', 'action' => 'grid', 'prefix' => 'admin', 'admin' => true));

    Router::connect('/trade/register', array('controller' => 'trade', 'action' => 'register'));
    Router::connect('/trade/login', array('controller' => 'trade', 'action' => 'login'));
    Router::connect('/trade/custom', array('controller' => 'trade', 'action' => 'custom'));

	Router::connect('/item/grid/*', array('controller' => 'item', 'action' => 'grid'));
	Router::connect('/item/search/*', array('controller' => 'item', 'action' => 'grid'));

	Router::parseExtensions('xml');
	Router::connect(
		"/rest/photo/:id",
		array("controller" => "rest_api", "action" => "image_delete", "prefix" => "item", "rest" => true, "[method]" => "DELETE", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/item/",
		array("controller" => "rest_api", "action" => "add", "prefix" => "item", "rest" => true, "[method]" => "POST", "ext" => "xml")
	);
	Router::connect(
		"/rest/item/:id",
		array("controller" => "rest_api", "action" => "edit", "prefix" => "item", "rest" => true, "[method]" => "POST", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/item/:id",
		array("controller" => "rest_api", "action" => "edit", "prefix" => "item", "rest" => true, "[method]" => "PUT", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/item/:id",
		array("controller" => "rest_api", "action" => "delete", "prefix" => "item", "rest" => true, "[method]" => "DELETE", "ext" => "xml"),
		array("id" => "[0-9]+")
	);

	Router::connect(
		"/rest/category/",
		array("controller" => "rest_api", "action" => "add", "prefix" => "category", "rest" => true, "[method]" => "POST", "ext" => "xml")
	);
	Router::connect(
		"/rest/category/:id",
		array("controller" => "rest_api", "action" => "edit", "prefix" => "category", "rest" => true, "[method]" => "POST", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/category/:id",
		array("controller" => "rest_api", "action" => "edit", "prefix" => "category", "rest" => true, "[method]" => "PUT", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/category/:id",
		array("controller" => "rest_api", "action" => "delete", "prefix" => "category", "rest" => true, "[method]" => "DELETE", "ext" => "xml"),
		array("id" => "[0-9]+")
	);

	Router::connect(
		"/rest/type/",
		array("controller" => "rest_api", "action" => "add", "prefix" => "type", "rest" => true, "[method]" => "POST", "ext" => "xml")
	);
	Router::connect(
		"/rest/type/:id",
		array("controller" => "rest_api", "action" => "edit", "prefix" => "type", "rest" => true, "[method]" => "POST", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/type/:id",
		array("controller" => "rest_api", "action" => "edit", "prefix" => "type", "rest" => true, "[method]" => "PUT", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
	Router::connect(
		"/rest/type/:id",
		array("controller" => "rest_api", "action" => "delete", "prefix" => "type", "rest" => true, "[method]" => "DELETE", "ext" => "xml"),
		array("id" => "[0-9]+")
	);
?>