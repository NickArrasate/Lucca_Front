<?php
	$javascript->link('jquery', false);
	$javascript->link('jquery.ui', false);
	$javascript->link('jquery.cookie', false);
	$javascript->link('item_admin_image', false);
	echo $html->css('jquery.fancybox');
	$javascript->link('jquery.easing.1.3.js', false);
	$javascript->link('jquery.fancybox-1.2.1.pack.js', false);
	
	//debug($main_image);
	//debug($detail_images);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('a.image').fancybox();
	});
</script>

<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>Are you sure you want to delete this photo?</strong><p></div>


<dl id="item_statuses">
<?php
	foreach($navigation as $n) {
		if($n['title'] == 'Create New Product') {
			echo '<dd><a class="'. $n['class'] .'" href="'. $n['link'] .'">'. $n['title'] .'</a></dd>';
		} else {
			echo '<dd><a class="'. $n['class'] .'" href="'. $n['link'] .'">'. $n['title'] .' ('. $n['count'] .')</a></dd>';
		}
	} 
?>
</dl>
<div class="gray-left-border edit-images">

	<div class="breadcrumbs">
			<?php
				foreach($admin_subnavigation as $a) {
					if($a['class'] == 'active') {
						echo '<a href="'. $a['link'] .'">'. $a['title'] . '</a>';
					}
				}
			?> >
			<?php if(isset($item_details)) { ?>
			<?php if ($item_details[0]['ItemType']['name']!== null) {?>
			<?php
				echo '<a href="/admin/item/grid/'. $item_details[0]['Item']['item_type_id'] .'/'. $item_details[0]['Item']['status'] .'/">'. $item_details[0]['ItemType']['name'] . '</a>';
			?> >
			<?php } ?>
			<?php } ?>
			
			<?php
			foreach($navigation as $n) {
				if($n['class'] == 'active') {
					echo '<a href="'. $n['link'] .'">'. $n['title'] . '</a>';
				}
			}
			?>
	</div>
	
<div class="subheader">
<?php if(isset($item_details)) { ?>
<?php 

	echo '<h3>' . $h3 . '</h3>';
?>
<?php } ?>
</div>
<div class="notifications">
<?php 
if(isset($errors_images)) {
	foreach($errors_images as $ei) {
		echo '<p>'. $ei .'</p>';
	}
} 
?>
</div>

<?php if(isset($item_details)) { ?>
<div class="summary">
<p>
	<strong>Category: </strong><?php echo $item_details[0]['ItemType']['name'] ?>
</p>
<p>
	<strong>Subcategory: </strong><?php echo $item_details[0]['ItemCategory']['name'] ?>
</p>
<p>
	<strong>Item Name: </strong><?php echo $item_details[0]['Item']['name'] ?>
</p>
<p>
	<strong>Description: </strong><?php echo $item_details[0]['Item']['description'] ?>
</p>
</div>

<?php } ?>

	<?php if(isset($item_details[0]['ItemImage'])) { ?>
	<form action="/admin/item/upload_images/<?php echo $item_details[0]['Item']['id'] ?>" method="post" enctype="multipart/form-data">
	<dl id="image_upload">
		<dt><h4>Main Image:</h4></dt>
			<?php if($main_image['filename'] !== '') { ?>
				<dd>
				<ul>
				<li>
				<a class="image" href="../../../../../files/<?php echo $main_image['filename'] ?>">
					<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $main_image['filename'], $settings)?>" />
				</a>
				</li>
				<?php if(isset($action) && $action !== 'view') { ?>
				<li class="filename">

					<div><?php echo $main_image['filename'] ?></div>
					<input type="file" name="data[ItemImage][0][filename]" size="20" /> 
					<input type="hidden" name="data[ItemImage][0][id]" value="<?php echo $main_image['id'] ?>"/>
					<input type="hidden" name="data[ItemImage][0][primary]" value="1"/>
				</li>
				<?php } ?>
				</ul>
				</dd>
				<?php 
				} else { ?>
				<dd>
				<ul>
				<li>
				<img src="/img/none.jpg" />
				</li>
				
				<?php if(isset($action) && $action !== 'view') { ?>
				<li class="filename">
					
					<input type="file" name="data[ItemImage][0][filename]" size="35" /> 
					<input type="hidden" name="data[ItemImage][0][id]" value="<?php echo $main_image['id'] ?>"/>
					<input type="hidden" name="data[ItemImage][0][primary]" value="1"/>

				</li>
				<?php } ?>
				</ul>
				</dd>
				<?php }  ?>
			<dt><h4>Detail Images:</h4></dt>
			<?php 
			$l=0; ?>
			<?php foreach($detail_images as $i ) { 
			$l++ ?>
			<?php if($i['filename'] !== '') { ?>
				<dd>
				<ul>
				<li>
					<a class="image" href="../../../../../files/<?php echo $i['filename'] ?>">
						<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $settings)?>" />
					</a>
				</li>
				<?php if(isset($action) && $action !== 'view') { ?>
				<li class="filename">
				<div><?php echo $i['filename'] ?></div>
				<input type="file" name="data[ItemImage][<?php echo $l ?>][filename]" size="20" /> 
				<input type="hidden" name="data[ItemImage][<?php echo $l ?>][id]" value="<?php echo $i['id'] ?>"/>
				<input type="hidden" name="data[ItemImage][<?php echo $l ?>][primary]" value="0"/>
				</li>
				<li>
				<span class="gray-background button black-text"><a class="delete-photo" href="/admin/item/delete_image/<?php echo $item_details[0]['Item']['id'] ?>/<?php echo $i['id'] ?>/<?php echo $i['filename'] ?>">Delete</a></span>
				</li>
				<?php } ?>
				</ul>
				</dd>
				<?php } else { ?>
				<dd>
				<ul>
				<li>
				<img src="/img/none.jpg" />
				</li>
				<?php if(isset($action) && $action !== 'view') { ?>
				<li class="filename">
					
					<input type="file" name="data[ItemImage][<?php echo $l ?>][filename]" size="35" /> 
					<input type="hidden" name="data[ItemImage][<?php echo $l ?>][id]" value="<?php echo $i['id'] ?>"/>
					<input type="hidden" name="data[ItemImage][<?php echo $l ?>][primary]" value="0"/>

				</li>
				<?php } ?>
				</ul>
				</dd>
			<?php } } ?>
			
		</dl>

	<?php } else { ?>
		<span class="floatRight"><small>* Required</small></span>
		<dl id="image_upload">
		<form action="/admin/item/create/" method="post" enctype="multipart/form-data">
		
		
		<dt>Main Image* (1 Image)</dt>
		<dd>
			<img src="/img/none.jpg" />
			<input type="file" name="data[ItemImage][][filename]" size="35" /> 
			<input type="hidden" name="data[ItemImage][][primary]" value="1"/>
		</dd>
		
		<dt>Detail Images (Up to 9 Images)</dt>
		<?php for($k=1; $k <9; $k++ ) {?>
		<dd>
			<img src="/img/none.jpg" />
			<input type="file" name="data[ItemImage][][filename]" size="35" value=""/> 
			<input type="hidden" name="data[ItemImage][][primary]" value="0"/>
		</dd>
		<?php } ?>
		</dl>
	
	<?php } ?>
	

<input type="submit" value="Save" class="black-text button gray-background"/>


</form>
</div>
