<?php
	echo $html->css('details');
	#$javascript->link('item_details', false);
	$this->pageTitle = 'Lucca Antiques - '. $breadcrumbs[0] . ': ' . $item_details[0]['Item']['name'] ;
	//debug($options);
?>

<script type="text/javascript">

$(document).ready(function() {

	$('a.image').fancybox();

	$('.product-visuals dl dd').click(function(){

		var l_image_file = $(this).children('#large-image').attr('src');

		var m_image_file = $(this).children('#medium-image').attr('src');

		$('.product-visuals .image img').attr('src', m_image_file);

		$('.product-visuals .image').attr('href', l_image_file);

		$('.product-visuals dl dd').attr('class','');

		$(this).attr('class','active');

	});

	$(".btn-email").fancybox({
		'scrolling'		: 'no',
		'titleShow'		: false,
		'onClosed'		: function() {
			$("#email_error").hide();
		}
	});

	$("#email-item").bind("submit", function() {
		var email_pattern = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		var email_from = $('#email_from').val();
		var email_from_name = $('#email_from_name').val();
		var	email_to = $('#email_to').val();

		var is_form_valid = true;

		if (email_to.length == 0) {
			is_form_valid = false;
			$('#email_error').text('Please enter in an email');
		} else if (!email_pattern.test(email_to)) {
			is_form_valid = false;
			$('#email_error').text('Email To is invalid. Please enter valid email address');
		}

		if (email_from.length == 0) {
			is_form_valid = false;
			$('#email_error').text('Please enter in an your email');
		} else if (!email_pattern.test(email_from)) {
			is_form_valid = false;
			$('#email_error').text('Your Email is invalid. Please enter valid email address');
		}

		if (is_form_valid) {
		$.fancybox.showActivity();

		$.ajax({
			type	: "POST",
			cache	: false,
			url		: "/item/email_item/<?php echo $this->params['pass'][0]; ?>",
			data	: $(this).serializeArray(),
			success	: function(data) {
					$('#email_from').val('<?php echo $item_details[0]['ItemLocation']['email']; ?>');
					$('#email_from_name').val('Lucca Antiques');
					$('#email_to').val('');
					$('#email_message').val('');
				$.fancybox(data);
			}
		});
		} else {
			$("#email_error").show();
			$.fancybox.resize();
		}

		return false;
	});

});

</script>
<div style="display:none">
	<?php print_r($item_details); echo " ---- "; print_r($inventory_location); echo "------"; print_r($inventory_location_id); echo "----";
	echo $item_details[0]['InventoryQuantity'][0]['location']?>
</div>
<div style="display:none">
	<form id="email-item" method="post" action="">
		<p>Send this page to your email:</p>
		<div style="width:267px;margin-bottom:8px;">
			<label for="email_to" style="text-align: right; float:left; dispaly: block; width: 70px; padding-top: 4px;">*To:&nbsp;</label><input type="text" id="email_to" name="data[EmailMessage][address_to]" size="20" />
		</div>
		<div style="width:267px;margin-bottom:8px;">
		<label for="email_from" style="text-align: right; float:left; dispaly: block; width: 70px; padding-top: 4px;">*Your Email:&nbsp;</label><input type="text" id="email_from" name="data[EmailMessage][address_from]" size="20" value="<?php echo $item_details[0]['ItemLocation']['email']; ?>"/>
		</div>
		<div style="width:267px;margin-bottom:8px;">
			<label for="email_from_name" style="text-align: right; float:left; dispaly: block; width: 70px; padding-top: 4px;">Your Name:&nbsp;</label><input type="text" id="email_from_name" name="data[EmailMessage][address_from_name]" size="20" value="Lucca Antiques"/>
		</div>
		<div style="width:267px;margin-bottom:5px;">
			Message: <br />
			<textarea name="data[EmailMessage][message]" cols="20" rows="5" style="width: 262px;" id="email_message"></textarea>
		</div>
	   	<p style="font-weight: bold;">* Required</p>
    	<p id="email_error" style="display:none; color: red; font-weight: bold;">Please enter in an email.</p>
		<p style="text-align:center">
			<input type="hidden" name="data[EmailMessage][subject]" value="Lucca Antiques : <?php echo $item_details[0]['Item']['name']; ?>" />
			<input type="hidden" name="data[EmailMessage][asking_price]" value="<?php echo $item_details[0]['ItemVariation'][0]['price']; ?>" />
			<?php foreach ($item_details[0]['ItemImage'] as $item_image) { ?>
			<input type="hidden" name="data[Item][images][]" value="<?php echo $item_image['filename']; ?>">
			<?php } ?>
			<input style="margin:0px auto" type="submit" value="Send" />
		</p>
	</form>
</div>

