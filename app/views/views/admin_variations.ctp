<?php
	$javascript->link('jquery', false);
	$javascript->link('jquery.ui', false);
	$javascript->link('jquery.cookie', false);
	$javascript->link('item_admin_variations', false);
	
?>


<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>Are you sure you want to delete this variation?</strong><p></div>

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

<div class="gray-left-border edit-variations">

<div class="breadcrumbs">
		<?php
			foreach($admin_subnavigation as $a) {
				if($a['class'] == 'active') {
					echo '<a href="'. $a['link'] .'">'. $a['title'] . '</a>';
				}
			}
		?> >
		
		<?php if ($item_type!== null) {?>
		<?php
				foreach($item_type as $t) { echo $t; } 
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
<?php 
	echo '<h3>' . $h3 . '</h3>';
?>
</div>
<div class="notifications">
<?php 
if(isset($errors_item_variation)) {
	foreach($errors_item_variation as $eiv) {
		echo $eiv . '<br/>';
	}
} 
?>
</div>
<div class="summary">
<dl>
	<dt>Item Name</dt>
	<dd><?php echo $item[0]['Item']['name'] ?></dd>
</dl>

<dl>
	<dt>Item Type</dt>
	<dd><?php foreach($item_type as $t) { echo $t; } ?></dd>
</dl>

<dl>
	<dt>Item Category</dt>
	<dd><?php foreach($item_category as $c) { echo $c; } ?></dd>
</dl>

<dl>
	<dt>Description</dt>
	<dd><?php echo $item[0]['Item']['description'] ?></dd>
</dl>
</div>

<h4>Variations:</h4>

<?php if (count($item_variations) > 0) { ?>
<form action="/admin/item/variations/save/<?php echo $item_id ?>" method="post">
<table class="stripe existing-variations">
<tr>
<th>SKU</th>
<th>Variation</th>
<th>Price</th>
<th>&#160;</th>
</tr>
<?php foreach ($item_variations as $i) { ?>
<tr class="<?php echo $i['ItemVariation']['id']?>">
<td><input type="hidden" name="data[ItemVariation][id][]" value="<?php echo $i['ItemVariation']['id'] ?>"/><?php echo $i['ItemVariation']['sku'] ?></td>
<td><input type="text" class="name" name="data[ItemVariation][name][]" value="<?php echo $i['ItemVariation']['name'] ?>"/></td>
<td><input type="text" class="price" name="data[ItemVariation][price][]" value="<?php echo $i['ItemVariation']['price'] ?>"/></td>
<?php if ($i['ItemVariation']['primary'] == 0) {?>
<td><span class="button gray-background black-text"><a class="delete-variation" href="/admin/item/variations/delete/<?php echo $item_id ?>/<?php echo $i['ItemVariation']['id'] ?>">Delete</a></span></td>
<?php } ?>
</tr>
<?php } ?>
</table>
<input type="submit" value="Save these Variations" class="button gray-background black-text"/>
</form>

<?php } else { ?>
	<p><em>There are currently no variations for this item</em></p>
<?php } ?>
	
<h4>Add Variation</h4>
<form action="/admin/item/variations/add/<?php echo $item_id ?>" method="post">
<input type="hidden" name="data[ItemVariation][primary]" value="0"/>
<input type="hidden" name="data[ItemVariation][item_id]" value="<?php echo $item_id ?>"/>

<div class="add-variations">
	<dl>
	<dt>SKU:</dt>
	<dd><input type="text" name="data[ItemVariation][sku]" value="<?php echo $unique_sku ?>"></dd>
	</dl>

	<dl>
	<dt>Variation:</dt>
	<dd><input type="text" name="data[ItemVariation][name]"></dd>
	</dl>

	<dl>
	<dt>Price</dt>
	<dd><input type="text" name="data[ItemVariation][price]"></dd>
	</dl>

</div>
<input class="button gray-background black-text" type="submit" value="Add this Variation">
</form>
</div>
