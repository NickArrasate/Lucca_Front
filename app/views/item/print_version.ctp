
<?php
//debug($item_details);
$this->pageTitle = "Lucca Antiques";
?>
<?php 
			$thumb_settings = array('w'=>100,'h'=>100,'crop'=>1); 
			$larger_thumb_settings = array('w'=>170,'h'=>170,'crop'=>1);
			$main_settings = array('w'=>400,'crop'=>1); 
			$large_settings = array('w'=>600,'crop'=>1); 
		?>

		<?php foreach($item_details as $item_detail) { ?>
			<?php $settings = array('w'=>500,'crop'=>1); ?>
		<div id="PrintSheetContent">
			<div id="BigImageBox">
				<img style="max-height:330px" src="<?=$resizeimage->resize(WWW_ROOT . '/files/'. $primary_image, $settings)?>"></img>
			</div>
			<div style="clear:both;"></div>
			<h2 class="ProductTitle"><?php echo $item_detail['Item']['name'] ?></h2>
			<h3 class="details"><?php echo $item_detail['Item']['description'] ?></h3>
            <div style="float:left;width:50%">
                <h2 class="ProductTitle">Price</h2>
                <h3 class="details">
                    <?php
                            $variation_count = sizeof($item_detail['ItemVariation']);
                            $price = '$' . $fieldformatting->price_formatting($item_detail['ItemVariation'][0]['price']);
                            switch ($item_category_id) {
                                case 1:
                                    // ANTIQUE **************************************************************************************8
                                    // is an antique dont bother listing a qty. -- add a form attribute that's hidden instead
                                    $quantity = '<dl>';
                                    if((int)$item_detail['ItemVariation'][0]['quantity'] > 1){
                                        $quantity .= '<dt>Quantity available: </dt><dd>' . $item_detail['ItemVariation'][0]['quantity'];
                                    }elseif((int)$item_detail['ItemVariation'][0]['quantity'] == 1)  {
                                        $quantity .= '';
                                    } else {
                                        $quantity .= '';
                                    }
                                    $quantity .= '</dl>';
                                    // there are no variations for antiques, but i still need the id of the main variation
                                    $variation = '';
                                    break;
                                case 2:
                                    // LUCCA STUDIO *************************************************************
                                    // is a lucca studio make a select list of 10
                                    $quantity = '';
                                    // the price for the main variation
                                    $variation = '<dl>';
                                    if($variation_count > 1) {
                                        // if there are variations, list the ids
                                        $variation .= '<dt><span>Variations</span></dt><dd>';
                                        foreach($item_detail['ItemVariation'] as $v) {
                                            $variation .= $v['name'] .' ($' . $fieldformatting->price_formatting($v['price']) .')<br/>';
                                        }
                                    }
                                    $variation .= '</dd></dl>';
                                    break;
                                case 3:
                                    // FOUND ITEM **********************************************
                                    $quantity = '<dl>';
                                    if((int)$item_detail['ItemVariation'][0]['quantity'] > 1){
                                        $quantity .= '<dt><dd>' . $item_detail['ItemVariation'][0]['quantity'] .' Available</dd>';
                                    }elseif((int)$item_detail['ItemVariation'][0]['quantity'] == 1)  {
                                        $quantity .= '<dd><strong>1 Available</strong></dd>';
                                    } else {
                                        $quantity .= '';
                                    }
                                    $quantity .= '</dl>';
                                    $variation = '';
                                    // for found items,  there are NO variations except for the main variation
                                    $variation = '';
                        
                                    break;
                                case 4:
                                    // LIMITED EDITION **********************************************
                                    $quantity = '<dl>';
                                    if((int)$item_detail['ItemVariation'][0]['quantity'] > 1){
                                        $quantity .= '<dt><dd>' . $item_detail['ItemVariation'][0]['quantity'] .' Available</dd>';
                                    }elseif((int)$item_detail['ItemVariation'][0]['quantity'] == 1)  {
                                        $quantity .= '<dd><strong>1 Available</strong></dd>';
                                    } else {
                                        $quantity .= '';
                                    }
                                    $quantity .= '</dl>';
                                    $variation = '';
                                    // for found items,  there are NO variations except for the main variation
                                    $variation = '';

                                    break;

                            }
                
                            if(isset($options)) {
                                $option_count = sizeof($options);
                                $option = '';
                                if($option_count > 0) {
                                $option .= '<dl><dt><span>Addons:</span></dt><dd>';
                                foreach($options as $a) {
                                    $option .= $a['Option']['name'] . ' +($' . $fieldformatting->price_formatting($a['Option']['price']) .') <br/>';
                                }
                                $option .= '</dd></dl>';
                            }
                        } 
                        ?>
                        <?php if(isset($price) && $session->check('Trade')) {echo '' . $price; } ?>
                        <?php if($item_detail['Item']['status'] !== 'Sold') { ?>

                        <?php } else { ?>
                            <span class="button gray-background red-text-border">SOLD</span>
                        <?php } ?>
                </h3>
                <?php if ($item_details[0]['InventoryQuantity'][0]['location'] == 1){ 
                        $item_detail['InventoryLocation']['address'] = "744 North La Cienega Blvd. Los Angeles, CA 90069";
                        $item_detail['InventoryLocation']['phone'] = "310-657-7800";
                        $item_detail['InventoryLocation']['email'] = LA_EMAIL;
                        $email = "phaedra@luccaantiques.com";
                    }elseif($item_details[0]['InventoryQuantity'][0]['location'] == 2){
                        $item_detail['InventoryLocation']['address'] = "306 East 61st Street 4th Floor New York, NY 10065";
                        $item_detail['InventoryLocation']['phone'] = "212-343-9005";
                        $item_detail['InventoryLocation']['email'] = NY_EMAIL;
                    }else{
                        $item_detail['InventoryLocation']['address'] = "This item is located in our LA Warehouse.  Please contact 
                        ".LA_NAME." for viewing information.";
                        $item_detail['InventoryLocation']['phone'] = "310-657-7800";
                        $item_detail['InventoryLocation']['email'] = LA_EMAIL;
                    }?>
                    <?php if(!empty($item_detail['Item']['fid'])){ ?>
                <h2 class="ProductTitle">Item ID:</h2>
                <h3 class="details"><?php echo $item_detail['Item']['fid'] ?></h3>
                <?php } ?>
                <!--
                <h2 class="ProductTitle">Condition:</h2>
                <h3 class="details"><?php echo $item_detail['Item']['condition'] ?></h3>
                -->
            </div>
            <div style="float:left;width:50%">
                <h2 class="ProductTitle">Measurements:</h2>
                <?php if ($item_detail['Item']['height'] !== null && $item_detail['Item']['height'] !== '') { ?>
                <h3 class="details">Height: <?php echo $item_detail['Item']['height'] . $item_detail['Item']['units']; ?></h3>
                <?php } ?>
                <?php if ($item_detail['Item']['height_2'] !== null && $item_detail['Item']['height_2'] !== '') { ?>
                <h3 class="details">Height 2: <?php echo $item_detail['Item']['height_2'] . $item_detail['Item']['units'];	?></h3>
                <?php } ?>
                <?php if ($item_detail['Item']['width'] != null && $item_detail['Item']['width'] !== '') { ?>
                <h3 class="details">Width: <?php echo $item_detail['Item']['width'] . $item_detail['Item']['units']; ?></h3>
                <?php } ?>
                <?php if ($item_detail['Item']['depth'] != null && $item_detail['Item']['depth'] !== '') { ?>
                <h3 class="details">Depth: <?php echo $item_detail['Item']['depth'] . $item_detail['Item']['units']; ?></h3>
                <?php } ?>
                <?php if ($item_detail['Item']['diameter'] != null && $item_detail['Item']['diameter'] !== '') { ?>
                <h3 class="details">Diameter: <?php echo $item_detail['Item']['diameter'] . $item_detail['Item']['units']; ?></h3>
                <?php } ?>
                <h2 class="ProductTitle">Specifications:</h2>
                <?php if ($item_detail['Item']['materials_and_techniques'] !== null && $item_detail['Item']['materials_and_techniques'] !== '') { ?>
                <h3 class="details">Materials/Techniques: <?php echo $item_detail['Item']['materials_and_techniques']; ?></h3>
                <?php } ?>
                <?php if ($item_detail['Item']['country_of_origin'] !== null && $item_detail['Item']['country_of_origin'] !== '') { ?>
                <h3 class="details">Origin: <?php echo $item_detail['Item']['country_of_origin']; ?></h3>
                <?php } ?>	
                <?php if ($item_detail['Item']['creator'] !== null && $item_detail['Item']['creator'] !== '') { ?>
                <h3 class="details">Creator: <?php	echo $item_detail['Item']['creator']; ?></h3>
                <?php } ?>
                <?php if ($item_detail['Item']['period'] !== null && $item_detail['Item']['period'] !== '') { ?>
                <h3 class="details">Period: <?php echo $item_detail['Item']['period']; ?></h3>
                <?php } ?>
            </div>
            <div style="clear:both"></div>
            <p style="text-align:center; padding-top:10px;"><?php echo $item_detail['InventoryLocation']['address']; ?> | <?php echo $item_detail['InventoryLocation']['phone']; ?> | <?php echo $item_detail['InventoryLocation']['email']; ?></p>
            
<!--		<div id="ContentDetailsBox">
				<div id="ContentDetailsItem">
					<h5 class="NoPad">Condition:</h5>
					<h6 class="DetailTitle"><span class="OfNote"><?php echo $item_detail['Item']['condition'] ?></span></h6>
				<div style="clear:both;"></div>
				</div>

				<div id="ContentDetailsItem">
					<h5 class="NoPad">Measurements:</h5>
					<?php if ($item_detail['Item']['height'] !== null && $item_detail['Item']['height'] !== '') { ?>
						<h6 class="DetailTitle">Height: <span class="OfNote"><?php echo $item_detail['Item']['height'] . $item_detail['Item']['units']; ?></span></h6>
					<?php } ?>
					<?php if ($item_detail['Item']['height_2'] !== null && $item_detail['Item']['height_2'] !== '') { ?>
						<h6 class="DetailTitle">Height 2: <span class="OfNote"><?php echo $item_detail['Item']['height_2'] . $item_detail['Item']['units'];	?></span></h6>
					<?php } ?>
					<?php if ($item_detail['Item']['width'] != null && $item_detail['Item']['width'] !== '') { ?>
						<h6 class="DetailTitle">Width: <span class="OfNote"><?php echo $item_detail['Item']['width'] . $item_detail['Item']['units']; ?></span></h6>
					<?php } ?>
					<?php if ($item_detail['Item']['depth'] != null && $item_detail['Item']['depth'] !== '') { ?>
						<h6 class="DetailTitle">Depth: <span class="OfNote"><?php echo $item_detail['Item']['depth'] . $item_detail['Item']['units']; ?></span></h6>
					<?php } ?>
					<?php if ($item_detail['Item']['diameter'] != null && $item_detail['Item']['diameter'] !== '') { ?>
						<h6 class="DetailTitle">Diameter: <span class="OfNote"><?php echo $item_detail['Item']['diameter'] . $item_detail['Item']['units']; ?></span></h6>
					<?php } ?>
				<div style="clear:both;"></div>

				</div>
				<div id="ContentDetailsItem">
					<h5 class="NoPad">Specifications:</h5></dt>
					<?php if ($item_detail['Item']['materials_and_techniques'] !== null && $item_detail['Item']['materials_and_techniques'] !== '') { ?>
						<h6 class="DetailTitle">Materials/Techniques: <span class="OfNote"><?php echo $item_detail['Item']['materials_and_techniques']; ?></span></h6>
					<?php } ?>
					<?php if ($item_detail['Item']['country_of_origin'] !== null && $item_detail['Item']['country_of_origin'] !== '') { ?>
						<h6 class="DetailTitle">Origin: <span class="OfNote"><?php echo $item_detail['Item']['country_of_origin']; ?></span></h6>
					<?php } ?>	
					<?php if ($item_detail['Item']['creator'] !== null && $item_detail['Item']['creator'] !== '') { ?>
						<h6 class="DetailTitle">Creator: <span class="OfNote"><?php	echo $item_detail['Item']['creator']; ?></span></h6>
					<?php } ?>
					<?php if ($item_detail['Item']['period'] !== null && $item_detail['Item']['period'] !== '') { ?>
						<h6 class="DetailTitle">Period: <span class="OfNote"><?php echo $item_detail['Item']['period']; ?></span></h6>
					<?php } ?>
				<div style="clear:both;"></div>

				</div>
                <div style="display:none">
                <?php print_r($item_details); echo "----";print_r($item_details[0]['InventoryQuantity']);?>
                </div>
                
				<div id="ContentDetailsItem">
					<h5 class="NoPad">Contact:</h5>
					<h6 class="DetailTitle">Email: <span class="OfNote"><?php echo $email;$item_detail['InventoryLocation']['email']; ?></span></h6>
					<h6 class="DetailTitle">Phone: <span class="OfNote"><?php echo $item_detail['InventoryLocation']['phone']; ?></span></h6>
					<h6 class="DetailTitle">Address: <span class="OfNote"><?php echo $item_detail['InventoryLocation']['address']; ?></span></h6>
				<div style="clear:both;"></div>
				</div>	
               
			</div>

			<div id="ThumbBox">
				<?php $i = 1;
					foreach( $item_detail['ItemImage'] as $item_image)  { 
						if ($item_image['filename'] == $primary_image){
								continue;
							} ?>
						<?php if($item_image['filename'] !== '' ) {
						if($i > 5){
							continue;
						}
						$i++;
						?>
						<img style="margin:0px 5px 5px 0px; border:1px solid #666666;" src="<? echo $resizeimage->resize(WWW_ROOT . '/files/'.$item_image['filename'], $larger_thumb_settings)?>" /> 
					<?php } // end if filename exists ?>
				<?php } // end foreach ?>
			</div>
		</div>
-->	
	<script type="text/javascript">
		window.print();
	</script>
	<?php } ?>
	