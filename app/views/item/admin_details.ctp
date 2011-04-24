<?php

	echo $html->css('jquery.fancybox');
	$javascript->link('jquery', false);
	$javascript->link('jquery.easing.1.3.js', false);
	$javascript->link('jquery.fancybox-1.2.1.pack.js', false);
	//if(isset($data)) {print_r($data);}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('table.stripe>tbody>tr:even').addClass("dark-background");

		$('a.image').fancybox();

	});
</script>

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

<div class="gray-left-border details">

	<div class="breadcrumbs">
			<?php
				foreach($admin_subnavigation as $a) {
					if($a['class'] == 'active') {
						echo '<a href="'. $a['link'] .'">'. $a['title'] . '</a>';
					}
				}
			?> >

			<?php if ($item_details[0]['ItemType']['name']!== null) {?>
			<?php
				echo '<a href="/admin/item/grid/'. $item_details[0]['Item']['item_type_id'] .'/'. $item_details[0]['Item']['status'] .'">'. $item_details[0]['ItemType']['name'] .'</a>';
			?> >
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
<?php if(!isset($item_details)) { ?>
<h3>Create New Item : Add Item Information</h3>
<?php } else { ?>
<h3>Edit Details : <?php echo $item_details[0]['Item']['name'] ?></h3>
<?php } ?>
</div>

<p class="required"><em>* required</em></p>
<div class="notifications">
<?php
if(isset($errors_item)) {
	foreach($errors_item as $ei) {
		echo $ei .'<br/>';
	}
}
if(isset($errors_item_variation)) {
	foreach($errors_item_variation as $eiv) {
		echo $eiv . '<br/>';
	}
}
if(isset($details_feedback_message)) {
	echo $details_feedback_message;
}
?>
</div>
<p class="notifications"></p>

<?php $large_settings = array('w'=>600,'crop'=>1); ?>

<dl class="item-detail-thumbs">
<dt>Image Thumbnails <a class="button-small gray-background black-text" href="/admin/item/image/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Images</a></dt>
<?php
foreach ($item_details[0]['ItemImage'] as $i) {
	if ($i['filename'] !== '') {
?>
<dd>

	<a class="image" href="../../../../../files/<?php echo $i['filename'] ?>"><img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $settings)?>" /></a>

</dd>
<?php
	}
}
?>
</dl>


