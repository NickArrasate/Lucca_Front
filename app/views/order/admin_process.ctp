
<?php
	$javascript->link('jquery', false);
	$javascript->link('timers', false);
	$javascript->link('jquery.ui', false);
	//debug($records);
?>

<script type="text/javascript">

	
	$(document).ready(function() {
	
		function delete_order(data) {
		
			//alert(data);
			
			$.ajax({
			   type: 'post',
			   url: '/admin/orders/delete/',
			   data: data,
			  // dataType: 'json',
			   success: function(response){
					//alert(response);
					//window.location.reload();

			   }
			 });
			 
		}
		
		$('table.stripe>tbody>tr:even').addClass("dark-background"); 

		// for deleting orders
		
		$('#delete-orders').click(function(){
			
			var data = $('form#order-list').serialize();
			if(data !== '') {
				// modal then run the function
				 $('#delete-dialog').dialog('open');
				return false;
				 
				
			} else {
				$('.notifications').text('* No orders were selected to be deleted.');
			}
			
		});
		
		$('#delete-dialog').dialog({
            autoOpen: false,
            width: 400,
            modal: true,
            resizable: false,
            buttons: {
                "Delete": function() {
					var data = $('form#order-list').serialize();
					delete_order(data);
					window.location.reload();
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }
        });
		
		$('#select-all').click(function(){
			$('input[type="checkbox"]').attr('checked', true);
		});
		
		$('#select-none').click(function(){
			$('input[type="checkbox"]').attr('checked', false);
		});
		
	});
</script>

<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>Are you sure you want to delete the selected orders?</strong><p></div>


<div class="breadcrumbs">
	<?php
	foreach($breadcrumbs as $b) {
		if($b == end($breadcrumbs)) {
			echo '<a href="'. $b['link'] .'">'. $b['title'] .'</a>';
		} else {
			echo '<a href="'. $b['link'] .'">'. $b['title'] .' > </a>';
		}
	}
	?>
</div>
<?php echo $html->link('Lucca Originals Products', array('action' => 'process_lucca'), array('class' => 'underline')); ?>
<h6 class="notifications">
<?php
if(isset($order_management_feedback_message)) {
	foreach($order_management_feedback_message as $om) {
		echo $om;
	}
}
?>
</h6>

<p><?php echo $process_page_note; ?></p>

<?php if(count($records) !== 0) { ?>
<?php

	switch($status) {
	
		case 'inventory_check':
			$action = 'payment_and_shipping';
		break;
		case 'payment_and_shipping';
			$action = 'shipped';
		break;
		case 'shipped':
			$action = 'returned';
		break;
	}

?>
<?php if ($status !== 'returned') { ?>
<form id="order-list" method="post" action="/admin/orders/change_status/<?php echo $status ?>">
<?php } ?>
<table class="stripe">
	<tr>
		<th class="checkbox">&#160;</th>
		<th class="no">Order Number</th>
		<th class="date">Order Date</th>
		<th class="name">Customer Name</th>
		<th class="amount">Amount</th>
	</tr>
	<?php foreach ( $records as $r ) { ?>
	<tr id="<?php echo $r['Order']['id'] ?>">
		<td class="checkbox"><input type="checkbox" name="data[Order][ids][]" value="<?php echo $r['Order']['id'] ?>"/></td>
		<td class="no"><a href="/admin/orders/detail/<?php echo $r['Order']['id'] ?>"><?php echo $r['Order']['id'] ?></a></td>
		<td class="date"><?php echo $r['Order']['date'] ?></td>
		<td class="name"><?php echo $r['Order']['person_name']['last_name'] .', ' . $r['Order']['person_name']['first_name'] ?></td>
		<td class="amount">$<?php echo number_format($r['Order']['totals']['total'], 2) ?></td>
	</tr>
	<?php } ?>
</table>
<p>
Select: <span id="select-all" class="underline">All</span> <span id="select-none" class="underline">None</span>
<?php if(!isset($view_all)) {?>
<span class="float-right underline"><a href="/admin/orders/process/<?php echo $status ?>/all/">View All Orders</a></span>
<?php } ?>
</p>
<?php if(!isset($view_all)) {?>
<div class="pagination">
<dl>
<?php 
$paginator->options(array('url' => $this->passedArgs));
?>
<?php echo '<dd>' . $paginator->numbers() . '</dd>'; ?>
<?php
	echo $paginator->prev('« ', null, null, array('class' => 'disabled'));
	echo $paginator->next(' »', null, null, array('class' => 'disabled'));
?> 
</dl>
</div>
<?php } ?>
<?php if ($status !== 'returned') { ?>
<input type="submit"  class="gray-background button black-text" value="Process"/>
<?php } ?>
<input type="button" class="gray-background button black-text" id="delete-orders" value="Delete" />
<?php
	} else {
		echo '<h3 class="center notifications">There are currently no orders under this process.</h3>';
	}

?>

</form>


<?php
//debug($records);
?>

