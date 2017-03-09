<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
		<title><?php echo $title_for_layout?></title>
		<?php 
			echo $scripts_for_layout;
			echo $this->element('versioned_css', array('files' => 'base'));
			//debug($item_category_id);
		?>
	</head>

	<body>
		<div class="container">
			<dl class="header">
				<dd><a href="/"><img src="/img/lucca_logo.png" alt="Lucca Antiques logo" /></a></dd>
			</dl>

			<?php echo $content_for_layout ?>

			<dl class="footer-copyright">
				<dd class="seventh"><span>&#169; 2010 Lucca Antiques</span></dd>
			</dl>
		</div>

	</body>
	
</html> 

</body>
</html>
