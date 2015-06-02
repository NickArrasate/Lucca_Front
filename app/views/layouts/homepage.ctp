<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta charset="utf-8"></meta>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon" />
        <link href="/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
        <link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
        <link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />

		<meta name="description" content="Lucca Antiques provides a valuable resource for designers and celebrity clients seeking pieces that are unique in scale, composition and origin. A constantly changing inventory assures that the merchandise is always fresh and the eclectic mix of elements makes shopping at Lucca exciting.">
        <meta name="keywords" content="antiques, lucca, tables, chairs, vintage, hollywood, designer, custom, unique" />
	<title><?php echo $title_for_layout?></title>
	<?php echo $scripts_for_layout; ?>	
	<script type="text/javascript" src="/js/jquery.min.js"></script> 
	<script type="text/javascript" src="/js/jquery.easing-1.3.pack.js"></script> 
	<script type="text/javascript" src="/js/jquery.fancybox-1.3.0.pack.js"></script> 
	<script type="text/javascript" src="/js/jquery.lightbox-0.5.js"></script> 
	<script type="text/javascript" src="/js/jquery.hp-slideshow.js"></script>
	<script type="text/javascript" src="/js/search.js"></script>
	<script type="text/javascript" src="/js/menus.js"></script>
	<script type="text/javascript" src="/js/flash_message.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <link type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
	<?php
		echo $this->element('versioned_css', array(
			'files' => array('bootstrap.min', 'base', 'menus', 'jquery.lightbox-0.5', 'jquery.fancybox-1.3.0', 'hp-slideshow')
			));
	?>
	</head>
	<body id="homepage">
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

			<div id="flash_message">
				<h2>
					<?php 
						if($session->check('Message.flash')): 
							$session->flash();
						endif;  
					?>
				</h2>
			</div>

			<?php echo $content_for_layout ?>

			<dl class="footer" style="margin-top:0px !important">
				<dd class="first"><a href="/"><span>home</span></a></dd>
				<dd class="second"><a href="/pages/about/"><span>about</span></a></dd>
				<dd class="fourth"><a href="/pages/disclosure/"><span>disclosure</span></a></dd>
				<dd class="fifth"><a href="/pages/contact/"><span>contact</span></a></dd>
				<dd class="sixth"><a href="/pages/press/"><span>press</span></a></dd>
                <dd class="sixth"><a href="/pages/careers/"><span>careers</span></a></dd>
				<dd class="seventh"><span> &#169; <?php echo date('Y')?> Lucca Antiques</span></dd>
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


