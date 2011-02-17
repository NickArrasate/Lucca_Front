<?php
	echo $html->css('order');
	$this->pageTitle = 'Lucca Antiques - Billing &amp; Shipping'; 
	//print_r($person);
?>
<div class="c_checkout">
	<dl class="order-steps">
		<dd class="active">1. Billing &amp; Shipping</dd>
		<dd>2. Payment Info</dd>
		<dd>3. Place Order</dd>
		<dd class="last">4. Thank you!</dd>
	</dl>
		
	<div class="wrapper">
		
		<h2>Shipping Options</h2>
		<span class="required">Required *</span>
		<p class="instructions">
			We currently only offer In-Store pickup. You may pick up your purchase from the shop or the warehouse.
Please contact us to advise us as to when you will be picking up your purchase.  
		</p>
		
		<form action="/orders/payment" method="post">
		
		<?php
		//TEMP
			$shipping_types = array(
				'LA Store Pickup',
				'NY Store Pickup',
				'LA Warehouse Pickup'
			);
		?>
		
		<select name="data[Order][shipping_type]" >
		<?php foreach ($shipping_types as $st) { ?>
		<?php if(isset($order['shipping_type']) && $st == $order['shipping_type']) { ?>
			<option selected="selected" value="<?php echo $st ?>"><?php echo $st?></option>
		<?php } else{  ?>
			<option value="<?php echo $st ?>"><?php echo $st?></option>
		<?php }?>
		<?php }?>
		</select>
		
		<h2>Billing Address</h2>
		<?php
			if(isset($errors)) {
				foreach ($errors as $e) {
					echo '<h6 class="error">* '. $e .'</h6>';
				}
			}
		?>
		<div class="form">
			<dl>
				<dt>First Name:*</dt>
				<dd><input name="data[Person][first_name]" type="text" style="width: 210px" value="<?php if(isset($person['first_name'])) { echo $person['first_name'];} ?>"/></dd>
			</dl>
			<dl>
				<dt>Last Name:*</dt>
				<dd><input name="data[Person][last_name]" type="text" style="width: 210px" value="<?php if(isset($person['last_name'])) { echo $person['last_name']; } ?>"/></dd>
			</dl>
			<dl>
				<dt>Address Line 1:*</dt>
				<dd><input name="data[Person][address_1]" type="text" style="width: 210px"value="<?php if(isset($person['address_1'])) { echo $person['address_1']; }?>" /></dd>
			</dl>
			<dl>
				<dt>Address Line 2:</dt>
				<dd><input name="data[Person][address_2]" type="text" style="width: 210px" value="<?php if(isset($person['address_2'])) { echo $person['address_2'];} ?>"/></dd>
			</dl>
			<dl>
				<dt>City:*</dt>
				<dd><input name="data[Person][city]" type="text" style="width: 210px" value="<?php if(isset($person['city'])) { echo $person['city'];} ?>"/></dd>
			</dl>
			<dl>
				<dt>State:*</dt>
				<dd><input type="text" name="data[Person][state]" style="width: 75px" value="<?php if(isset($person['state'])) { echo $person['state'];} ?>"/></dd>
			</dl>
			<dl>
				<dt>Zip Code:*</dt>
				<dd><input type="text" name="data[Person][zipcode]" style="width: 75px" value="<?php if(isset($person['zipcode'])) { echo $person['zipcode']; } ?>"/></dd>
			</dl>
			<dl>
				<dt>Phone Number:</dt>
				<dd><input type="text" name="data[Person][phone_number]" style="width: 210px" value="<?php if(isset($person['phone_number'])) { echo $person['phone_number'];}?>"/></dd>
			</dl>
			<dl>
				<dt>Email Address:*</dt>
				<dd><input type="text" name="data[Person][email]" style="width: 210px" value="<?php if(isset($person['email'])) { echo $person['email']; } ?>"/></dd>
			</dl>
		</div>
		
		<h2>Lucca Trade Professionals</h2>
		<?php if(isset($person['trade_professional']) && $person['trade_professional'] == '1') { ?>
		<input type="checkbox" checked="checked" name="data[Person][trade_professional]"></input> 
		<?php } else { ?>
		<input type="checkbox" name="data[Person][trade_professional]" value="1"></input> 
		<?php }?>
		I have registered with Lucca Antiques as a Trade Professional
		
		<dl class="button-wrap">
			<dd><input type="submit" class="button red-background white-text" value="Continue"></input></dd>
		</dl>
		
		</form>
		
	</div>
	<div class="item-category-border"></div>
</div>
<?php
?>