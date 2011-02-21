<?php
	echo $html->css('jquery.fancybox');
	$javascript->link('jquery', false);
	$javascript->link('jquery.easing.1.3.js', false);
	$javascript->link('jquery.fancybox-1.2.1.pack.js', false);
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

<div class="gray-left-border summary">

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
				echo '<a href="/admin/item/grid/'. $item_details[0]['Item']['item_type_id'] .'/'. $item_details[0]['Item']['status'] .'/">'. $item_details[0]['ItemType']['name'] . '</a>';
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
<h3><?php echo $h3 ?> <?php echo $item_details[0]['Item']['name'] ?></h3>
</div>

<dl class="general">
<?php foreach ($item_details[0]['ItemImage'] as $i) { ?>
<?php if($i['filename'] !== '') { ?>
	<?php if($i['primary'] == '1' ) { ?>
	<dd>
		<img src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $main_settings)?>" />
		
	</dd>
	<?php } else { ?>
	<?php } ?>
<?php } ?>
<?php } ?>
</dd>
<dd>
<h5> Status: </h5>
<form action="/admin/item/update_status/<?php echo $item_details[0]['Item']['id'] ?>" method="post">
<select name="data[Item][status]" >
	<?php foreach($item_statuses as $s ) {?>
		<?php 
		if($s == 'Works in Progress') {
			$s_value = 'Unpublished';
		} else {
			$s_value = $s;
		}
		?>
		<?php if ($s == $status) { ?>
		<option selected="selected" value="<?php echo $s_value ?>"><?php echo $s ?></option>
		<?php } else {?>
		<option value="<?php echo $s_value ?>"><?php echo $s ?></option>
		<?php } ?>
	<?php } ?>
</select>
<input type="submit" value="Update" class="button gray-background black-text"/>
</form>
<dl class="actions">
<dd><a class="button gray-background black-text" href="/admin/item/details/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Details</a></dd>
<dd><a class="button gray-background black-text" href="/admin/item/image/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Images</a></dd>
<dd><a class="button gray-background black-text" href="/admin/item/email/<?php echo $item_details[0]['Item']['id'] ?>">Email Item</a></dd>

<dd><a target="_blank" class="button gray-background black-text" href="/item/details/<?php echo $item_details[0]['Item']['id'] ?>/print/">Print Item</a></dd>
</dl>

<p><em>Date Added : <?php echo $item_details[0]['Item']['publish_date']; ?> </em></p>
</dd>
<dd>
<?php foreach ($item_details[0]['InventoryQuantity'] as $inventoryQuantity): ?>
	<?php echo $locationsNames[$inventoryQuantity['location']]['longName'];?>&nbsp;:&nbsp;<?php echo $inventoryQuantity['quantity']; ?><br/>
<?php endforeach; ?>
</dd>
</dl>

<div class="notesPlace">
<div class="header">Notes</div>
<div class="filter">
<select name="noteFilter">
<option value="newest">newest</option>
<option value="oldest">oldest</option>
<?php foreach ($noteStatuses as $id => $text): ?>
<option value="<?php echo $id; ?>"><?php echo $text; ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="notesForm">
<form id="itemNotes" method="post" action="/admin/item/save_note/">
<input type="hidden" name="data[Note][item]" value="<?php echo $item_details[0]['Item']['id']; ?>" />
<textarea name="data[Note][note]">Start Typing...</textarea>
<div class="hiddenFields">
	<div class="subheading">To:</div>
		<div>
		<?php foreach ($locationsNames as $locationId => $locationNamesRecord): ?>
			<input type="checkbox" name="data[Note][to][]" value="<?php echo $locationId; ?>" /><?php echo $locationNamesRecord['shortName']; ?>
		<?php endforeach; ?>
	</div>
	<div class="subheading">Status:</div>
	<div>
		<select name="data[Note][status]">
			<?php foreach ($noteStatuses as $id => $text): ?>
				<option value="<?php echo $id; ?>"><?php echo $text; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type="submit" value="Save" class="button gray-background black-text"/>
