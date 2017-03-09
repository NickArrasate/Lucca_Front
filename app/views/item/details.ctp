 <?php
	echo $this->element('versioned_css', array('files' => 'details'));
	#$javascript->link('item_details', false);
	$this->pageTitle = 'Lucca Antiques - '. $breadcrumbs[0] . ': ' . $item_details[0]['Item']['name'] ;
	echo $html->css('fotorama');
    echo $html->css('cloudzoom');
?>
<script type="text/javascript" src="/js/fotorama.js"></script>
<script type="text/javascript">

$(document).ready(function() {

	$(function() {
		if ("ontouchstart" in window || navigator.msMaxTouchPoints) {
			isTouch = true;
			$('.fotorama').attr('data-swipe', 'true');			
		} else {
			isTouch = false;
		}	
		
		zoomInit = false;
		
		$('#modal_gallery').css({
			'display' : 'block',
			'height' : '0px'
		});
		
		var $fotoramaDiv = $('.fotorama').fotorama();
		fotorama = $fotoramaDiv.data('fotorama');
	});
	
    //$('.lightbox').lightBox();
	
	$('.fotorama').on('click', '.fotorama__stage__frame.fotorama__loaded.fotorama__loaded--img.fotorama__active img', function(){
		if (!isTouch) {
			if(!zoomInit) {
				var l_image_file = $(this).data('large_image');
				$options = {
					zoomImage:l_image_file,
					zoomPosition:3,
					zoomWidth:200,
					zoomHeight:200
				};
				cloud_zoom = new CloudZoom($('.fotorama__stage__frame.fotorama__loaded.fotorama__loaded--img.fotorama__active img'), $options);
				zoomInit = true;
                $('#zoom-button .text').html('hover over image to zoom');
			}
		}
	});
	
	$('.thumb-gallary').click(function(){
		var frame = $(this).data('frame');
		fotorama.show(frame);
		$('div.fotorama__wrap').css({'margin' : '0 auto'});
		$('#modal_gallery').css({'height' : 'auto'});
	});
	
	$('.fotorama').on('fotorama:load ' + 'fotorama:showend', function(e, fotorama) {
		var active_frame = fotorama.activeFrame;
		var qaz = $('div.fotorama__stage__frame.fotorama__active.fotorama__loaded.fotorama__loaded--img').find("img[src='" + active_frame.img + "']");
		qaz.attr('data-large_image', active_frame.large_image);
		zoomInit = false;
		if(typeof cloud_zoom != 'undefined') {
			cloud_zoom.closeZoom();
			cloud_zoom.destroy();
            $('#zoom-button .text').html('click to zoom');
		}
	});

    $('#zoom-button').click(function(){
        $('.fotorama__stage__frame.fotorama__loaded.fotorama__loaded--img.fotorama__active img').trigger('click');
        return false;
    });

	$('body').on('click', 'div.cloudzoom-lens', function(){
		if(zoomInit) {
			zoomInit = false;
			cloud_zoom.closeZoom();
			cloud_zoom.destroy();
            $('#zoom-button .text').html('click to zoom');
		}
	});
	
//    $('.product-visuals dl dd').click(function(){
//
//        if($('#zoom-image').data('CloudZoom')){
//            var cz_object = $('#zoom-image').data('CloudZoom');
//            cz_object.destroy();
//        }
//        var m_image_file = $(this).children('#medium-image').attr('src');
//        var l_image_file = $(this).children('#large-image').attr('src');
//
//        $('#zoom-image').attr('src',m_image_file);
//        $('#zoom-image').data('medium-image',m_image_file);
//        $('#zoom-image').data('large-image',l_image_file);
//
//        $('.product-visuals dl dd').attr('class','');
//        $(this).attr('class','active');
//
//
//        $('#zoom-image').click(function(){
//            var l_image_file = $(this).data('large-image');
//            $('#zoom-image').CloudZoom({
//                zoomImage:l_image_file,
//                zoomPosition:3,
//                zoomWidth:200,
//                zoomHeight:200
//            });
//            $zoomInit = true;
//        });
//
//    });

	/*
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
			$('#email_error').text('Please enter an email address');
		} else if (!email_pattern.test(email_to)) {
			is_form_valid = false;
			$('#email_error').text('To: email is invalid. Please enter a valid email address');
		}

		if (email_from.length == 0) {
			is_form_valid = false;
			$('#email_error').text('Please enter a From: email address');
		} else if (!email_pattern.test(email_from)) {
			is_form_valid = false;
			$('#email_error').text('Your Email is invalid. Please enter a valid email address');
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
	*/
});

