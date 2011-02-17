<?php
	echo $html->css('order');
	$this->pageTitle = 'Lucca Antiques - Thank You'; 
	//debug($order);
?>
<div class="c_checkout">
	<dl class="order-steps">
		<dd>1. Billing &amp; Shipping</dd>
		<dd>2. Payment Info</dd>
		<dd>3. Place Order</dd>
		<dd class="active last">4. Thank you!</dd>
	</dl>
		
	<div class="wrapper">
		
		<h2>Your order #<?php echo $order_id?> has been placed! Thank you!</h2>
		
		<p>Thank you for purchasing from Lucca Antiques. A receipt has been sent to <strong><?php echo $email ?></strong>. We very much appreciate your business. You may pick up your purchase from the shop or the warehouse. Please <a href="/pages/contact/">contact us to arrange a pickup date</a> for your order.</p>
		
		<dl class="button-wrap">
			<dd><a href="/" class="button red-background white-text">Lucca Homepage</a></dd>
		</dl>
		
		
		
	</div>
	<div class="item-category-border"></div>
</div>