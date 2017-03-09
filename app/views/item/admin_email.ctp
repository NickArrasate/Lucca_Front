<?php
	echo $html->css('jquery.fancybox');
	$javascript->link('jquery', false);
	$javascript->link('jquery.easing.1.3.js', false);
	$javascript->link('jquery.fancybox-1.2.1.pack.js', false);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('a.image').fancybox();
	});
</script>

<dl id="item_statuses">
<?php
	if($item_details[0]['Item']['status'] == 'Unpublished') {
		$status = 'Works in Progress';
	} else {
		$status = $item_details[0]['Item']['status'];
	}
	foreach($navigation as $n) {
		if($n['title'] == $status) {
			echo '<dd><a class="active" href="'. $n['link'] .'">'. $n['title'] .' ('. $n['count'] .')</a></dd>';
		} elseif($n['title'] == 'Create New Product') {
			echo '<dd><a class="'. $n['class'] .'" href="'. $n['link'] .'">'. $n['title'] .'</a></dd>';
		} else {
			echo '<dd><a class="" href="'. $n['link'] .'">'. $n['title'] .' ('. $n['count'] .')</a></dd>';
		}

		
	} 
?>
</dl>
<div class="gray-left-border email-item">
	<div class="breadcrumbs">
		<?php
			foreach($admin_subnavigation as $a) {
				if($a['class'] == 'active') {
					echo '<a href="'. $a['link'] .'">'. $a['title'] . '</a>';
				}
			}
		?> >
		
		<?php if ($itemtype !== null) {?>
		<?php
				echo '<a href="/admin/item/grid/'. $item_details[0]['Item']['item_type_id'] .'/'. $item_details[0]['Item']['status'] .'/">'. $itemtype . '</a>';
			?> >
		<?php } ?>
		
		<?php
		foreach($navigation as $n) {
			if($n['class'] == 'active') {
				echo '<a href="'. $n['link'] .'">'. $n['title'] . '</a>';
			}
		}
		?>
		> Email Item
	
	</div>
	
<div class="subheader">
<h3>Email Item : <?php echo $item_details[0]['Item']['name'] ?></h3>
</div>
<p class="notifications">
<?php
	if(isset($email_result)) {
		foreach($email_result as $er) {
			echo $er . '<br/>';
		}
	}
?>
<?php
	if(isset($email_item_errors)) {
		foreach($email_item_errors as $e) {
			echo $e . '<br/>';
		}
	}
?>

</p>

<form class="email-item" method="post" action="/admin/item/email_item/<?php echo $item_details[0]['Item']['id'] ?>">
<dl>
	<dt>Recipient's Email</dt>
	<dd><input type="text" name="data[EmailMessage][address]" value="<?php if(isset($temp_email_item)) { echo $temp_email_item['EmailMessage']['address'] ; }?>"/></dd>
</dl>

<dl>
	<dt>Subject</dt>
	<dd><input type="text" name="data[EmailMessage][subject]" value="<?php if(isset($temp_email_item)) { echo $temp_email_item['EmailMessage']['subject'] ; } else { echo 'Lucca Antiques : ' .  $item_details[0]['Item']['name']; }?>"/></dd>
</dl>

<dl>
	<dt>Message</dt>
	<dd><textarea name="data[EmailMessage][message]" cols="20" rows="5"><?php if(isset($temp_email_item)) { echo $temp_email_item['EmailMessage']['message'] ; }?></textarea></dd>
</dl>

<p><strong>Include Details</strong></p>
<input type="hidden" name="data[Item][details][]" value="units">
<dl>
<?php $units = $item_details[0]['Item']['units'] ?>
<?php foreach($item_details[0]['Item'] as $key => $value) { ?>
	<?php if ($key !== 'id' && $key !== 'item_type_id' && $key !== 'status' && $key !== 'units' && $value !== '' && $value !== null) { ?>
	<dd><input type="checkbox" checked="checked" name="data[Item][details][]" value="<?php echo $key?>"/> <em> <?php echo $fieldformatting->modify($key) ?>:  </em>
		<?php
			echo $fieldformatting->append($key, $value, $units);
		?>
	</dd>
	<?php } ?>
<?php } ?>

</dl>

<p><strong>Include Images</strong></p>
<dl class="item-photos">
	<?php foreach($item_details[0]['ItemImage'] as $i) {?>
	<?php if($i['filename'] !== '') { ?>
	<dd>
		<input type="checkbox" checked="checked" value="<?php echo $i['filename']?>" name="data[Item][images][]"/>
		<a class="image" href="../../../../files/<?php echo $i['filename'] ?>">
			<img src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $i['filename'], $settings)?>" />
		</a>
	</dd>
	<?php } ?>
	<?php } ?>
</dl>

<p><strong>Price</strong> (<em>Leave blank if none</em>)</p>
<dl>
	<dd><input type="text" value="<?php if(isset($temp_email_item) && isset($temp_email_item['EmailMessage']['asking_price'])) { echo $temp_email_item['EmailMessage']['asking_price'] ; } else { echo $item_details[0]['ItemVariation'][0]['price']; }?>" name="data[EmailMessage][asking_price]"/></dd>
<dl>
<input type="submit" value="Send" class="button gray-background black-text"/>
</form>

</div>