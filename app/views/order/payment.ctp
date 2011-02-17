<?php
	echo $html->css('order');
	$this->pageTitle = 'Lucca Antiques - Payment'; 
	debug($order);
?>
<div class="c_checkout">
	<dl class="order-steps">
		<dd>1. Billing &amp; Shipping</dd>
		<dd class="active">2. Payment Info</dd>
		<dd>3. Place Order</dd>
		<dd class="last">4. Thank you!</dd>
	</dl>
		
	<div class="wrapper">
		<?php
			if(isset($errors)) {
				foreach ($errors as $e) {
					echo '<h6 class="error">* '. $e .'</h6>';
				}
			}
		?>	
		<h2>Credit Card Information</h2>
		<span class="required">Required *</span>
		<p class="instructions">Your credit card will not be charged until an order is placed.</p>
		
		<form method="post" action="/orders/place_order/">
		<div class="form">
			<dl>
				<dt>Credit Card Type:*</dt>
				<dd>
					<select name="data[Creditcard][type]">
						<?php foreach ($creditcard['type'] as $key => $value) {?>
						<option value="<?php echo $value ?>"><?php echo $value ?></option>
						<?php } ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt>Credit Card Number:*</dt>
				<dd><input  name="data[Creditcard][number]" type="text" style="width: 210px"/></dd>
			</dl>
			<dl>
			<?php // the expiration date should be a helper, based on the current date, IT CANNOT BE SOMETHNG LIKE JAN 2009, WHICH IS AN OPTION ?>
				<dt>Expiration Date:*</dt>
				<dd>
					<select name="data[Creditcard][expiration_date_month]">
						<option value="">-- Month --</option>
						<?php foreach ($creditcard['month'] as $key => $value) {?>
						<option value="<?php echo $key ?>"><?php echo $value ?></option>
						<?php } ?>
					</select>
					<select name="data[Creditcard][expiration_date_year]">
						<option value="">-- Year --</option>
						<?php foreach ($creditcard['year'][0] as $key => $value) {?>
						<option value="<?php echo $value ?>"><?php echo $value ?></option>
						<?php } ?>
					</select>
				</dd>
			</dl>
			<dl>
				<dt>Security Code:*</dt>
				<dd><input name="data[Creditcard][security_code]" type="text" style="width: 75px"/></dd>
			</dl>
		</div>
		
		<dl class="button-wrap">
			<dd><a href="/orders/billing/" class="button gray-background red-text">Back</a></dd>
			<dd><input type="submit" class="button red-background white-text" value="Continue"></dd>
		</dl>
		
		</form>
		
	</div>
	<div class="item-category-border"></div>
</div>