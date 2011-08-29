<div style="font-family: Arial, sans-serif; font-size:13px;color:#000; ">
	<table style="width:800px; padding:20px 0px; border-collapse:collapse; font-size:13px">
		<tr>
			<td colspan="2" style="font-size:12px; padding-bottom:20px;"><?php echo $email_body?></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<a href="http://www.luccaantiques.com"><img style="padding-bottom:10px;" src="http://dev.luccaantiques.com/img/Lucca-Logo2.png" height="120" width="380" alt="Lucca Antiques logo" border="0" /></a>
			</td>
		</tr>
		<tr>
			<td valign="top" colspan="2" align="center" style="padding:20px 0px;">
			<?php if(isset($images) && count($images) !== 0) {
				$top_image = '';
				foreach($images as $i) {
					$top_image .= '<a href="http://www.luccaantiques.com/item/details/'. $item_details['Item']['id'] .'"><img border="0" alt="Lucca Antiques Item Photo" height="450" width="450" src="http://www.luccaantiques.com/files/'. $i .'"/></a>';
					//CODE FOR ADDING A NICE LOOKING BORDER AROUND IMAGES - style="padding:5px; border:5px solid #352F2F"
					$top_image_url = $i;
				break;
				}
				echo $top_image;
			}
			?>
			</td>
			</tr>
			<?php

			if(isset($asking_price)) {
				if($asking_price !== '') {
					$item_details['Item']['price'] = $asking_price;
				}
			}
			if(isset($item_details['Item']['name'])) {
					$content .=  '<tr><td valign="top" colspan="2" style="padding-top:20px;" align="left"><span style="font-size:18px">'.$item_details['Item']['name'].'</span><Br />';
				}
			if(isset($item_details)) {

				if(isset($item_details['Item']['description'])) {
					$content .= '<span style="font-size:14px">' . $item_details['Item']['description'] . '</span></td></tr>';
				}

				if(isset($item_details['Item']['price'])) {
					$content .= '<tr><td colspan="2" align="left" style="padding-top:20px"><span style="font-size:18px">Price </span><br /><span style="font-size:14px">$'.$fieldformatting->price_formatting($item_details['Item']['price']) . '</span></td></tr>';
				}

				$units = $item_details['Item']['units'];

				$right_style ='<span style="font-size:14px">';
				$condition = '<tr><td colspan="2" valign="top" align="left" style="padding-top:20px"><span style="font-size:18px">Condition:</span><br />'.$right_style;
				$measurements = '<tr><td colspan="2" valign="top" align="left" style="padding-top:20px"><span style="font-size:18px">Measurements:</span><br />'.$right_style;
				$specifications = '<tr><td colspan="2" valign="top" align="left" style="padding-top:20px"><span style="font-size:18px">Specifications:</span><br />'.$right_style;

				foreach($item_details['Item'] as $key => $value) {
					if ($item_details['Item'][$key] !== null && $item_details['Item'][$key] !== '' && $key !== 'units' && $key !== 'name' && $key !== 'price' && $key !== 'description' && $key !== 'id' && $key !== 'inventory_location_id' ) {
						if($key == 'condition'){

							$condition .= $fieldformatting->append($key, $value, $units) .'<br />';

						}elseif ($key == 'height' || $key == 'height_2' || $key == 'width' || $key == 'depth' || $key == 'diameter') {

							$measurements .= $fieldformatting->modify($key) .': ' . $fieldformatting->append($key, $value, $units) .'<br />';

						} elseif ($key == 'creator' || $key == 'country_of_origin' || $key == 'period') {

							$specifications .= $fieldformatting->modify($key) .': ' . $fieldformatting->append($key, $value, $units) .'<br />';

						}

					}
				}

			}
				$end_style ='</span></td></tr>';
				echo $content;
				echo $condition.$end_style;
				echo $measurements.$end_style;
				echo $specifications.$end_style;
				// <br /><a href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=Lucca+Antiques&sll=34.092189,-118.398457&sspn=0.083305,0.110378&ie=UTF8&hq=Lucca+Antiques&hnear=&ll=34.093326,-118.393993&spn=0.083304,0.110378&z=13&iwloc=A">Click for Map</a>
			?>
		<tr>
			<td colspan="2" style="padding-top:20px">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="2" valign="top" align="center" style="padding-top:20px">
			<span style="font-size:16px">
				<?php echo $item_details['InventoryLocation']['address']; ?>  |  <?php echo $item_details['InventoryLocation']['phone']; ?>  |  <?php echo $item_details['InventoryLocation']['email']; ?>
			</span>
			</td>
		</tr>
	<?php /*	<tr>
			<td colspan="2" align="left" style="margin-top:10px; padding-left:0px; padding-top:10px; padding-bottom:10px;">
			<?php
			if(isset($images) && count($images) !== 0) {
					foreach($images as $i) {
						if ($i == $top_image_url){
							continue;
						}
						$contents .= '<a href="http://www.luccaantiques.com/item/details/'. $item_details['Item']['id'] .'"><img alt="Lucca Antiques Item Photo"  style="border:0; padding:10px 10px 0px 0px;" height="100" width="100" src="http://www.luccaantiques.com/files/'. $i .'"/></a>';
					}
				}

			echo $contents;

			</td>
		</tr>  */ ?>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</table>
</div>
