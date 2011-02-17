
<?php
	$javascript->link('jquery', false);
	$javascript->link('timers', false);
	$javascript->link('jquery.ui', false);
	//debug($products_in_order);
	//debug($totals);
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('table.stripe>tbody>tr:even').addClass("dark-background");
		
		// need a prompt for deleting.......when clicking on delete-item
		$('#delete-dialog').dialog({
            autoOpen: false,
            width: 400,
            modal: true,
            resizable: false,
            buttons: {
                "Delete": function() {
					// follow a link, not submit a form.....
					var link = $('#delete-order').attr('href');
					//alert(link);
                   window.location.href = link;
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }
        });

        $('#delete-order').click(function(){
            $('#delete-dialog').dialog('open');
            return false;
        });

	});
</script>

<div class="order-detail">

<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>
Are you sure you want to delete Order #<?php echo $order['Order']['id'] ?>?</strong><p></div>


<form action="/admin/orders/update_details/<?php echo $order['Order']['id'] ?>" method="post">
<input type="hidden" name="data[person_id]" value="<?php echo $order['Person']['id']?>"/>
<input type="hidden" name="data[cc_id]" value="<?php echo $order['Creditcard']['id']?>"/>

<div class="breadcrumbs">
	Order Management > Order Detail: #<?php echo $order['Order']['id']?>
</div>

<h4 class="notifications">
<?php
	if(isset($order_detail_feedback)) {
		foreach($order_detail_feedback as $odf) {
			echo $odf . '<br/>';
		}
	}
?>
</h4>


<h2>Order Detail: #<?php echo $order['Order']['id'] ?></h2>

<label>Status</label>
<select name="data[Order][status]">
<?php
	foreach($statuses as $s) {
		if($s['name'] == $order['Order']['status']) {
			echo '<option selected="selected" value="'. $s['name'] .'">'. $s['name'] .'</option>';
		} else {
			echo '<option value="'. $s['name'] .'">'. $s['name'] .'</option>';
		}
	}
?>
</select>

<h4>Products in Order: </h4>
<table class="stripe products-in-order">
	<tr>
		<th class="sku">SKU</th>
		<th class="category">Category</th>
		<th class="name">Product Name</th>
		<th class="variation">Variation</th>
		<th class="price">Price</th>
		<th class="qty">Quantity</th>
		<th class="amt">Amount</th>
	</tr>
	<?php foreach($products_in_order as $pio) {?>
	<tr>
		<td class="sku">
		<?php if(isset($pio['item_id'])) {?>
		<a target="_blank" href="/admin/item/summary/<?php echo $pio['item_id']?>">
		<?php echo $pio['item_variation_sku']?></a></td>
		<?php } else { ?>
		<?php echo $pio['item_variation_sku']?>
		<?php } ?>
		
		<td class="category"><?php echo $pio['item_category_name'] ?></td>
		<td class="name"><?php echo $pio['item_name']?></td>
		<td class="variation"><?php echo $pio['item_variation_name'] ?></td>
		<td class="price">$<?php echo number_format($pio['item_variation_price'], 2)?></td>
		<td class="qty"><input type="hidden" name="data[OrderedItem][id][]" value="<?php echo $pio['id']?>"/><input name="data[OrderedItem][quantity][]" type="text" value="<?php echo $pio['quantity']?>"/></td>
		<td class="amt">$<?php echo number_format($pio['item_variation_price'] * $pio['quantity'], 2 )?></td>
	</tr>
	<?php if(isset($pio['option_price'])) {?>
	<tr>
		<td><a target="_blank" href="/admin/addon/options/edit/<?php echo $pio['addon_id']?>"><?php echo $pio['option_sku']?></a></td>
		<td><?php ?></td>
		<td><?php echo $pio['addon_name'] ?></td>
		<td><?php echo $pio['option_name']?></td>
		<td class="price">$<?php echo number_format($pio['option_price'], 2)?></td>
		<td class="qty"><input type="text" name="data[OrderedItem][option_quantity][]" value="<?php echo $pio['option_quantity']?>"></td>
		<td class="amt">$<?php echo number_format($pio['option_price'] * 1, 2 )?></td>
	</tr>
	<?php } ?>
	<?php } ?>
