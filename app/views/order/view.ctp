<?php
	echo $html->css('order');
	//print_r($shopping_cart);
	//debug($order);
	$this->pageTitle = 'Lucca Antiques - Shopping Cart'; 
?>
<div class="shoppingcart">
	<div class="wrapper">
		<h2>Your Shopping Cart</h2>
		
		<?php
			if(isset($order_error_messages)) {
			echo '<div class="notifications">';
				foreach($order_error_messages as $oe) {
					echo '<h6 class="error">* ' . $oe . '</h6>';
				}
			echo '</div>';
			}
		?>
		
		<form action="/orders/update_view/" method="post">
			<table>
				<tr>
					<th>SKU</th>
					<th>Item Details</th>
					<th class="price">Price</th>
					<th class="quantity">Quantity</th>
					<th class="amount">Amount</th>
					<th>&#160;</th>
				</tr>
				<tr><td colspan="6"><div class="solid-gray-line"></div></td></tr>
				<?php if(count($ordered_items) >= 1) {$i = 0 ; ?>
				<?php foreach($ordered_items as $o) { $i++; ?>
				
				<tr>
					<td class="sku"><?php echo $o['sku'] ?><input type="hidden" name="data[OrderedItem][item_variation_id][]" value="<?php echo $o['item_variation_id'] ?>"></td>
					<td>
						<dl class="item-name">
							<dt class="title">
								<a href="/item/details/<?php echo $o['id'] ?>">
								<?php echo $o['name'] ?>
								</a>
							</dt>
							<?php if(isset($o['item_variation_name'])) { ?>
							<dd><em><?php echo $o['item_variation_name']?></em></dd>
							<?php } ?>
						</dl>
					</td>
					<td class="price">$<?php echo number_format($o['price'], 2) ?></td>
					<td class="quantity"><input type="text" name="data[OrderedItem][quantity][]" value="<?php echo $o['quantity'] ?>"></input></td>
					<td class="amount">$<?php echo number_format($o['price'] * $o['quantity'],2); ?></td>
					<td class="remove"><a href="/orders/delete_item/<?php echo ($i-1) . '/' . $o['quantity'] ?>" class="brown-background small-button white-text">Remove</a></td>
				</tr>
				
				<?php if( isset($o['addon']) && $o['addon']['price'] !== '') { ?>
				<tr>
					<td><?php echo $o['addon']['sku'] ?></td>
					<td>
						<dl>
							<dt><?php echo $o['addon']['name'] ?></dt>
						</dl>
					</td>
					<td class="price">$ <?php echo number_format($o['addon']['price'],2) ?></td>
					<td class="quantity"><input type="text" value="<?php echo $o['addon']['quantity']?>" name="data[OrderedItem][addon][quantity][]"/></td>
					<td class="amount">$ <?php echo number_format(($o['addon']['price'] * $o['addon']['quantity']), 2) // should do this multiplication in the controller ?></td>
					<td class="remove"><a href="/orders/delete_option/<?php echo ($i-1) . '/1' ?>" class="brown-background small-button white-text">Remove</a></td>
				</tr>
				<?php } ?>
				
				<tr><td colspan="6"><div class="item-category-border"></div></td></tr>
				<?php } ?>
				<?php } ?>

			</table>
			
			<input type="submit" class="brown-background small-button white-text update" value="Update"></input>
		</form>
		
		<div class="subtotal">
			<dl>
				<dt>Subtotal:</dt>
				<dd>$<?php echo number_format($subtotal, 2) ?></dd>
			</dl>
			<?php if($sales_tax_amount > 0) { ?>
			<dl>
				<dt>Sales Tax:</dt>
				<dd>$
				<?php echo number_format($sales_tax_amount, 2); ?>
				</dd>
			</dl>
			<?php } ?>
			<dl class="shipping">
				<dt>Shipping:</dt>
				<dd>In-Store Pickup</dd>
			</dl>
		</div>
		
		<dl class="total">
			<dt>Total:</dt>
			<dd>$<?php echo number_format($total, 2) ?></dd>
		</dl>
		
		<dl>
			<dd class="continue-shopping"><a class="button red-text gray-background" href="/item/grid/1/all/">Continue Shopping</a></dd>
			<dd class="checkout"><a class="button white-text red-background" href="/orders/billing/">Check Out</a></dd>
		</dl>
	</div>
	<div class="item-category-border"></div>
</div>

<?php

//debug($ordered_items);
			
			

