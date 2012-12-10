
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title><?php echo $title_for_layout?></title>
		<?php 
			echo $scripts_for_layout;
			echo $html->css('base');
		?>
	</head>

	<body>
		<div class="container">
			<!-- need to display a session variable here for the cart item count-->
			<dl><dd class="cart"><a href="http://s80321.gridserver.com/order/view/">View Cart (0)</a></dd></dl>
			<dl class="header">
				<dd><a href="http://s80321.gridserver.com/"><img src="http://s80321.gridserver.com/img/lucca_logo.png" alt="Lucca Antiques logo" /></a></dd>
				<dd>
					<dl class="nav">
						<!-- the navigation items need to be dynamic at some point-->
						<dd class="first"><a href="http://s80321.gridserver.com/item/grid/1/all/"><span>Lighting</span></a></dd>
						<dd class="second"><a href="http://s80321.gridserver.com/item/grid/2/all/"><span>Seating</span></a></dd>
						<dd class="third"><a href="http://s80321.gridserver.com/item/grid/3/all/"><span>Tables</span></a></dd>
						<dd class="fourth"><a href="http://s80321.gridserver.com/item/grid/4/all/"><span>Wall Decor</span></a></dd>
						<dd class="fifth"><a href="http://s80321.gridserver.com/item/grid/5/all/"><span>Case Goods</span></a></dd>
						<dd class="sixth"><a href="http://s80321.gridserver.com/item/grid/6/all/"><span>Garden &amp; More</span></a></dd>
					</dl>
				</dd>
			</dl>
<?php
	echo $html->css('order');
?>
<div class="c_checkout">

	<div class="wrapper">
		
		<h2>
		<?php 
			if($session->check('Message.flash')): 
				$session->flash();
			endif;  
		?>
		</h2>
		
	</div>
	<div class="item-category-border"></div>
</div>

<dl class="footer">
				<dd class="first"><a href="http://s80321.gridserver.com/"><span>Home</span></a></dd>
				<dd class="second"><a href="http://s80321.gridserver.com/pages/about/"><span>About</span></a></dd>
				<dd class="third"><a href="http://s80321.gridserver.com/pages/order-policy/"><span>Order Policy</span></a></dd>
				<dd class="fourth"><a href="http://s80321.gridserver.com/pages/disclosure/"><span>Disclosure</span></a></dd>
				<dd class="fifth"><a href="http://s80321.gridserver.com/pages/contact/"><span>Contact</span></a></dd>
				<dd class="sixth"><a href="http://s80321.gridserver.com/pages/press/"><span>Press</span></a></dd>
				<dd class="seventh"><span>Copyright 2010 Lucca Antiques</span></dd>
			</dl>
		</div>

	</body>
	
</html> 

</body>
</html>