<?php if(!isset($item_details)) { ?>
	<form action="/admin/item/save/" method="post" enctype="multipart/form-data">
	<p class="instructions">Enter in item information: </p>

	<input type="hidden" name="data[ItemVariation][primary]" value="1"/>
<?php } else { ?>
	<form action="/admin/item/update_details/<?php echo $item_details[0]['Item']['id'] ?>" method="post" enctype="multipart/form-data">
<?php } ?>
<dl class="column">
	<?php if(!isset($item_details)) { ?>
	<dd><label>SKU: *</label></dd>
	<dd><input type="text" name="data[ItemVariation][sku]" value="<?php if(isset($item_details)) { echo $item_details[0]['ItemVariation'][0]['sku']; } ?><?php if(isset($random_variation_id)) { echo $random_variation_id; } ?>"/></dd>
	<?php } ?>
	<?php if((isset($item_details)) && ($item_details[0]['Item']['status'] !== 'Unpublished')) { ?>
	<dd><label class="status">Status:</label></dd>
	<dd>
		<select name="data[Item][status]">
			<?php foreach($item_statuses as $s) { ?>
				<?php
					// TEMP
					if($s == 'Works in Progress') {
						$status = 'Unpublished';
					} else {
						$status = $s;
					}
				?>
				<?php if ($s == $item_details[0]['Item']['status'] ) { ?>
					<option selected="selected" value="<?php echo $status ?>"><?php echo $s ?></option>
				<?php } else { ?>
					<option value="<?php echo $status ?>"><?php echo $s ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</dd>
	<?php } ?>
	<dd><label>Item Name: *</label></dd>
	<dd><input type="text" name="data[Item][name]" value="<?php if(isset($data)) { echo $data['Item']['name']; } else {if(isset($item_details)) { echo $item_details[0]['Item']['name']; }} ?>"/></dd>
	<dd><label>Category: *</label></dd>
	<dd>
		<?php if (isset($item_details)) { ?>
		<select name="data[Item][item_type_id]">
			<?php foreach($item_type as $key => $value) { ?>
				<?php if ($key == $item_details[0]['ItemType']['id']) { ?>
					<option selected="selected" value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } else { ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } ?>
			<?php } ?>
		</select>
		<?php } else { ?>
			<select name="data[Item][item_type_id]">
			<?php foreach($item_type as $key => $value) { ?>
				<option value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php } ?>
			</select>
		<?php } ?>
	</dd>
	<dd><label>Subcategory: *</label></dd>
	<dd>
		<?php if (isset($item_details)){ ?>
		<select name="data[Item][item_category_id]">
			<?php foreach($item_category as $key => $value) {  ?>
				<?php if ($key == $item_details[0]['ItemCategory']['id']) { ?>
					<option selected="selected" value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } else {?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } ?>
			<?php } ?>
		</select>
		<?php } else { ?>
		<select name="data[Item][item_category_id]">
			<?php foreach($item_category as $key => $value) {  ?>
				<option value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php } ?>
		</select>
		<?php } ?>
	</dd>
	<?php if(isset($item_details) && $item_details[0]['Item']['item_category_id'] !== '1' ) { ?>
	<dd><label>Addons:</label></dd>
	<dd>
		<select name="data[Item][addon_id]">
			<option value="">None</option>
			<?php if (isset($item_details)) { ?>
			<?php foreach($addons as $key => $value) {  ?>
				<?php if ($key == $item_details[0]['Item']['addon_id']) { ?>
					<option selected="selected" value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } else {?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } ?>
			<?php } ?>
			<?php } else { ?>
				<?php foreach($addons as $key => $value) {  ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } ?>
			<?php } ?>
		</select>
	</dd>
	<?php } ?>
	<dd>
	</dd>
	<dd><label>Description:</label></dd>
	<dd>
		<textarea rows="5" cols="20" name="data[Item][description]"><?php if(isset($data)) { echo $data['Item']['description']; } else {if(isset($item_details)) { echo $item_details[0]['Item']['description']; }}?></textarea>
	</dd>
	<dd><label>Price: *</label></dd>
	<dd>
		<input type="text" name="data[ItemVariation][price]" value="<?php if(isset($data)) { echo $data['ItemVariation']['price']; } else {if(isset($item_details)) { echo $item_details[0]['ItemVariation'][0]['price']; }} ?>"/>
	</dd>

	<?php if((isset($item_details) && ($item_details[0]['Item']['item_category_id'] == '3') )) {?>
	<dd><label>Quantity: </label></dd>
	<dd>
		<input type="text" name="data[ItemVariation][quantity]" value="<?php if(isset($data)) { echo $data['ItemVariation']['quantity']; } else {if(isset($item_details)) { echo $item_details[0]['ItemVariation'][0]['quantity']; }} ?>"/>
	</dd>
	<?php } ?>

	<dd>
		<input style="width:25px" type="checkbox" name="data[Item][lucca_original]" value="1" <?php if(isset($data['Item']['lucca_original']) && $data['Item']['lucca_original']) { echo 'checked="checked"'; } else {if(!isset($data) && isset($item_details[0]['Item']['lucca_original']) && $item_details[0]['Item']['lucca_original']) { echo 'checked="checked"'; }} ?> id="ItemLuccaOriginal"/><span style="font-weight:bold">Lucca Studio</span>
	</dd>

	<dd class="group-header"><label>Inventory Location</label></dd>
	<?php foreach ($inventory_location as $id => $shortName): ?>
		<dd class="little-input">
			<dl>
				<dd><label><?php echo $shortName; ?></label><input type="text" name="data[InventoryQuantity][<?php echo $id; ?>]" value="<?php if(isset($data) && isset($data['InventoryQuantity'][$id])) { echo $data['InventoryQuantity'][$id]; } else { if(isset($item_details) && isset($item_details[0]['InventoryQuantity'][$id])) { echo $item_details[0]['InventoryQuantity'][$id]; } } ?>"/></dd>
			</dl>
		</dd>
	<?php endforeach; ?>

</dl>
<dl class="column">
	<dd><label>Condition:</label></dd>
	<dd><input type="text" name="data[InventoryLocation][]" value="<?php if(isset($data)) { echo $data['Item']['condition']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['condition']; } } ?>"/></dd>

	<dd><label>Units of Measurement:</label></dd>
	<dd>
		<?php
			// TEMP
			$units = array(
				'in' => 'Inches',
				'cm' => 'Centimeters'

			);
		?>

		<?php if (isset($item_details)) { ?>
		<select name="data[Item][units]">
			<?php foreach($units as $key => $value) { ?>
				<?php if ($key == $item_details[0]['Item']['units']) { ?>
					<option selected="selected" value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } else { ?>
					<option value="<?php echo $key ?>"><?php echo $value ?></option>
				<?php } ?>
			<?php } ?>
		</select>
		<?php } else { ?>
			<select name="data[Item][units]">
			<?php foreach($item_type as $key => $value) { ?>
				<option value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php } ?>
			</select>
		<?php } ?>

	</dd>
	<dd class="short-input">
		<dl>
			<dd><label>Height:</label></dd>
			<dd><input type="text" name="data[Item][height]" value="<?php if(isset($data)) { echo $data['Item']['height']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['height']; } } ?>"/></dd>
		</dl>
	</dd>
	<dd class="short-input">
		<dl>
			<dd><label>Height 2:</label></dd>
			<dd><input type="text" name="data[Item][height_2]" value="<?php if(isset($data)) { echo $data['Item']['height_2']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['height_2']; } } ?>"/></dd>
		</dl>
	</dd>
	<dd class="short-input">
		<dl>
		<dd><label>Width:</label></dd>
		<dd><input type="text" name="data[Item][width]" value="<?php if(isset($data)) { echo $data['Item']['width']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['width']; } } ?>"/></dd>
		</dl>
	</dd>
	<dd class="short-input">
		<dl>
		<dd><label>Depth:</label></dd>
		<dd><input type="text" name="data[Item][depth]" value="<?php if(isset($data)) { echo $data['Item']['depth']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['depth']; } } ?>"/></dd>
		</dl>
	</dd>
	<dd class="short-input">
		<dl>
		<dd><label>Diameter:</label></dd>
		<dd><input type="text" name="data[Item][diameter]" value="<?php if(isset($data)) { echo $data['Item']['diameter']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['diameter']; } } ?>"/></dd>
		</dl>
	</dd>


	<dd><label>Material and Techniques:</label></dd>
	<dd><input type="text" name="data[Item][materials_and_techniques]" value="<?php if(isset($data)) { echo $data['Item']['materials_and_techniques']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['materials_and_techniques']; } } ?>"/></dd>
	<dd><label>Creator:</label></dd>
	<dd><input type="text" name="data[Item][creator]" value="<?php if(isset($data)) { echo $data['Item']['creator']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['creator']; } } ?>"/></dd>
	<dd><label>Country of Origin:</label></dd>
	<dd><input type="text" name="data[Item][country_of_origin]" value="<?php if(isset($data)) { echo $data['Item']['country_of_origin']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['country_of_origin']; } } ?>"/></dd>
	<dd><label>Period:</label></dd>
	<dd><input type="text" name="data[Item][period]" value="<?php if(isset($data)) { echo $data['Item']['period']; } else { if(isset($item_details)) { echo $item_details[0]['Item']['period']; } } ?>"/></dd>

</dl>

<?php if(isset($item_variations) && $item_details[0]['Item']['item_category_id'] == '2') { ?>

	<h4>Variations</h4>

	<?php if (count($item_variations) > 0) { ?>
	<table class="stripe">
		<tr>
			<th>SKU</th>
			<th>Variation</th>
			<th>Price</th>
		</tr>
		<?php foreach ($item_variations as $i) { ?>
		<tr class="<?php echo $i['id']?>">
			<td><?php echo $i['sku'] ?></td>
			<td><?php echo $i['name'] ?></td>
			<td>$<?php echo $fieldformatting->price_formatting($i['price']) ?></td>
		</tr>
	<?php } ?>
	</table>
	<?php } else { ?>
		<p><em>There are currently no variations for this item</em></p>
	<?php } ?>

	<p><a class="button gray-background black-text" href="/admin/item/variations/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Variations</a></p>

<?php } else { ?>
	<?php if(!isset($item_details)) { ?>
	<div class="create-variations">
	<p><em>For Antiques, leave blank:</em></p>
	<input type="hidden" name="data[AnotherItemVariation][sku]" value="<?php echo $another_random_variation_id ?>"/>
	<input type="hidden" name="data[AnotherItemVariation][primary]" value="0"/>
	<input type="hidden" name="data[AnotherItemVariation][item_id]" value="<?php echo $another_random_variation_id ?>"/>
	<dl class="column">
		<dd><label>Variation</label></dd>
		<dd><input type="text" name="data[AnotherItemVariation][name]"/></dd>
		<dd><label># Available*</label></dd>
		<dd><input type="text" name="data[AnotherItemVariation][quantity]"/></dd>
		<dd><label>Price *</label></dd>
		<dd><input type="text" name="data[AnotherItemVariation][price]"/></dd>
		<dd><em>*Only use if item is limited in quantity</em></dd>
	</dl>
	</div>
	<?php } ?>
<?php } ?>

<dl class="actions">
<?php if(!isset($item_details)) { ?>
<dd><input type="submit" name="submit" value="Save" class="gray-background button black-text"/></dd>
<?php } else { ?>
<dd><input type="submit" name="submit" value="Save Changes" class="gray-background button black-text"/></dd>
<dd><a target="_blank" class="black-text button gray-background" href="/item/details/<?php echo $item_details[0]['Item']['id'] ?>">Preview</a></dd>
<?php } ?>
<?php //} ?>
</dl>

</form>

</div>
<?php

//debug($item_details);
