<?php
	//$javascript->link('jquery', false);
	//$javascript->link('jquery.ui', false);
	//$javascript->link('jquery.cookie', false);
	//$javascript->link('item_admin_grid', false);

	//debug($item_types);
?>

<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>Are you sure you want to delete this item?</strong><p></div>

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

<div class="gray-left-border grid">
	<div class="breadcrumbs">
	<?php
		foreach($admin_subnavigation as $a) {
			if($a['class'] == 'active') {
				echo '<a href="'. $a['link'] .'">'. $a['title'] . '</a>';
			}
		}
	?> >

	<?php
	foreach($navigation as $n) {
		if($n['class'] == 'active') {
			echo '<a href="'. $n['link'] .'">'. $n['title'] . '</a>';
		}
	}
	?>
	<?php
	if(isset($type_id) && $type_id !== 'all') {
		echo '> '. $item_types[$type_id];
	}
	?>

	</div>
	<form action="/admin/item/grid/" method="post">
	<dl class="subheader">
		<dd>
		<h3><?php echo $h3 ?></h3>
		<?php if($status !== 'Unsorted') {?>
		<select id="select_item_type" name="data[ItemType][id]">
		<?php foreach($item_types as $key => $value) {?>
			<?php if($key == $type_id) { ?>
				<option selected="selected" value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php } else { ?>
				<option value="<?php echo $key ?>"><?php echo $value ?></option>
			<?php } ?>
		<?php } ?>
		</select>
		<?php } ?>
		<select id="filterMenu" name="data[filterMenu]">
			<option value=""> -- sort by -- </option>
			<?php foreach ($filterMenu as $key => $name): ?>
			<option value="<?php echo $key; ?>" <?php echo (($selectedFilter == $key) ? 'selected="selected"' : "" ); ?>><?php echo $name; ?></option>
			<?php endforeach; ?>
		</select>
		</dd>
		<?php if(!isset($all_items) ) { ?>
		<dd>
			<?php if($count > 8) { ?>
			<div class="pagination">
			<a class="underline" href="/admin/item/grid/<?php echo $type_id ?>/<?php echo $status ?>/all/<?php echo ($selectedFilter ? 'filter:'.$selectedFilter: ''); ?>">View All</a>
			</div>
			<?php } ?>
			<div class="pagination">
			<dl>
			<?php
			$paginator->options(array('url' => $this->passedArgs));
			?>
			<dd><?php echo $paginator->prev('<<'); ?></dd>
			<?php echo '<dd>' . $paginator->numbers() . '</dd>'; ?>
			<dd><?php echo $paginator->next('>>'); ?> </dd>
			</dl>
			</div>
		</dd>
		<?php } else { ?>

			<div class="pagination"><span>Viewing all <?php echo $status ?> <?php echo $item_types[$type_id]?></span> <a class="underline" href="/admin/item/grid/<?php echo $type_id ?>/<?php echo $status ?>/<?php echo ($selectedFilter ? 'filter:'.$selectedFilter: ''); ?>">View 8 per page</a></div>

		<?php } ?>

	</dl>

	<?php if(count($chunked_items) > 0) {?>
	<table>
	<?php foreach ($chunked_items as $unpublished_items) { ?>
		<tr <?php if($unpublished_items == end($chunked_items)) { echo 'class="last"'; }?>>
	<?php foreach($unpublished_items as $u) { ?>
			<td>
				<dl id="<?php echo $u['Item']['id'] ?>">

					<?php foreach ($u['ItemImage'] as $i) { ?>
					<?php if($i['primary'] == '1') { ?>
						<?php if(isset($all_items)) { ?>
							<dd><a href="/admin/item/summary/<?php echo $u['Item']['id'] ?>">

							<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $settings)?>" />


							</a></dd>
						<?php } else { ?>
							<dd><a href="/admin/item/summary/<?php echo $u['Item']['id'] ?>">

								<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $settings)?>" />

							</a></dd>
						<?php } ?>
					<?php }?>
					<?php } ?>
					<dt><a href="/admin/item/summary/<?php echo $u['Item']['id'] ?>"><?php echo $u['Item']['name'] ?></a></dt>
					<dd class="price"><?php if (isset($u['ItemVariation'][0]['price'])) { echo '$' . $fieldformatting->price_formatting($u['ItemVariation'][0]['price']); } ?></dd>
					<dd class="end-info-block">
						<?php if (!empty($u['InventoryQuantity'])): ?>
							<?php if (count($u['InventoryQuantity']) > 1 || $u['Item']['lucca_original'] == 1): ?>
								<?php
									$displayInformation = array();
									foreach ($u['InventoryQuantity'] as $InventoryQuantity) {
										array_push($displayInformation, $locationsNames[$InventoryQuantity['location']]['shortName'].':&nbsp;'.(($InventoryQuantity['quantity'] == 0 && $u['Item']['lucca_original'] == 1) ? '<font color="red">'.$InventoryQuantity['quantity'].'</font>' : $InventoryQuantity['quantity']));
									}
								?>
								<?php echo implode('&nbsp;|&nbsp;', $displayInformation); ?>
							<?php else: ?>
								<?php echo $locationsNames[$u['InventoryQuantity'][0]['location']]['longName']; ?>
								<?php if ($u['InventoryQuantity'][0]['quantity'] > 1): ?>
									:&nbsp;<?php echo ($u['InventoryQuantity'][0]['quantity'] == 0 && $u['Item']['lucca_original'] == 1) ? '<font color="red">'.$u['InventoryQuantity'][0]['quantity'].'</font>' : $u['InventoryQuantity'][0]['quantity']; ?>
								<?php else: ?>
									<span class="hidden-inventory-quantity">
										:&nbsp;<?php echo ($u['InventoryQuantity'][0]['quantity'] == 0 && $u['Item']['lucca_original'] == 1) ? '<font color="red">'.$u['InventoryQuantity'][0]['quantity'].'</font>' : $u['InventoryQuantity'][0]['quantity']; ?>
									</span>
								<?php endif; ?>
							<?php endif; ?>
						<?php endif; ?>
					</dd>
					<dd>-- <a href="/admin/item/summary/<?php echo $u['Item']['id'] ?>">View Summary</a></dd>
					<dd>-- <a href="/admin/item/image/edit/<?php echo $u['Item']['id'] ?>">Edit Images</a></dd>
					<dd>-- <a href="/admin/item/details/edit/<?php echo $u['Item']['id'] ?>">Edit Item Details</a></dd>
					<?php if($status == 'Unpublished') { ?>
					<dd>-- <a target="_blank" href="/item/details/<?php echo $u['Item']['id'] ?>">Preview</a></dd>
					<?php } ?>
					<dd>-- <a href="/admin/item/summary/<?php echo $u['Item']['id'] ?>">Change Status</a></dd>

					<dd>-- <a href="/admin/item/email/<?php echo $u['Item']['id'] ?>">
Email</a></dd>

					<?php if($status == 'Unpublished') { ?>
					<dd>--
<a href="/admin/item/publish/<?php echo $u['Item']['id'] ?>/<?php echo $u['Item']['item_type_id'] ?>/<?php echo $status ?>">Publish</a></dd>
					<?php }?>
					<?php if($status !== 'Unsorted') { ?>
					<dd>-- <a class="delete-item" href="/admin/item/delete/<?php echo $u['Item']['id'] ?>/<?php echo $u['Item']['item_type_id'] ?>/<?php echo $status ?>">Delete</a></dd>
					<?php } ?>
					<?php if($status == 'Unsorted') { ?>
					<dd>-- <a class="delete-item" href="/admin/item/delete/<?php echo $u['Item']['id'] ?>/all/<?php echo $status ?>">Delete</a></dd>
					<?php } ?>
				</dl>
			</td>
	<?php } ?>
	</tr>
	<?php } ?>
	</table>
	<?php } else { ?>
		<h3 class="notifications">No items found</h3>
	<?php } ?>
</div>
