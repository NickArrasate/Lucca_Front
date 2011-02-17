<img style="height:35px;" src="http://s78390.gridserver.com/img/lucca_logo.png" />
<div style="font-family: Helvetica; sans-serif; font-size:90%; width:100%">
<h2>An order has been placed at LuccaAntiques.com</h2>

<p>Below is a copy of the order. Please keep this receipt for your records.</p>

<h3>Order Number: <?php echo $order['id'] ?></h3>
<h3>Ordered Items</h3>
<table>
	<tr>
		<th style="text-align: left">SKU</th>
		<th style="text-align: left">Item Details</th>
		<th style="text-align: left" class="price">Price</th>
		<th style="text-align: left">Quantity</th>
		<th style="text-align: left" class="amount">Amount</th>
	</tr>
	<tr><td colspan="6" class="solid-line"></td></tr>
	<?php if(count($ordered_items) >= 1) {$i = 0 ; ?>
		<?php foreach($ordered_items as $o) { $i++; ?>
		
		<tr>
			<td><?php echo $o['sku']  ?></td>
			<td>
				<dl>
					<dt><?php echo $o['item_name'] ?></dt>
				</dl>
			</td>
			<td class="price">$<?php echo number_format($o['price'], 2, '.', ',') ?></td>
			<td class="quantity"><?php echo $o['quantity'] // if the qty is an antique?? not an input box.?></td>
			<td class="amount">$<?php echo number_format(($o['price'] * $o['quantity']), 2, '.', ',') ?></td>
		</tr>
		<?php } ?>
		<?php if( isset($o['addon']) && $o['addon']['price'] !== '') { ?>
				<tr>
					<td><?php echo $o['addon']['sku'] ?></td>
					<td>
						<dl>
							<dt><?php echo $o['addon']['name'] ?></dt>
						</dl>
					</td>
					<td class="price">$ <?php echo number_format($o['addon']['price'], 2, '.', ',') ?></td>
					<td class="quantity"><?php echo $o['addon']['quantity']?><input type="hidden" value="1" name="data[OrderedItem][addon][quantity][]"/></td>
					<td class="amount">$ <?php echo number_format(($o['addon']['price'] * $o['addon']['quantity']), 2, '.', ',') ?></td>
				</tr>
				<?php } ?>
			<tr><td colspan="6" class="dotted-line"></td></tr>
		<?php } ?>
</table>
<div class="subtotal">
<p>Subtotal: $<?php echo number_format($subtotal, 2, '.', ',') ?></p>

	<?php if(isset($sales_tax_amount) && $sales_tax_amount >0) { ?>
<p>Sales Tax: $<?php echo number_format($sales_tax_amount, 2, '.', ',') ?></p>

	<?php } ?>
	
	<?php if(isset($trade_professional_discount) && $trade_professional_discount >0 ) { ?>
	<p>15% Trade Discount: <?php echo '- $' . number_format($trade_professional_discount, 2, '.', ',') ?></p>
	<?php } ?>
</div>

<p><strong>Total: $<?php echo number_format($total, 2, '.', ',') ?></strong></p>


<h2>Billing Info </h2>
<dl>
<dd>
<?php
	echo $person['first_name'] . '&#160;' . $person['last_name'] . '<br/>';
	echo $person['address_1'] . '<br/>';
	if (isset($person['address_2']) && $person['address_2'] !== '') {
		echo $person['address_2'] . '<br/>';
	}
	echo $person['city'] . ', ' . $person['state'] . '&#160;' .$person['zipcode'] . '<br/>';
	if (isset($person['phone_number']) && $person['phone_number'] !== '') {
		echo $person['phone_number']  .'<br/>';
	}
	echo $person['email'];
?>
</dd>
</dl>
<dl>
<h2>Payment Info</h2>
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
<h2>Shipping Info</h2>
<dl>
<dd>
	Free <?php echo $order['shipping_type']; ?>
</dd>
</dl>
</div>