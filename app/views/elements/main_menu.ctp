<div class="menu-block navbar navbar-default">
	<div class="navbar-header">
      		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-navbar">
        		<span class="sr-only">Toggle navigation</span>
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
      		</button>
    	</div>
	<div class="collapse navbar-collapse" id="menu-navbar">
	<ul class="menus nav navbar-nav">
		<?php foreach($item_types['base_types'] as $item):?>
			<li class="menu">
				<a href="/item/grid/<?php echo $item['id']; ?>/all/all" <?php if (isset($current_item_type_id)&&($current_item_type_id == $item['id'])) echo 'class="selected"'; ?> title="<?php echo $item['name'];?>"><?php echo $item['name']; ?></a>
			</li>
		<?php endforeach;?>
		<?php if(!empty($item_types['over_base_types'])):?>
		<li class="menu6">
			<div class="locations">
				<a href="#" title="Objects">Objects</a>
				<div class="pulldown">
					<ul class="submenus">
					<?php foreach($item_types['over_base_types'] as $item):?>
						<li class="submenu">
							<a href="/item/grid/<?php echo ($item['id']) ?>/all/all" title="<?php echo $item['name'];?>"><?php echo $item['name'] ?></a>
						</li>
					<?php endforeach;?>
					</ul>
				</div>
			</div>
		</li>
		<?php endif;?>
		<li class="menu">
			<div class="locations">
				<a href="/item/grid/all/all/all" <?php if (isset($current_item_type_id) && $current_item_type_id == 0) echo 'class="selected"'; ?> title="All Inventory">All Inventory</a>
				<div class="pulldown">
					<ul class="submenus">
						<li class="submenu1"><a href="/item/grid/all/all/1" title="">Los Angeles</a></li>
						<li class="submenu2"><a href="/item/grid/all/all/2" title="">New York</a></li>
						</ul>
					</div>
				</div>
			</li>
			<li class="search">
					<div class="searchform <?php echo (empty($searchString)) ? "disabled" : "enabled"; ?>">
						<?php echo $form->create('Search', array('url' => array('controller' => 'item', 'action' => 'search'))); ?>
							<?php echo $form->text('Search.item', array('value' => $searchString)); ?>
							<?php echo $form->label('Search.item', 'Search'); ?>
						<?php echo $form->end(); ?>
					</div>
			</li>
	</ul>
	</div>
</div>
