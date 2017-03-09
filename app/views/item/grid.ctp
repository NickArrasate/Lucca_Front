<?php
	echo $this->element('versioned_css', array('files' => 'grid'));
	$this->pageTitle = 'Lucca Antiques - '. (isset($current_item_type_name[$current_item_type_id]) ? $current_item_type_name[$current_item_type_id] : $current_item_type_id);
?>
<div class="item-category row">
	<dl class="menu-nav col-xs-3 col-md-2">
		<!-- Shown only if not All Inventory -->
		<div class="first-menu-nav">
		<?php if ($current_item_type_id != 'all') { ?>
			<dd <?php if ($current_item_category == 'all') { echo 'class="active"'; } ?>>
				<span><a href="/item/grid/<?php echo $current_item_type_id ?>/all/<?php echo $inventory_location; ?>/">All <?php echo $current_item_type_name[$current_item_type_id] ?></a>
			</dd>

			<?php
			foreach ($item_categories as $id => $category) {
				# Skip if it's the "CUSTOM" category
				$skip = json_decode(SKIP_CATS,true);
				if(in_array($id,$skip)){continue;};
				if($current_item_category == $id ) { ?>

					<dd class="active">
				<?php } else { ?>
					<dd>
				<?php } ?>
			<span><a href="/item/grid/<?php echo $current_item_type_id ?>/<?php echo $id ?>/"><?php echo $category ?></a></span></dd>
			<?php } ?>

			<br />
			<div style="border-top:1px dashed #ccc; width:130px;">&nbsp;</div>
		<?php } ?>
		</div>

		<div class="second-menu-nav">
			<dd <?php if ($inventory_location == 'all') { echo 'class="active"'; } ?>>
				<span><a href="<?php echo Router::url('/', true); ?>item/<?php echo $currentAction?>/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/all/<?php echo (empty($searchString)) ? "" : "search:" . $searchString . '/' ;?>">All Locations</a>
			</dd>
			<?php foreach($list_location_menu as $location_id => $display_name) { ?>
                <?php if($location_id == "3"){ continue; } ?>
				<dd <?php if ($inventory_location == $location_id) { echo 'class="active"'; } ?>>
					<span><a href="<?php echo Router::url('/', true); ?>item/<?php echo $currentAction?>/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/<?php echo $location_id; ?>/<?php echo (empty($searchString)) ? "" : "search:" . $searchString . '/' ;?>"><?php echo $display_name; ?></a>
				</dd>
			<?php } ?>
	</dl>


	<div class="wrapper col-xs-9 col-md-10">
		<dl class="breadcrumbs hidden-xs">
			<dd class="breadcrumb-nav">
				<a href="/">Home</a> >
				<?php
					echo '<span><a href="/item/grid/'. $this->params['pass'][0] .'/all/all">'. $breadcrumbs[0] .'</a> > </span>';
					echo '<span><a href="/item/grid/'. $this->params['pass'][0] .'/'. $this->params['pass'][1] . '/all">'. $breadcrumbs[1] .' </a></span>';
				?>
			</dd>
			<div class="breadcrumb-pagination">
				<?php if(!isset($all_items)) {?>
				<dd class="pagination">
					<dl>
					<dd>
					<?php
					$paginator->options(array('url' => array_merge(array('action' => $currentAction), $this->passedArgs)));
					?>
					<span><?php echo $paginator->prev('<< previous '); ?></span>
					<?php echo $paginator->numbers($options = (array( 'separator' => ''))); ?>
					<span> <?php echo $paginator->next(' next >>'); ?> </span>
					</dd>
					</dl>
				</dd>
				<?php } ?>

				<?php if (isset($all_items)) { ?>
				<dd class="pagination">Viewing all <?php ?>| <a class="underline" href="<?php echo Router::url('/', true); ?>item/<?php echo $currentAction; ?>/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/<?php echo $this->params['pass'][2]; ?>/<?php echo (empty($searchString)) ? "" : "search:" . $searchString . '/' ;?>">View 8 per page</a></dd>
				<?php } else {  ?>
				<?php if (isset($count) && $count > 8) { ?>
				<dd class="pagination">
				<a class="underline" href="<?php echo Router::url('/', true); ?>item/<?php echo $currentAction?>/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/<?php echo $this->params['pass'][2]; ?>/all/<?php echo (empty($searchString)) ? "" : "search:" . $searchString . '/' ;?>">View All</a>
				</dd>
				<?php } ?>
				<?php } ?>
			</div>
		</dl>

		<p class="category-summary"><?php echo $category_summary ?></p>

		<div class="item-category-border"></div>

		<dl class="results row">
		<?php if (count($items) > 0) {?>

		<?php
			$last_item = end($items);
			$items_count = 0;
		?>

		<?php

		foreach($items as $item) { ?>
			<?php foreach ($item as $i) { ?>
			<?php $items_count++; ?>
			<dd class="col-xs-6 col-sm-4 col-md-3 item">
				<ul>
					<li>
						<a href="/item/details/<?php echo $i['Item']['id']  ?>/">
							<?php foreach($i['ItemImage'] as $o) {?>
							<?php if($o['primary'] == 1) {?>

							<?php $settings = array('w'=>142,'h'=>142,'canvas-color'=>"#ffffff"); ?>
								<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'.$o['filename'], $settings)?>" />
              <!-- <img src="<? echo '/files/'.$o['filename']?>" width="142" /> -->
							<?php } ?>
							<?php } ?>
						</a>
					</li>
					<li class="item-title"><a href="/item/details/<?php echo $i['Item']['id']  ?>/"><?php echo $i['Item']['name'] ?></a></li>
					<?php if (!empty($i['Item']['fid'])): ?>
						<li class="item-title" style="color: #534741;">ID: <?php echo $i['Item']['fid']; ?></li>
					<?php endif; ?>
					<?php if($i['Item']['status'] == 'Sold') { ?>
						<li><span class="small-button gray-background red-text-border">SOLD</span></li>
					<?php } else { ?>
						<?php if($isTrader || $isUser){ ?>
							<li class="item-price">$<?php echo $fieldformatting->price_formatting($i['ItemVariation'][0]['price']) ?></li>
						<?php } ?>
					<?php } ?>
				</ul>
			</dd>

			<?php if ($items_count % 6 == 0 && $item != $last_item): ?>
				<dd class="no-margin col-xs-12 visible-sm"><div class="category-results-divider2"></div></dd>
			<?php endif; ?>
			<?php } ?>

			<?php if ( $item != $last_item) { ?>
				<dd class="no-margin col-xs-12 hidden-sm"><div class="category-results-divider2"></div></dd>
			<?php } ?>
		<?php } ?>
		<?php } else {?>
		<h3 class="notifications col-lg-12">No items found</h3>

		<?php } ?>
		</dl>
		<dl class="breadcrumbs">
			<dd>&#160;</dd>
			<div class="breadcrumb-pagination">
				<?php if(!isset($all_items)) {?>
				<dd class="pagination">
					<dl>
					<dd>
					<?php
					$paginator->options(array('url' => array_merge(array('action' => $currentAction), $this->passedArgs)));
					?>
					<span><?php echo $paginator->prev('<< previous '); ?></span>
					<?php echo $paginator->numbers($options = (array( 'separator' => ''))); ?>
					<span> <?php echo $paginator->next(' next >>'); ?> </span>
					</dd>
					</dl>
				</dd>
				<?php } ?>

				<?php if (isset($all_items)) { ?>
				<dd class="pagination">Viewing all <?php ?>| <a class="underline" href="<?php echo Router::url('/', true); ?>item/<?php echo $currentAction; ?>/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/<?php echo $this->params['pass'][2]; ?>/<?php echo (empty($searchString)) ? "" : "search:" . $searchString . '/' ;?>">View 8 per page</a></dd>
				<?php } else {  ?>
				<?php if (isset($count) && $count > 8) { ?>
				<dd class="pagination">
				<a class="underline" href="<?php echo Router::url('/', true); ?>item/<?php echo $currentAction?>/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/<?php echo $this->params['pass'][2]; ?>/all/<?php echo (empty($searchString)) ? "" : "search:" . $searchString . '/' ;?>">View All</a>
				</dd>
				<?php } ?>
				<?php } ?>
			</div>
		</dl>

	</div>
	<dd class="no-margin col-xs-12"><div class="category-results-divider-bottom"></div></dd>
</div>
