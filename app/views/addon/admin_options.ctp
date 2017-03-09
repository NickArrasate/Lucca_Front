<?php
	$javascript->link('jquery', false);
	$javascript->link('jquery.ui', false);
	$javascript->link('jquery.cookie', false);
?>

<script type="text/javascript">
	$(document).ready(function() {
	
		$.cookie("delete_url", null);
		
		$('table.stripe>tbody>tr:even').addClass("dark-background"); 
		$('#delete-dialog').dialog({
            autoOpen: false,
            width: 400,
            modal: true,
            resizable: false,
            buttons: {
                "Delete": function() {
					window.location.href = $.cookie("delete_url");
				},
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }
        });
		
		$('.delete-option').click(function(e){
			$.cookie("delete_url", $(this).attr('href'));
				e.preventDefault();
				// modal then run the function
				 $('#delete-dialog').dialog('open');
				return false;
			
		});
		
	});
</script>

<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>Are you sure you want to delete this option?</strong><p></div>


<div class="addons">
<div class="breadcrumbs">
	<a href="/admin/item/grid/all/Unpublished/">Product Management</a> > 
	<a href="/admin/addon/categories/edit/">Manage Addons</a> > 
	Edit Options
</div>

<div class="subheader">
<h3><?php echo $h3 ?></h3>
</div>
<div class="notifications">
<?php 
if(isset($errors_addon_option)) {
	foreach($errors_addon_option as $ei) {
		echo $ei .'<br/>';
	}
} 
?>
</div>
<?php if (count($options) > 0) { ?>
<form method="post" action="/admin/addon/options/save/<?php echo $addon_id ?>">
<table class="stripe">
	<tr>
		<th>SKU</th>
		<th>Name</th>
		<th>Price</th>
		<th>&#160;</th>
	</tr>
	<?php foreach($options as $o) { ?>
	<tr>
		<td><input type="hidden" value="<?php echo $o['Option']['id'] ?>" name="data[Option][id][]"/><?php echo $o['Option']['sku']?></td>
		<td><input type="text" value="<?php echo $o['Option']['name'] ?>" name="data[Option][name][]"/></td>
		<td><input type="text" value="<?php echo $o['Option']['price'] ?>" name="data[Option][price][]"/></td>
		<td><span class="gray-background button black-text"><a class="delete-option" href="/admin/addon/options/delete/<?php echo $o['Option']['id']?>/<?php echo $addon_id ?>/">Delete</span></a></td>
	</tr>
	<?php } ?>
</table>
<input type="submit" value="Save these Options" class="button gray-background black-text"/>
</form>
<?php } else { ?>
	<p class="notifications">There are currently no options</p>
<?php } ?>
<h4>Add Option</h4>
<form action="/admin/addon/options/add/<?php echo $addon_id ?>" method="post">
<input type="hidden" name="data[Option][addon_id]" value="<?php echo $addon_id ?>" />
	<dl>
		<dt>SKU</dt>
		<dd><input type="text" name="data[Option][sku]" value="<?php echo $option_sku ?>"/></dd>
	</dl>
	<dl>
		<dt>Name</dt>
		<dd><input type="text" name="data[Option][name]"/></dd>
	</dl>
	<dl>
		<dt>Price</dt>
		<dd><input type="text" name="data[Option][price]"/></dd>
	</dl>
	<dl>
		<dt>&#160;</dt>
		<dd><input type="submit" value="Add Option" class="gray-background black-text button"/></dd>
	</dl>
</form>
</div>
