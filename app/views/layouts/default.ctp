<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<meta name="robots" content="noindex,nofollow,noarchive">
		<title><?php echo $title_for_layout?></title>
		<meta name="description" content="Lucca Antiques is a constantly changing inventory of fresh and the eclectic antique tables, lamps, wall decor, furniture, seating and more.  Locations in New York and Los Angeles.">
		<meta name="keywords" content="lucca antiques, furniture, wall decor, pictures, paintings, couch, seating, chair, lamps, mirrors, lights, antiques, antique">
		<meta name="google-site-verification" content="TRc8B8MLF5on9ePLPX6Rlovh5rAUV3nAQKzE_xccaE8" />
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
		<div class="container">
			<?php if($title_for_layout !== "Lucca Antiques"){ ?>
			<!-- <dl><dd class="cart"><a href="/orders/view/">View Cart (<?php if(isset($cart_count)) { echo $cart_count; } else { echo '0';} ?>)</a></dd></dl> -->
			<?php } ?>
			<dl class="header">
				<dd><a href="/"><img src="/img/lucca_logo.png" alt="Lucca Antiques logo" /></a></dd>
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
					<div class="menu-block">
						<ul class="menus">
						<?php while ($i<5){
							$value=each($item_types);
							$i++;
							?>

							<li class="menu"><a href="/item/grid/<?php echo ($value[0]) ?>/all/all" <?php if (isset($current_item_type_id)&&($current_item_type_id == $value[0])) echo 'class="selected"'; ?> title=""><?php echo $value[1] ?></a></li>

						<?php }
						?>
							<li class="menu6">
								<div class="locations">
									<a href="#" title="More">More</a>
									<div class="pulldown">
										<ul class="submenus">
										<?php while (current($item_types)){
											$value=each($item_types);
							?>
											<li class="submenu"><a href="/item/grid/<?php echo ($value[0]) ?>/all/all" title=""><?php echo $value[1] ?></a></li>
											<?php }
							?>
										</ul>
									</div>
								</div></li>
							<li class="menu7">
								<div class="locations">
									<a href="/item/grid/all/all/all" <?php if (isset($current_item_type_id) && $current_item_type_id == 0) echo 'class="selected"'; ?> title="All Inventory">All Inventory</a>
									<div class="pulldown">
										<ul class="submenus">
											<li class="submenu1"><a href="/item/grid/all/all/1" title="">Los Angeles</a></li>
											<li class="submenu2"><a href="/item/grid/all/all/2" title="">New York</a></li>
										</ul>
									</div>
								</div>
							</li>
							<li class="search">
									<div class="searchform <?php echo (empty($searchString)) ? "disabled" : "enabled"; ?>">
										<?php echo $form->create('Search', array('url' => array('controller' => 'item', 'action' => 'search'))); ?>
											<?php echo $form->text('Search.item', array('value' => $searchString)); ?>
											<?php echo $form->label('Search.item', 'Search'); ?>
										<?php echo $form->end(); ?>
									</div>
							</li>
						</ul>
					</div>
				</dd>
			</dl>

			<?php echo $content_for_layout ?>

			<dl class="footer">
				<dd class="first"><a href="/"><span>Home</span></a></dd>
				<dd class="second"><a href="/pages/about/"><span>About</span></a></dd>
				<dd class="third"><a href="/pages/order-policy/"><span>Order Policy</span></a></dd>
				<dd class="fourth"><a href="/pages/disclosure/"><span>Disclosure</span></a></dd>
				<dd class="fifth"><a href="/pages/contact/"><span>Contact</span></a></dd>
				<dd class="sixth"><a href="/pages/press/"><span>Press</span></a></dd>
				<dd class="seventh"><span> &#169; 2010 Lucca Antiques</span></dd>
			</dl>
		</div>
		<!-- <p class="design-credits"><a href="http://www.btrax.com">web design by btrax, Inc.</a></p> -->

		<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-15786570-1");
pageTracker._trackPageview();
} catch(err) {}</script>

	</body>

</html>

</body>
</html>
