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
		
		$('.delete-addon').click(function(e){
			$.cookie("delete_url", $(this).attr('href'));
				e.preventDefault();
				// modal then run the function
				 $('#delete-dialog').dialog('open');
				return false;
			
		});
	});
</script>

<div id="delete-dialog"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span><p><strong>Are you sure you want to delete this addon?</strong><p></div>


<div class="addons">
<div class="breadcrumbs">
	<a href="/admin/item/grid/all/Unpublished/">Product Management</a> > 
	Manage Addons
</div>
<div class="subheader">
<h3>Manage Addons</h3>
</div>
<div class="notifications">
<?php 
if(isset($errors_addon_category)) {
	foreach($errors_addon_category as $ei) {
		echo $ei .'<br/>';
	}
} 
?>
</div>

<?php if (count($addons) > 0) { ?>
<form method="post" action="/admin/addon/categories/save/">
<table class="stripe">
	<tr>
		<th>Add On Name</th>
		<th>Options</th>
		<th colspan="2"></th>
	</tr>
	
	<?php foreach($addons as $a) { ?>
	<tr>
		<td>
			<input type="hidden" value="<?php echo $a['Addon']['id']?>" name="data[Addon][id][]"/>
			<input type="text" value="<?php echo $a['Addon']['name']?>" name="data[Addon][name][]"/>
		</td>
		<td><?php echo $a['Addon']['option_count'] ?></td>
		<td><a class="button black-text gray-background" href="/admin/addon/options/edit/<?php echo $a['Addon']['id']?>">Edit Options</a></td>
		<td><span class="button black-text gray-background"><a class="delete-addon" href="/admin/addon/categories/delete/<?php echo $a['Addon']['id']?>">Delete</a></span></td>
	</tr>
	<?php } ?>
</table>
<input type="submit" value="Save these Addons" class="gray-background button black-text"/>
</form>
	<?php } else { ?>
		<p class="notifications">There are currently no addons</p>
	<?php } ?>


<h4>Create an Addon</h4>
<form action="/admin/addon/categories/add/" method="post">
	<dl class="create-addon">
		<dt>Name</dt>
		<dd><input type="text" name="data[Addon][name]"/></dd>
		<dd><input type="submit" class="button gray-background black-text" value="Create Addon"/></dd>
	</dl>
</form>
</div>