</div>
</form>
</div>
<div class="notesList">
	<?php foreach ($itemNotes as $note): ?>
		<div class="note">
			<p><?php echo date('D, j M \a\t g:ia', strtotime($note['Note']['created'])); ?></p>
			<p><?php echo $note['Note']['note']; ?></p>
			<div class="bottomMenu"><a href="#">edit</a><input type="hidden" value="<?php echo $note['Note']['id']; ?>" name="id" />&nbsp;|&nbsp;<a href="#">comments(<?php echo count($note['Comments']); ?>)</a></div>
			<div class="commentForm">
				<?php foreach ($note['Comments'] as $comment): ?>
					<div>
						<p><?php echo date('D, j M \a\t g:ia', strtotime($comment['created'])); ?></p>
						<p><?php echo $comment['note']; ?></p>
						<div class="bottomMenu"><a href="#">edit</a><input type="hidden" value="<?php echo $comment['id']; ?>" name="id" /></div>
					</div>
				<?php endforeach; ?>
				<form method="post" action="/admin/item/save_note/">
				<input type="hidden" name="data[Note][item]" value="<?php echo $item_details[0]['Item']['id']; ?>" />
				<input type="hidden" name="data[Note][parent]" value="<?php echo $note['Note']['id']; ?>" />
				<textarea name="data[Note][note]">Start Typing...</textarea>
				<div class="hiddenFields">
					<div class="subheading">To:</div>
						<div>
						<?php foreach ($locationsNames as $locationId => $locationNamesRecord): ?>
							<input type="checkbox" name="data[Note][to][]" value="<?php echo $locationId; ?>" /><?php echo $locationNamesRecord['shortName']; ?>
						<?php endforeach; ?>
					</div>
					<div class="subheading">Status:</div>
					<div>
						<select name="data[Note][status]">
							<?php foreach ($noteStatuses as $id => $text): ?>
								<option value="<?php echo $id; ?>"><?php echo $text; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<input type="submit" value="Save" class="button gray-background black-text"/>
				</div>
				</form>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<div class="paginator">
<?php $paginator->options(array('url' => $this->passedArgs));?>
<?php echo $paginator->numbers(array('separator' => ' ')); ?></div>
</div>

<h4>Product Details</h4>

