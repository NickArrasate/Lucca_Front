<?php
	echo $html->css('grid');
	$this->pageTitle = 'Lucca Antiques - '. $current_item_type_name[$current_item_type_id]; 
//	debug($count);
?>
<div class="sgrid8">
	<div class="item-category">
	
		<dl class="nav">
			
			<?php if ($current_item_category == 'all') { ?>
				<dd class="active">
			<?php } else { ?>
				<dd>
			<?php } ?>
			
			<span><a href="/item/grid/<?php echo $current_item_type_id ?>/all/">All <?php echo $current_item_type_name[$current_item_type_id] ?></a></dd>
			
			<?php
				$item_categories_count = sizeof($item_categories);
				
				for ($i=0; $i < $item_categories_count; $i++) {
				
					if(($i + 1) == $current_item_category ) { ?>
					
						<dd class="active">
					<?php } else { ?>
						<dd>
					<?php } ?>
				<span><a href="/item/grid/<?php echo $current_item_type_id ?>/<?php echo $i + 1 ?>/"><?php echo $item_categories[$i + 1] ?></a></span></dd>
			<?php } ?>
		</dl>
		
		
		<div class="wrapper">
			<dl class="breadcrumbs">
				<dd>
				<a href="/">Home</a> > 
				<?php 
					echo '<span><a href="/item/grid/'. $this->params['pass'][0] .'/all/">'. $breadcrumbs[0] .'</a> > </span>';
					echo '<span><a href="/item/grid/'. $this->params['pass'][0] .'/'. $this->params['pass'][1] . '">'. $breadcrumbs[1] .' </a></span>';
				?>
				</dd>
				<?php if (isset($all_items)) { ?>
				<dd class="pagination">Viewing all <?php ?>| <a class="underline" href="/item/grid/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/">View 8 per page</a></dd>
				<?php } else {  ?>
				<?php if (isset($count) && $count > 8) { ?>
				<dd class="pagination">
					<a class="underline" href="/item/grid/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/all/">View All</a>
				</dd>
				<?php } ?>
				<?php } ?>
				<?php if(!isset($all_items)) {?>
				<dd class="pagination">
					<dl>
					<dd>
					<?php 
					$paginator->options(array('url' => $this->passedArgs));
					?>
					<span><?php echo $paginator->prev('<< '); ?></span>
					<?php echo $paginator->numbers($options = (array( 'separator' => ''))); ?>
					<span> <?php echo $paginator->next(' >>'); ?> </span>
					</dd>
					</dl>
				</dd>
				<?php } ?>				
			</dl>
			
			<p class="category-summary"><?php echo $category_summary ?></p>
			
			<div class="item-category-border"></div>
			
			<dl class="results">
			
			<?php if (count($items) > 0) {?>
			
			<?php
				$last_item = end($items);
			?>
			
			<?php foreach($items as $item) { ?>
			
			
			
				<?php foreach ($item as $i) { ?>
				<dd>
					<ul>
						<li>
							<a href="/item/details/<?php echo $i['Item']['id']  ?>/">
								<?php foreach($i['ItemImage'] as $o) {?>
								<?php if($o['primary'] == 1) {?> 
								
								<?php $settings = array('w'=>142,'h'=>142,'crop'=>1); ?>
								<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'.$o['filename'], $settings)?>" />
								<?php } ?>
								<?php } ?>
							</a>
						</li>
						<li class="item-title"><a href="/item/details/<?php echo $i['Item']['id']  ?>/"><?php echo $i['Item']['name'] ?></a></li>
						<?php if($i['Item']['status'] == 'Sold') { ?>
							<li><span class="small-button gray-background red-text-border">SOLD</span></li>
						<?php } else { ?>
							<li class="item-price">$<?php echo $fieldformatting->price_formatting($i['ItemVariation'][0]['price']) ?></li>
						<?php } ?>
					</ul>
				</dd>
				<?php } ?>
				
			
			
				<?php if ( $item != $last_item) { ?>
					<dd class="no-margin"><div class="category-results-divider2"></div></dd>
				<?php }  ?>
			
			<?php } ?>
			<?php } else {?>
			<h3 class="notifications">No items found</h3>
			
			<?php } ?>
			</dl>
			<dl class="breadcrumbs">
				<dd>&#160;</dd>
				<?php if (isset($all_items)) { ?>
				<dd class="pagination">Viewing all <?php ?> | <a class="underline" href="/item/grid/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/">View 8 per page</a></dd>
				<?php } else {  ?>
				<?php if (isset($count) && $count > 8) { ?>
				<dd class="pagination">
					<a class="underline" href="/item/grid/<?php echo $this->params['pass'][0] ?>/<?php echo $this->params['pass'][1] ?>/all/">View All</a>
				</dd>
				<?php } ?>
				<?php } ?>
				<?php if(!isset($all_items)) {?>
				<dd class="pagination">
					<dl>
					<dd>
					<?php 
					$paginator->options(array('url' => $this->passedArgs));
					?>
					<span><?php echo $paginator->prev('<< '); ?></span>
					<?php echo $paginator->numbers($options = (array( 'separator' => ''))); ?>
					<span> <?php echo $paginator->next(' >>'); ?> </span>
					</dd>
					</dl>
				</dd>
				<?php } ?>	
			</dl>
			
		</div>
	</div>
</div>