</table>

<dl class="totals">
	<dt>Subtotal:</dt>
	<dd>$<?php echo number_format($totals['subtotal'], 2) ?></dd>
	<dt>Shipping Cost:</dt>
	<?php // this number format isnt ncessary once the shipping is inplace?>
	<dd>$<?php echo number_format($totals['shipping_cost'], 2) ?></dd>
	<dt>Tax:</dt>
	<dd>$<?php echo number_format($totals['sales_tax_amount'], 2)  ?></dd>
	<dt>Discount %</dt>
	<dd><input type="text" name="data[Order][discount]" value="<?php echo $totals['trade_professional_percentage'] ?>" /></dd>
	<dt>&#160;</dt>
	<dd>- $<?php echo number_format($totals['trade_professional_discount'],2) ?></dd>
</dl>

<dl class="total">
<dt>Total:</dt>
<dd>$<?php echo number_format($totals['total'],2) ?></dd>
</dl>

<div class="billing-and-shipping">
<div>
<h4>Billing Information: </h4>
<div>
<dl>
	<dt>First Name</dt>
	<dd><input type="text" name="data[Person][first_name]" value="<?php echo $billing_information['first_name']?>"/></dd>
</dl>
<dl>
	<dt>Last Name</dt>
	<dd><input type="text" name="data[Person][last_name]" value="<?php echo $billing_information['last_name']?>"/></dd>
</dl>
<dl>
	<dt>Address 1</dt>
	<dd><input type="text" name="data[Person][address_1]" value="<?php echo $billing_information['address_1']?>"/></dd>
</dl>
<dl>
	<dt>Address 2</dt>
	<dd><input type="text" name="data[Person][address_2]" value="<?php echo $billing_information['address_2']?>"/></dd>
</dl>
<dl>
	<dt>City</dt>
	<dd><input type="text" name="data[Person][city]" value="<?php echo $billing_information['city']?>"/></dd>
</dl>
<dl>
	<dt>State</dt>
	<dd><input type="text" name="data[Person][state]" value="<?php echo $billing_information['state']?>"/></dd>
</dl>
<dl>
	<dt>Zipcode</dt>
	<dd><input type="text" name="data[Person][zipcode]" value="<?php echo $billing_information['zipcode']?>"/></dd>
</dl>
<dl>
	<dt>Phone</dt>
	<dd><input type="text" name="data[Person][phone]" value="<?php echo $billing_information['phone_number']?>"/></dd>
</dl>
<dl>
	<dt>Email </dt>
	<dd><input type="text" name="data[Person][email]" value="<?php echo $billing_information['email']?>"/></dd>
</dl>
<dl>
	<dt>Card Type</dt>
	<dd>
	<select name="data[Creditcard][type]">
		<?php foreach($cc['type'] as $ct) {?>
			<?php if ($ct == $billing_information['type']) { ?>
			<option selected="selected" value="<?php echo $ct?>"><?php echo $ct ?></option>
			<?php }  else { ?>
			<option value="<?php echo $ct?>"><?php echo $ct ?></option>
			<?php } ?>
		<?php } ?>
	</select>
	</dd>
</dl>
<dl>
	<dt>Credit Card #: </dt>
	<dd><input name="data[Creditcard][number]" type="text" value="<?php echo $billing_information['number']?>"/></dd>
