<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title><?php echo $title_for_layout?></title>
		<?php 
			echo $html->css('details');
			echo $html->css('print');
		?>
	</head>

	<body>
		<h2 class="ProductTitle"><?php echo $item_detail['Item']['name'] ?></h2>
		<img style="padding-left:345px; width:200px; padding-bottom:10px;" src="http://www.luccaantiques.com/img/logoboxtop.png" alt="Lucca Antiques logo" />

			<?php echo $content_for_layout ?>
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
