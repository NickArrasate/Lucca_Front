<?php
//print_r($autofills)
?>
<div class="autofill">
<div class="breadcrumbs"><a href="/admin/item/grid/all/Unpublished/"> Product Management</a> > Autofill Text</div>

<p>Change text that is automatically created when creating a new item</p>
<p class="notifications">
<?php
if(isset($autofill_feedback_message)) {
	foreach($autofill_feedback_message as $a ){
		echo $a;
	}
}
?>
</p>
<form action="save/" method="post"/>
<dl>
	<?php foreach($autofills as $a) { ?>
	<dd>
		<label><?php echo ucfirst($a['Autofill']['name']) ?></label>
		<textarea name="data[Autofill][content][]" cols="50"><?php echo $a['Autofill']['content'] ?></textarea>
		<input type="hidden" value="<?php echo $a['Autofill']['id'] ?>" name="data[Autofill][id][]" />
	</dd>
	<?php } ?>
</dl>
<input type="submit" class="button gray-background black-text" value="Save"/>
</form>
</div>