<div class="s_product3">

	<div class="wrapper">

		<?php foreach($item_details as $item_detail) { ?>

		<div class="product-visuals">

		<?php
			$thumb_settings = array('w'=>74,'h'=>74,'crop'=>1);
			$main_settings = array('w'=>400,'crop'=>1);
			$large_settings = array('w'=>600,'crop'=>1);
		?>

			<a href="../../../files/<?php echo $primary_image ?>" class="image">
			<img src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$primary_image, $main_settings);?>" />
			</a>

			<dl>
				<?php foreach( $item_detail['ItemImage'] as $item_image)  { ?>
					<?php if($item_image['filename'] !== '' ) {?>
					<?php if ($item_image['filename'] == $primary_image) { ?>

					<dd class="active">

						<img src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $thumb_settings)?>" />
						<img id="medium-image" class="hidden" src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $main_settings)?>" alt=""/>

						<img id="large-image" class="hidden" src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $large_settings)?>" alt=""/>

					</dd>

					<?php } else { ?>
					<dd>

						<img src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $thumb_settings)?>" />
						<img id="medium-image" class="hidden" src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $main_settings)?>" alt=""/>

						<img id="large-image" class="hidden" src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $large_settings)?>" alt=""/>

					</dd>
					<?php } ?>
					<?php } ?>
				<?php } ?>
			</dl>

		</div>
		<div class="product-written-details">
		<dl class="top-details-wrapper">
			<dd class="breadcrumbs">
			<a href="/">Home</a> >
				<?php
					echo '<span><a href="/item/grid/'. $item_type_id .'/all/">'. $breadcrumbs[0] .'</a> > </span>';
					echo '<span><a href="/item/grid/'. $item_type_id .'/'. $item_category_id .'">'. $breadcrumbs[1] .'</a></span>';
				?>
			</dd>
			<dd class="print" >
				<a style="display:none" target="_blank" href="/item/details/<?php echo $item_detail['Item']['id'] ?>/print/">Print</a>
				<br />
				<a style="display:none" href="#email-item" class="btn-email"/>Email</a>




			</dd>
		</dl>
		<h2><?php echo $item_detail['Item']['name'] ?></h2>
		<p><?php echo $item_detail['Item']['description'] ?></p>

		<?php echo $form->create(null, array('type' => 'post', 'id' => 'item-purchase-form', 'url' => array('controller' => 'orders', 'action' => 'add_item'))); ?>

			<?php echo $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][id]', 'value' => $item_detail['Item']['id'] )); ?>

			<?php
				$variation_count = sizeof($item_detail['ItemVariation']);

				//print_r($options);


				switch ($item_category_id) {
					case 1:
						// ANTIQUE *********************************************************************/
						// is an antique dont bother listing a qty. -- add a form attribute that's hidden instead
						$quantity = '<ul><li>';
						if((int)$item_detail['ItemVariation'][0]['quantity'] > 1 ){

							$keys = range(1, $item_detail['ItemVariation'][0]['quantity']);
							$values = range(1, $item_detail['ItemVariation'][0]['quantity']);
							$range = array_combine($keys, $values);

							$quantity .= $form->label('Quantity');

							$quantity .= $form->select($fieldName = '', $range, $selected ='', $attributes = array('name' => 'data[OrderedItem][quantity]', 'id' => null), $showEmpty = false);


						}elseif ((int)$item_detail['ItemVariation'][0]['quantity'] == 1 && $item_detail['Item']['status'] !== 'Sold') {
							$quantity .= $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][quantity]', 'value' => $item_detail['ItemVariation'][0]['quantity'] ));

						} else {
							$quantity .= '';
						}
						$quantity .= '</li></ul>';
						$price = '$' . $fieldformatting->price_formatting($item_detail['ItemVariation'][0]['price']);
						$price .=  $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][price]', 'value' => $item_detail['ItemVariation'][0]['price'] ));

						// there are no variations for antiques, but i still need the id of the main variation
						$variation = $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][item_variation_id]', 'value' => $item_detail['ItemVariation'][0]['id'] ));

						break;
					case 2:
						// LUCCA STUDIO *************************************************************/
						// is a lucca studio make a select list of 10
						$quantity = '<ul><li>' . $form->label('Quantity');

						$keys_quantity = range(1,10);
						$values_quantity = range(1,10);
						$range_quantity = array_combine($keys_quantity, $values_quantity);

						$quantity .= $form->select($fieldName = '', $range_quantity, $selected ='', $attributes = array('name' => 'data[OrderedItem][quantity]', 'id' => null), $showEmpty = false);

						$quantity .= '</li></ul>';
						// the price for the main variation
						$price = '$' . $fieldformatting->price_formatting($item_detail['ItemVariation'][0]['price']);
						$variation = '';
						if($variation_count > 1) {
							// if there are variations, list the ids
							$variation .= '<dd><ul><li>'. $form->label('Variations') .'</li><li>';

							foreach($item_detail['ItemVariation'] as $v) {
								$keys_variation[] = $v['id'];
								$values_variation[] = $v['name'] .' ($' . $fieldformatting->price_formatting($v['price']) .')';
							}

							$range_variation = array_combine($keys_variation, $values_variation);


							$variation .= $form->select($fieldName = '', $range_variation, $selected ='', $attributes = array('name' => 'data[OrderedItem][item_variation_id]', 'id' => null), $showEmpty = false);

							$variation .= '</li></ul></dd>';
						} else {
							// else variation count is equal to 0 and i still need the id for the main variation
							$variation .=
							$variation .= $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][item_variation_id]', 'value' => $item_detail['ItemVariation'][0]['id'] ));
						}
						break;
					case 3:
						// FOUND ITEM **********************************************/
						$quantity = '';

						if($item_detail['ItemVariation'][0]['quantity'] > 1) {
						$quantity .= '<ul><li>';
							$quantity .= $form->label('Quantity');

							if($item_detail['ItemVariation'][0]['quantity'] < 10) {
								$quantity_list = $item_detail['ItemVariation'][0]['quantity'];
							} else {
								$quantity_list = 10;
							}

							$values_found = range(1, $quantity_list);
							$keys_found = range(1, $quantity_list);
							$range_found = array_combine($keys_found, $values_found);

							$quantity .= $form->select($fieldName = '', $range_found, $selected ='', $attributes = array('name' => 'data[OrderedItem][quantity]', 'id' => null), $showEmpty = false);

							$quantity .= '</li></ul>';

						} elseif($item_detail['ItemVariation'][0]['quantity'] == 1) {

							$quantity .=  '<strong>'. $item_detail['ItemVariation'][0]['quantity'] .' Available</strong>';
							$quantity .= $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][quantity]', 'value' => $item_detail['ItemVariation'][0]['quantity'] ));

						} else {
							$quantity .= '';
						}
						$price = '$' . $fieldformatting->price_formatting($item_detail['ItemVariation'][0]['price']);
						$variation = $form->hidden('id', array('id' => null,  'name' => 'data[OrderedItem][item_variation_id]', 'value' => $item_detail['ItemVariation'][0]['id'] ));
						// for found items,  there are NO variations except for the main variation


						break;
				}

				if(isset($options)) {
					$option_count = sizeof($options);
				}
				$option = '';
				if($option_count > 0) {
				$option .= '<dd><p class="dark-red">Optional Add On:</p><ul><li>';
				$option .= $form->label($options[0]['Addon']['name']);
				$option .= '</li><li>';

				foreach($options as $a) {
					$keys_option[] = $a['Option']['id'];
					$values_option[] = $a['Option']['name'] . ' +($' . $fieldformatting->price_formatting($a['Option']['price']) .')';
				}

				$range_option = array_combine($keys_option, $values_option);

				$option .= $form->select($fieldName = '', $range_option, $selected ='', $attributes = array('name' => 'data[OrderedItem][addon][option_id]', 'id' => null), $showEmpty = '-- None ---');

				$option .= '</li></ul></dd>';
			}
			?>

			<dl>
				<?php if (!empty($item_detail['Item']['fid'])): ?>
					<dd class="item-price">Filemaker ID: <?php echo $item_detail['Item']['fid']; ?></dd>
				<?php endif; ?>
				<?php if($item_detail['Item']['status'] !== 'Sold') { ?>
				<dd class="item-price">Price: <?php echo $price ?></dd>
				<?php echo $variation ?>
				<?php } // end if its not sold ?>
				<?php if($item_detail['Item']['status'] !== 'Sold') { ?>
				<dd style="display:none">
					<?php echo $quantity ?>
				</dd>
				<?php } ?>
				<?php echo $option ?>

				<?php if($item_detail['Item']['status'] !== 'Sold') { ?>
					<!--
					<dd class="purchase">
					<input type="submit" value="Email" class="button gray-background white-text"/>
					</dd>
					<dd class="email">
					<a href="#email-item" class="button gray-background gray-text btn-email" style="font-size: 12px"/>3 Email</a>
					<a href="#email-item" class="button gray-background gray-text btn-email" style="background:#534741;color:#fff"/>4 Email</a>
					<a href="#email-item" class="button gray-background gray-text btn-email" style="font-size: 14px; font-weight:bold"/>5 Email</a>
					<br /><a href="#email-item" class="button gray-background gray-text btn-email" style="font-size: 18px; background:#999; color:white"/>6 Email</a>
					-->
					<dd class="email">
					<a href="/item/details/<?php echo $item_detail['Item']['id'] ?>/print/" class="button gray-background gray-text"/>Print</a>
					<a href="#email-item" class="button gray-background gray-text btn-email" />Email</a>
					</dd>
				<?php } else { ?>
					<dd class="sold"><span class="button gray-background red-text-border">SOLD</span></dd>

				<?php } ?>
			</dl>

		<div class="item-category-border "></div>

		<div class="item-details">
			<dl>
				<dt><span>Condition<span></dt>
				<dd class="width300"><?php echo $item_detail['Item']['condition'] ?></dd>
			</dl>

			<dl>
			<dt><span>Measurements</span></dt>
			<dd>
			<?php if ($item_detail['Item']['height'] !== null && $item_detail['Item']['height'] !== '') { ?>
				<strong>Height: </strong>

				<?php
				echo $item_detail['Item']['height'] . $item_detail['Item']['units'];
				?>

			<br/>
			<?php } ?>
			<?php if ($item_detail['Item']['height_2'] !== null && $item_detail['Item']['height_2'] !== '') { ?>

				<strong>Height 2: </strong>
				<?php
				echo $item_detail['Item']['height_2'] . $item_detail['Item']['units'];
				?>
			<br/>
			<?php } ?>

			<?php if ($item_detail['Item']['width'] != null && $item_detail['Item']['width'] !== '') { ?>
				<strong>Width: </strong>
					<?php
					echo $item_detail['Item']['width'] . $item_detail['Item']['units'];
					?>
			<br/>
			<?php } ?>

			<?php if ($item_detail['Item']['depth'] != null && $item_detail['Item']['depth'] !== '') { ?>

				<strong>Depth: </strong>
				<?php
				echo $item_detail['Item']['depth'] . $item_detail['Item']['units'];
				?>
			<br/>
			<?php } ?>

			<?php if ($item_detail['Item']['diameter'] != null && $item_detail['Item']['diameter'] !== '') { ?>

				<strong>Diameter: </strong>
				<?php
				echo $item_detail['Item']['diameter'] . $item_detail['Item']['units'];
				?>
			<br/>
			<?php } ?>
			</dd>
			</dl>

			<dl>
				<dt><span>Specifications</span></dt>
				<dd class="width300">
					<?php if ($item_detail['Item']['materials_and_techniques'] !== null && $item_detail['Item']['materials_and_techniques'] !== '') { ?>
						<strong>Materials/Techniques: </strong>
							<?php
							echo $item_detail['Item']['materials_and_techniques'];
							?>
					<br/>
					<?php } ?>

					<?php if ($item_detail['Item']['country_of_origin'] !== null && $item_detail['Item']['country_of_origin'] !== '') { ?>
						<strong>Origin: </strong>
							<?php
							echo $item_detail['Item']['country_of_origin'];
							?>
					<br/>
					<?php } ?>

					<?php if ($item_detail['Item']['creator'] !== null && $item_detail['Item']['creator'] !== '') { ?>
						<strong>Creator:</strong>
							<?php
							echo $item_detail['Item']['creator'];
							?>
					<br/>
					<?php } ?>

					<?php if ($item_detail['Item']['period'] !== null && $item_detail['Item']['period'] !== '') { ?>

						<strong>Period:</strong>

							<?php
							echo $item_detail['Item']['period'];
							?>
					<br/>

					<?php } ?>
				</dd>
			</dl>


			<dl>
				<dt><span>Location</span></dt>
				<dd class="width300">
					<?php
					/* modify the data, replace new lines with code. or do this when displaying the data
					$contact = preg_split('/\n/',$item_detail['InventoryLocation']['contact']);
					$search = array("anne@luccaantiques.com","lori@luccaantiques.com", "Please contact Anne");
					$replace = array("beth@luccaantiques.com","mark@luccaantiques.com", "Please contact Beth");
					foreach($contact as $c) {
						$formatted_contact[] = '<li>'. str_replace($search,$replace,$c) .'</li>';
					}
					$contact = '<ul>' . implode($formatted_contact) . '</ul>';
					echo $contact;*/
					// ADDED BY BRIAN
					//if(empty($item_detail['InventoryLocation']['contact'])){
						if ($item_details[0]['InventoryQuantity']['location'] == 1){
							echo "<ul><li>744 North La Cienega Blvd.
							</li><li>Los Angeles, CA 90069
							</li><li>Phone:             310-657-7800
							</li><li>Email: beth@luccaantiques.com</li></ul>";
						}elseif($item_details[0]['InventoryQuantity']['location'] == 2){
							echo "<ul><li>182 Duane Street
							</li><li>New York, NY 10013
							</li><li>Phone:             212-343-9005
							</li><li>Email:  mark@luccaantiques.com</li></ul>";
						}else{
							echo "<ul><li>This item is located in our LA Warehouse.
								<br />Please contact Beth at 310-657-7800 for viewing information.</li></ul>";
						}

					//}
					// END BRIAN ADDITIONS
					?>
				</dd>
			</dl>
		</div>
		</div>
		<?php } ?>

	</div>
</div>
