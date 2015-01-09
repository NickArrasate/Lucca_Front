<?php if (is_array($files)): ?>
	<?php foreach($files as $css): ?>
		<link rel="stylesheet" type="text/css" href="/css/<?php echo $css; ?>.css?v=<?php echo CSS_VERSION; ?>">
	<?php endforeach; ?>
<?php elseif (is_string($files)): ?>
	<link rel="stylesheet" type="text/css" href="/css/<?php echo $files; ?>.css?v=<?php echo CSS_VERSION; ?>">
<?php endif; ?>