<dl>
	<dt>Expiration Date:</dt>
	<dd>
	<select name="data[Creditcard][expiration_date_month]">
		<?php foreach($cc['month'] as $key => $value) {?>
			<?php if ($key == $billing_information['expiration_date_month']) { ?>
			<option selected="selected" value="<?php echo $key?>"><?php echo $value ?></option>
			<?php }  else { ?>
			<option value="<?php echo $key?>"><?php echo $value ?></option>
			<?php } ?>
		<?php } ?>
	</select>
	<select name="data[Creditcard][expiration_date_year]">
		<?php foreach($cc['year'][0] as $cy) {?>
			<?php if ($cy == $billing_information['expiration_date_month']) { ?>
			<option selected="selected" value="<?php echo $cy?>"><?php echo $cy ?></option>
			<?php }  else { ?>
			<option value="<?php echo $cy?>"><?php echo $cy ?></option>
			<?php } ?>
		<?php } ?>
	</select>
	</dd>
</dl>
<dl>
	<dt>Security Code:</dt>
	<dd><input name="data[Creditcard][security_code]" type="text" value="<?php echo $billing_information['security_code']?>"/></dd>
</dl>
</div>
</div>
<div>
<h4>Shipping Information: </h4>
<div>
<?php // this condition isnt necessarily true. but is okay for now ?>
<?php if ( $order['Order']['shipping_type'] == 'LA Store Pickup' || $order['Order']['shipping_type'] == 'LA Warehouse Pickup' ) { ?>
<input type="checkbox" checked="checked" name="data[ShippingInformation][check]"/>

<?php } else { ?>
<input type="checkbox" name="data[ShippingInformation][check]"/>
<?php } ?>
 Shipping is Same as Billing
<?php // need to check the flag to checkmark this box or not. then, if the box is unchecked - then create a new entry and save it. if its not checked, make sure the entry doesn't exist (delete it if it exists)?>

<dl>
	<dt>First Name:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][first_name]" value="<?php echo $shipping_information['first_name']?>"/></dd>
</dl>
<dl>
	<dt>Last Name:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][last_name]" value="<?php echo $shipping_information['last_name']?>"/></dd>
</dl>
<dl>
	<dt>Address 1:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][address_1]" value="<?php echo $shipping_information['address_1']?>"/></dd>
</dl>
<dl>
	<dt>Address 2:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][address_2]" value="<?php echo $shipping_information['address_2']?>"/></dd>
</dl>
<dl>
	<dt>City:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][city]" value="<?php echo $shipping_information['city']?>"/></dd>
</dl>
<dl>
	<dt>State:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][state]" value="<?php echo $shipping_information['state']?>"/></dd>
</dl>
<dl>
	<dt>Zipcode:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][zipcode]" value="<?php echo $shipping_information['zipcode']?>"/></dd>
</dl>
<dl>
	<dt>Phone:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][phone_number]" value="<?php echo $shipping_information['phone_number']?>"/></dd>
</dl>
<dl>
	<dt>Email:</dt>
	<dd><input type="text" name="data[ShippingInformation][Person][email]" value="<?php echo $shipping_information['email']?>"/></dd>
</dl>
</div>
</div>
</div>

<h4>Shipping: </h4>

<?php
$shipping_types = array(
				'LA Store Pickup',
				'LA Warehouse Pickup'
			);
?>

<div class="shipping-method">
<dl>
	<dt>Shipping Method: </dt>
	<dd>
		<select name="data[Order][shipping_method]">
			<?php foreach ($shipping_types as $st) { ?>
			<?php if ($st == $order['Order']['shipping_type']) { ?>
			<option selected="selected" value="<?php  echo $st?>"><?php  echo $st?></option>
			<?php } else {  ?>
			<option value="<?php  echo $st ?>"><?php  echo $st ?></option>
			<?php } ?>
			<?php } ?>
		</select>	
	</dd>
</dl>
</div>

<h4>Store Comments: </h4>

<textarea name="data[Order][store_comments]" rows="3" cols="67">
<?php echo $order['Order']['store_comments']; 
?>
</textarea>

<div class="actions">
<input class="gray-background black-text button" type="submit" value="Save"/>
<a id="delete-order" class="gray-background black-text button" href="/admin/orders/delete/<?php echo $order['Order']['id'] ?>/<?php echo $order['Person']['id'] ?>">Delete</a>
</div>

</form>
</div>
<?php 
	//debug($products_in_order);
	//debug($order);
?>
