<?php
	echo $html->css('order');
	$this->pageTitle = 'Lucca Antiques - Place Your Order'; 
	debug($order);
?>
<div class="c_checkout place-order">
	<dl class="order-steps">
		<dd>1. Billing &amp; Shipping</dd>
		<dd>2. Payment Info</dd>
		<dd class="active">3. Place Order</dd>
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
		<h2>Items in Order</h2>
			
		<table>
			<tr>
				<th>SKU</th>
				<th>Item Details</th>
				<th class="price">Price</th>
				<th class="quantity">Quantity</th>
				<th class="amount">Amount</th>
			</tr>
			<tr><td colspan="6"><div class="solid-gray-line"></div></td></tr>
			<?php if(count($ordered_items) >= 1) {$i = 0 ; ?>
				<?php foreach($ordered_items as $o) { $i++; ?>
				
				<tr>
					<td><?php echo $o['sku']  ?></td>
					<td>
						<dl>
							<dt><?php echo $o['name']?></dt>
							<dd><em><?php echo $o['item_variation_name']?></em></dd>
						</dl>
					</td>
					<td class="price">$<?php echo $o['price'] ?></td>
					<td class="quantity"><?php echo $o['quantity'] // if the qty is an antique?? not an input box.?></td>
					<td class="amount">$<?php echo number_format($o['price'] * $o['quantity'], 2) ?></td>
				</tr>
				
				<?php if( isset($o['addon']) && $o['addon']['price'] !== '') { ?>
				<tr>
					<td><?php echo $o['addon']['sku'] ?></td>
					<td>
						<dl>
							<dt><?php echo $o['addon']['name'] ?></dt>
						</dl>
					</td>
					<td class="price">$ <?php echo $o['addon']['price'] ?></td>
					<td class="quantity"><?php echo $o['addon']['quantity']?><input type="hidden" value="1" name="data[OrderedItem][addon][quantity][]"/></td>
					<td class="amount">$ <?php echo number_format(($o['addon']['price'] * $o['addon']['quantity']), 2) // should do this multiplication in the controller ?></td>
				</tr>
				<?php } ?>
				<tr><td colspan="6"><div class="item-category-border"></div></td></tr>
				<?php } ?>
				<?php } ?>
		</table>
		
		<div class="subtotal">
			<dl>
				<dt>Subtotal:</dt>
				<dd>$<?php echo number_format($subtotal,2) ?></dd>
			</dl>
			<dl class="shipping">
				<dt>Shipping:</dt>
				<dd><?php echo number_format($order['shipping_type'],2) ?></dd>
			</dl>
			<?php if(isset($sales_tax_amount) && $sales_tax_amount >0) { ?>
			<dl>
				<dt>Sales Tax:</dt>
				<dd>$<?php echo number_format($sales_tax_amount,2) ?></dd>
			</dl>
			<?php } ?>
			<?php if(isset($trade_professional_discount) && $trade_professional_discount >0 ) { ?>
			<dl>
				<dt>15% Trade Discount:</dt>
				<dd><?php echo '- $' . number_format($trade_professional_discount, 2) ?></dd>
			</dl>
			<?php } ?>
		</div>
		
		<dl class="total">
			<dt>Total:</dt>
			<dd>$<?php echo number_format($total, 2) ?></dd>
		</dl>
		
		<dl class="place-order">
			<dd><a class="button white-text red-background" href="/orders/add_order/">Place Order</a></dd>
		</dl>
		
		<div class="order-info-summary">
			<div class="item-category-border"></div>
			<div>
				<dl>
					<dd><h2>Billing Info <a href="/orders/billing/" class="brown-background small-button white-text">Edit</a></h2></dd>
					<dd>
					<?php
						echo $person['first_name'] . '&#160;' . $person['last_name'] . '<br/>';
						echo $person['address_1'] . '<br/>';
						if (isset($person['address_2']) && $person['address_2'] !== '') {
							echo $person['address_2'] . '<br/>';
						}
						echo $person['city'] . ', ' . $person['state'] . '&#160;' .$person['zipcode'] . '<br/>';
						if(isset($person['phone_number']) && $person['phone_number'] !== '') {
							echo $person['phone_number']  .'<br/>';
						}
						echo '<br/>' . $person['email'];
					?>
					</dd>
				</dl>
				<dl>
					<dd><h2>Payment Info <a href="/orders/payment/" class="brown-background small-button white-text">Edit</a></h2></dd>
					<dd>
						<?php 
							echo $person['first_name'] . '&#160;' .  $person['last_name']. '<br/>';
							echo $creditcard['type'] . '<br/>';
							echo $creditcard['number'] . '<br/>';
							echo $creditcard['security_code'] . '<br/>';
							echo $creditcard['expiration_date_month']. '/' . $creditcard['expiration_date_year'] . '<br/>';
							?>
					</dd>
				</dl>
				<dl>
					<dd><h2>Shipping Info <a href="/orders/billing/" class="brown-background small-button white-text">Edit</a></h2></dd>
					<dd>
						Free <?php echo $order['shipping_type']?>
					</dd>
				</dl>
			</div>
		</div>
		
	</div>
	<div class="item-category-border"></div>
</div>