<div class="product-details">
	<div class="column">
		<dl>
		<dt><label>Status:</label></dt>
		<dd><?php echo $item_details[0]['Item']['status']; ?></dd>
		</dl>

		<dl>
		<dt><label>Item Name:</label></dt>
		<dd><?php echo $item_details[0]['Item']['name']; ?></dd>
		</dl>

		<dl>
		<dt><label>Category:</label></dt>
		<dd><?php echo $item_details[0]['ItemType']['name']; ?></dd>
		</dl>

		<dl>
		<dt><label>Subcategory:</label></dt>
		<dd><?php echo $item_details[0]['ItemCategory']['name']; ?></dd>
		</dl>

		<?php if ($item_details[0]['Item']['item_category_id'] !== '1') {?>
		<dl>
		<dt><label>Addons:</label></dt>
			<dd>
				<?php 
				if (count($addons) != 0 ) { 
				foreach($addons as $a) {
					echo $a;
				}
				} else { 
					echo 'None';
				}
				?>
			</dd>
		</dl>
		<?php } ?>
		
		<dl>
		<dt><label>Condition:</label></dt>
		<dd><?php echo $item_details[0]['Item']['condition'] ?></dd>
		</dl>
		
		<dl>
		<dt><label>Price:</label></dt>
		<dd><?php if(isset($item_details[0]['ItemVariation'][0]['price'])) { echo '$'. $fieldformatting->price_formatting($item_details[0]['ItemVariation'][0]['price']) ; }?></dd>
		</dl>
		
	</div>
	<div class="column">
	
	<dl>
	<dt><label>Measurements:</label></dt>
	<dd>
		
		<?php if ($item_details[0]['Item']['height'] !== null && $item_details[0]['Item']['height'] !== '') { ?>
			Height: <?php echo $item_details[0]['Item']['height'] . $item_details[0]['Item']['units']?>
			<br/>
		<?php } ?>
		
		<?php if ($item_details[0]['Item']['height_2'] !== null && $item_details[0]['Item']['height_2'] !== '') { ?>
			Height 2: <?php echo $item_details[0]['Item']['height_2'] . $item_details[0]['Item']['units'] ?>
			<br/>
		<?php } ?>

		<?php if ($item_details[0]['Item']['width'] !== null && $item_details[0]['Item']['width'] !== '') { ?>
			Width: <?php echo $item_details[0]['Item']['width'] . $item_details[0]['Item']['units'] ?>
			<br/>
		<?php } ?>
		
		
		<?php if ($item_details[0]['Item']['depth'] !== null && $item_details[0]['Item']['depth'] !== '') { ?>
			Depth: <?php echo $item_details[0]['Item']['depth'] . $item_details[0]['Item']['units'] ?>
			<br/>
		<?php } ?>
		
		<?php if ($item_details[0]['Item']['diameter'] !== null && $item_details[0]['Item']['diameter'] !== '') { ?>
			Diameter: <?php echo $item_details[0]['Item']['diameter'] . $item_details[0]['Item']['units'] ?>
			<br/>
		<?php } ?>
	</dd>
	</dl>
	<dl>
	<dt><label>Specifications:</label></dt>
	<dd>
		
		<?php if ($item_details[0]['Item']['materials_and_techniques'] !== null && $item_details[0]['Item']['materials_and_techniques'] !== '') { ?>
			Material/Techniques: <?php echo $item_details[0]['Item']['materials_and_techniques'] ?>
			<br/>
		<?php } ?>
		
		<?php if ($item_details[0]['Item']['creator'] !== null && $item_details[0]['Item']['creator'] !== '') { ?>
			Creator: <?php echo $item_details[0]['Item']['creator'] ?>
			<br/>
		<?php } ?>
		
		<?php if ($item_details[0]['Item']['country_of_origin'] !== null && $item_details[0]['Item']['country_of_origin'] !== '') { ?>
			Origin: <?php echo $item_details[0]['Item']['country_of_origin'] ?>
			<br/>
		<?php } ?>
		
		<?php if ($item_details[0]['Item']['period'] !== null && $item_details[0]['Item']['period'] !== '') { ?>
			Period: <?php echo $item_details[0]['Item']['period'] ?>
			<br/>
		<?php } ?>
	</dd>
	</dl>
		<dl>
		<dt><label>Contact:</label></dt>
		<?php
			$contact = preg_split('/\n/',$item_details[0]['InventoryLocation']['contact']);
			foreach($contact as $c) {
				$formatted_contact[] = '<li>'. $c .'</li>';
			}
			
			$contact = '<ul>' . implode($formatted_contact) . '</ul>';
		?>
		<dd><?php echo $contact ?></dd>
		</dl>
		<dl>
		<dt><label>Inventory Location:</label></dt>
		<dd><?php echo $item_details[0]['InventoryLocation']['name']; ?></dd>
		</dl>
	</div>
	<p><a class="button gray-background black-text" href="/admin/item/details/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Details</a></p>
</div>

<h4>Product Images</h4>
<div class="product-images">
<dl>
<?php foreach ($item_details[0]['ItemImage'] as $i) { ?>
<?php if($i['filename'] !== '') { ?>
	<dd>
		<a class="image" href="../../../../../files/<?php echo $i['filename'] ?>"><img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $thumb_settings)?>" /></a>
		
	</dd>
<?php } ?>
<?php } ?>
</dl>
<p><a class="button gray-background black-text" href="/admin/item/image/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Images</a></p>
</div>

<?php if ($item_details[0]['Item']['item_category_id'] == '2') {?>

<h4>Product Variations</h4>

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
			<td>$<?php echo $i['price'] ?></td>
		</tr>
	<?php } ?>
	</table>
	<?php } else { ?>
		<p><em>There are currently no variations for this item</em></p>
	<?php } ?>

<p><a class="button gray-background black-text" href="/admin/item/variations/edit/<?php echo $item_details[0]['Item']['id'] ?>">Edit Variations</a></p>

<?php } ?>

</div>
