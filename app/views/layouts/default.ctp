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
	<?php 
		echo $html->css('bootstrap.min.css');
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
	<script type="text/javascript" src="/js/cloudzoom/cloudzoom.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/css/jquery.lightbox-0.5.css" /> 
	<link rel="stylesheet" type="text/css" href="/css/jquery.fancybox-1.3.0.css" /> 
	<link rel="stylesheet" type="text/css" href="/css/hp-slideshow.css" />
	<link rel="stylesheet" type="text/css" href="/js/cloudzoom/cloudzoom.css" />

	<!-- Include Cloud Zoom script. -->

	<script type="text/javascript">
		$(document).ready(function(){
			$("#SearchAddForm label").click(function(){
				$("#SearchAddForm").submit();
			});
		});
	</script>
	</head>

	<body>
		<div class="container">
		    <?php if($title_for_layout !== "Lucca Antiques"){ ?>
			<!-- <dl><dd class="cart"><a href="/orders/view/">View Cart (<?php if(isset($cart_count)) { echo $cart_count; } else { echo '0';} ?>)</a></dd></dl> -->
			<?php } ?> 
			<dl class="header">
				<dd style="text-align:center"><a href="/"><img src="/img/logoboxtop.png" style="width:175px" alt="Lucca Antiques" class="active"/></a></dd>
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
					<div class="menu-block navbar navbar-default">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="collapse navbar-collapse" id="menu-navbar">
							<ul class="menus nav navbar-nav">
							<?php 
									/*$i = 0;
								while ($i<6){
								$value=each($item_types);
									$i++;*/
								?>
								<?php foreach($item_types['base_types'] as $item):?>
								<li class="menu">
									<a href="/item/grid/<?php echo ($item['id']) ?>/all/all" <?php if (isset($current_item_type_id)&&($current_item_type_id == $item['id'])) echo 'class="selected"'; ?> title="<?php echo $item['name'];?>"><?php echo $item['name'] ?></a>
								</li>
								<?php endforeach;?>
								<?php if(!empty($item_types['over_base_types'])):?>
								<li class="menu6">
									<div class="locations">
										<a href="#" title="Objects">Objects</a>
										<div class="pulldown">
											<ul class="submenus">
											<?php /*while (current($item_types)){
												$value=each($item_types);*/
								?>
												<?php foreach($item_types['over_base_types'] as $item):?>
												<li class="submenu">
													<a href="/item/grid/<?php echo ($item['id']) ?>/all/all" title="<?php echo $item['name'];?>"><?php echo $item['name']; ?></a>
												</li>
												<?php endforeach;?>
											</ul>
										</div>
									</div>
								</li>
								<?php endif;?>
								<li class="menu">
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
					</div>
				</dd>
			</dl>

			<?php echo $content_for_layout ?>

			<dl class="footer">
				<dd class="first"><a href="/"><span>home</span></a></dd>
				<dd class="second"><a href="/pages/about/"><span>about</span></a></dd>
				<dd class="fourth"><a href="/pages/disclosure/"><span>disclosure</span></a></dd>
				<dd class="fifth"><a href="/pages/contact/"><span>contact</span></a></dd>
				<dd class="sixth"><a href="/pages/press/"><span>press</span></a></dd>
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
