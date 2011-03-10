<?php if (isset($hasText) && $hasText): ?>
	<p><?php echo date('D, j M \a\t g:ia', strtotime($note['Note']['created'])); ?></p>
	<p><?php echo $note['Note']['note']; ?></p>
<?php else: ?>
<form method="post" action="/admin/item/edit_note/<?php echo $note['Note']['id'];?>">
	<input type="hidden" name="data[Note][id]" value="<?php echo $note['Note']['id']; ?>" />
	<input type="hidden" name="data[Note][item]" value="<?php echo $note['Note']['item']; ?>" />
	<input type="hidden" name="data[Note][parent]" value="<?php echo $note['Note']['parent']; ?>" />
	<textarea name="data[Note][note]"><?php echo$note['Note']['note']; ?></textarea>
	<div class="hiddenFields">
		<div class="subheading">To:</div>
			<div>
			<?php foreach ($locationsNames as $locationId => $locationNamesRecord): ?>
				<input type="checkbox" name="data[Note][to][]" value="<?php echo $locationId; ?>" /><?php echo $locationNamesRecord['shortName']; ?>
			<?php endforeach; ?>
		</div>
		<div class="subheading">Status:</div>
		<div>
			<select name="data[Note][status]">
				<?php foreach ($noteStatuses as $id => $text): ?>
					<option value="<?php echo $id; ?>" <?php echo (($id == $note['Note']['status']) ? 'selected="selected"' : ''); ?>><?php echo $text; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="cancel-edit-note"><a href="#">cancel</a></div>
		<input type="submit" value="Save" class="button gray-background black-text"/>
		<div class="delete-note"><a href="<?php echo $html->url(array('controller' => 'item', 'action' => 'delete_note', 'prefix' => 'admin', $note['Note']['id'])); ?>">delete</a></div>
	</div>
	</form>
<?php endif; ?>
