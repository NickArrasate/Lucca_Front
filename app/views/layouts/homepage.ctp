<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<meta name="robots" content="noindex,nofollow,noarchive">
		<title><?php echo $title_for_layout?></title>
		<?php
			echo $scripts_for_layout;
			echo $html->css('base');
			//debug($item_category_id);
		?>
		<?php echo $html->css('menus.css'); ?>
		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="/js/jquery.easing-1.3.pack.js"></script>
		<script type="text/javascript" src="/js/jquery.fancybox-1.3.0.pack.js"></script>
		<script type="text/javascript" src="/js/jquery.lightbox-0.5.js"></script>
		<script type="text/javascript" src="/js/jquery.hp-slideshow.js"></script>
		<script type="text/javascript" src="/js/search.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/jquery.lightbox-0.5.css" />
		<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox-1.3.0.css" />
		<link rel="stylesheet" type="text/css" href="/css/hp-slideshow.css" />
	</head>
	<body>
		<div class="container" style="background:#666">
			<?php if($title_for_layout !== "Lucca Antiques"){ ?>
			<!-- <dl><dd class="cart"><a href="/orders/view/">View Cart (<?php if(isset($cart_count)) { echo $cart_count; } else { echo '0';} ?>)</a></dd></dl> -->
			<?php } ?>
			<dl class="header">
				<dd><a href="/"><img src="/img/logoboxtop.jpg" style="width:100px;display:none" alt="Lucca Antiques" class="active"/></a></dd>
				<dd>
					<!--
					<dl class="nav<?php if(isset($current_item_type_id)) { echo '-'. $current_item_type_id;} if(isset($item_type_id)) { echo '-'. ($item_type_id);} ?>">
						<dd class="first"><a href="/item/grid/1/all/"><span>Lighting</span></a></dd>
						<dd class="second"><a href="/item/grid/2/all/"><span>Seating</span></a></dd>
						<dd class="third"><a href="/item/grid/3/all/"><span>Tables</span></a></dd>
						<dd class="fourth"><a href="/item/grid/4/all/"><span>Wall Decor</span></a></dd>
						<dd class="fifth"><a href="/item/grid/5/all/"><span>Case Goods</span></a></dd>
						<dd class="sixth"><a href="/item/grid/6/all/"><span>Garden &amp; More</span></a></dd>
						<dd class="seventh"><a href="/item/grid/all/all/"><span>All Inventory</span></a></dd>
					</dl>
					-->
					<?php echo $this->element('main_menu', array('item_types' => $item_types));?>
				</dd>
			</dl>

			<?php echo $content_for_layout ?>

			<dl class="footer">
				<dd class="first"><a href="/"><span>home</span></a></dd>
				<dd class="second"><a href="/pages/about/"><span>about</span></a></dd>
				<dd class="third"><a href="/pages/order-policy/"><span>order policy</span></a></dd>
				<dd class="fourth"><a href="/pages/disclosure/"><span>disclosure</span></a></dd>
				<dd class="fifth"><a href="/pages/contact/"><span>contact</span></a></dd>
				<dd class="sixth"><a href="/pages/press/"><span>press</span></a></dd>
				<dd class="seventh"><span> &#169; 2012 Lucca Antiques</span></dd>
			</dl>
		</div>
		<!-- <p class="design-credits"><a href="http://www.btrax.com">web design by btrax, Inc.</a></p> -->

		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-12063725-1");
		pageTracker._trackPageview();
		} catch(err) {}</script>

	</body>

</html>

</body>
</html>
