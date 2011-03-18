<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title><?php echo $title_for_layout?></title>
		
		<link rel="stylesheet" type="text/css" href="/css/admin.css" />
		<link rel="stylesheet" type="text/css" href="/css/jquery.ui.css" />
		<script type="text/javascript" src="/js/jquery.min.js"></script> 
		<script type="text/javascript" src="/js/jquery.easing.1.3.js"></script> 		
		<script type="text/javascript" src="/js/jquery.cookie.js"></script> 
		<script type="text/javascript" src="/js/item_admin_grid.js"></script>
		<?php 
			echo $scripts_for_layout;
			//echo $html->css('admin');
			//echo $html->css('jquery.ui');
		?>
	</head>

	<body>
		<div class="container">
			<dl class="header">
				<dd><a href="/"><img src="/img/lucca_logo.png" alt="Lucca Antiques logo" /></a></dd>
				<dd class="nav">
					<dl>
						<!-- the navigation items need to be dynamic at some point-->
						<dd class=""><a href="/admin/orders/process_lucca/"><span>Order Management</span></a></dd>
						<dd class="active"><a href="/admin/item/grid/all/Unpublished/"><span>Product Management</span></a></dd>
					</dl>
				</dd>
				
				<dd class="floatRight logout">
					<a class="button white-background black-text" href="/users/logout/" />Logout</a>
				</dd>
			</dl>
			
			<!--<h2>Product Management</h2>-->

			<dl class="subnavigation">
				<?php foreach($admin_subnavigation as $a) { ?>
					<dd class="<?php echo $a['class'] ?>"><a href="<?php echo $a['link'] ?>"><?php echo $a['title']?></a></dd>
				<?php } ?>
			</dl>
			
			<div class="content">

			<?php echo $content_for_layout ?>
			
			</div>

		</div>
		
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