</script>
<div style="display:none">
	<form id="email-item" method="post" action="">
		<p>Send this page to an email address:</p>
		<div style="width:267px;margin-bottom:8px;">
			<label for="email_to" style="text-align: right; float:left; dispaly: block; width: 80px; padding-top: 4px;">*To Email:&nbsp;</label><input type="text" id="email_to" name="data[EmailMessage][address_to]" size="20" />
		</div>
		<div style="width:267px;margin-bottom:8px;">
		<label for="email_from" style="text-align: right; float:left; dispaly: block; width: 80px; padding-top: 4px;">*From Email:&nbsp;</label><input type="text" id="email_from" name="data[EmailMessage][address_from]" size="20" value="<?php echo $item_details[0]['ItemLocation']['email']; ?>"/>
		</div>
		<div style="width:267px;margin-bottom:8px;">
			<label for="email_from_name" style="text-align: right; float:left; dispaly: block; width: 80px; padding-top: 4px;">From Name:&nbsp;</label><input type="text" id="email_from_name" name="data[EmailMessage][address_from_name]" size="20" value="Lucca Antiques"/>
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

	<div class="wrapper row">

		<?php foreach($item_details as $item_detail) { ?>

		<div class="product-visuals col-xs-4 col-md-6">

		<?php
			$thumb_settings = array('w'=>74,'h'=>74,'crop'=>1);
			$main_settings = array('w'=>400,'crop'=>1);
			$large_settings = array('w'=>600,'crop'=>1);
			
		?>

            <img id="zoom-image" class="thumb-gallary" 
				 data-toggle="modal" 
				 data-target="#modal_gallery" 
				 data-frame="0"
                 src="<?php echo $resizeimage->resize(WWW_ROOT . '/files/'.$primary_image, $main_settings)?>" 
			/>
			
		<?php
			$frame = 0;
			foreach( $item_detail['ItemImage'] as $item_image) {
				if($item_image['filename'] !== '' ) {
					$gallery[$frame]['large-image'] = Router::url('/', true) . 'files/' . $item_image['filename'];
					$gallery[$frame]['medium-image'] = $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $main_settings);
					$gallery[$frame]['thumb-image'] = $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $thumb_settings);
					$frame ++;
				}
			}
		?>
			<dl>
				<?php foreach($gallery as $frame => $image) { ?>
					<dd>
						<img class="thumb-gallary" data-toggle="modal" data-target="#modal_gallery" data-frame="<?php echo $frame; ?>" src="<?php echo $image['thumb-image']; ?>" />
					</dd>	
				<?php } ?>
			</dl>
		</div>

		<div class="product-written-details col-xs-8 col-md-6">
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
							$variation .= '<dd class="hidden"><ul><li>'. $form->label('Variations') .'</li><li>';

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
					case 4:
						// Limited Edition *************************************************************/
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
							$variation .= '<dd class="hidden"><ul><li>'. $form->label('Variations') .'</li><li>';

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
				<?php if($item_detail['Item']['status'] !== 'Sold') { ?>
					<?php if($isTrader || $isUser) { ?>
						<dd class="item-price">Price: <?php echo $price ?></dd>
					<?php } ?>
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
					<a href="#email-item" class="button gray-background gray-text btn-email hidden" />Email</a>
					</dd>
				<?php } else { ?>
                
					<dd class="sold"><span class="button gray-background red-text-border">SOLD</span></dd>

				<?php } ?>
			</dl>

		<div class="item-category-border "></div>

		<div class="item-details">
        	<?php if(!empty($item_detail['Item']['fid'])){ ?>
			<dl>
				<dt><span>Item ID<span></dt>
				<dd class="width300"><?php echo $item_detail['Item']['fid'] ?></dd>
			</dl>
            <?php } ?>
            <dl class="hidden">
				<dt><span>Condition<span></dt>
				<dd class="width300"><?php echo $item_detail['Item']['condition'] ?></dd>
			</dl>

			<dl>
			<dt><span>Measurements</span></dt>
			<dd class="width300">
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
						if ($item_detail['InventoryQuantity']['location'] == 3){
							echo "<ul><li>This item is located in our Santa Barbara facility.
								<br />Please contact ".WH_NAME." at ". $item_detail['ItemLocation']['phone'] .
								" or <a href='mailto:" . $item_detail['ItemLocation']['email'] . "'>" . $item_detail['ItemLocation']['email'] .
								"</a> for viewing information.</li></ul>";
						} else {
							echo "<ul>
								<li>" . $item_detail['ItemLocation']['address1'] . "</li>
								<li>" . $item_detail['ItemLocation']['address2'] . "</li>
								<li>Phone: " . $item_detail['ItemLocation']['phone'] . "</li>
								<li>Email: <a href='mailto:" . $item_detail['ItemLocation']['email'] . "'>" . $item_detail['ItemLocation']['email'] . "</a></li>
							</ul>";
						}
					?>
				</dd>
			</dl>
		</div>
		</div>
		<dd class="no-margin col-xs-12"><div class="category-results-divider-bottom"></div></dd>
		<?php } ?>

	</div>
</div>

<div class="modal fade" id="modal_gallery" >
    <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
              <a href="#" id="zoom-button" class="hidden-xs pull-left">
                  <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                  <span class="text">click to zoom</span>
              </a>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <span>&nbsp;</span>
          </div>
          <div class="modal-body">

            <div class="fotorama" data-auto="false"
                 data-nav="thumbs"
                 data-click="false"
                 data-arrows="always"
                 data-swipe="false"
            >
                <?php foreach ($gallery as $frame => $image) { ?>
                    <a data-large_image="<?php echo $image['large-image']; ?>" href="<?php echo $image['medium-image']; ?>">
                        <img data-qaz="1" src="<?php echo $image['thumb-image']; ?>" />
                    </a>
                <?php } ?>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default hidden" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
</div>
