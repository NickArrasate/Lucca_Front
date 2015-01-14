<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="utf-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title><?php echo $title_for_layout?></title>
		<?php echo $this->element('versioned_css', array('files' => array('details', 'print'))); ?>
	</head>

	<body>
		<h2 class="ProductTitle"><?php echo $item_detail['Item']['name'] ?></h2>
		<div style="text-align: center;">
			<img style="max-height:180px; padding-bottom:10px;" src="http://www.luccaantiques.com/img/logoboxtop.png" alt="Lucca Antiques logo" />
		</div>
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
