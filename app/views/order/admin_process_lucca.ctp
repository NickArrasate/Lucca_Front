<?php
	$javascript->link('jquery', false);
	$javascript->link('jquery.ui', false);
	$javascript->link('jquery.cookie', false);
	$javascript->link('item_admin_grid', false);
?>
<div class="process-lucca-filter">
<select name="itemTypesFilter">
<?php foreach ($itemTypesFilter as $id => $name): ?>
	<option value="<?php echo $id; ?>"><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
</div>
<?php if (!empty($orders)): ?>
<div class="process-lucca-paging"><?php echo $paginator->numbers(); ?></div>
<table class="lucca-process">
<tr>
	<th></th>
	<th>Name</th>
	<th>Description</th>
	<th>NY</th>
	<th>LA</th>
	<th>WH</th>
	<th></th>
</tr>
<?php foreach($orders as $order): ?>
<tr class="item">
	<td class="image"><?php echo $html->image($resizeimage->resize(WWW_ROOT . '/files/'. $order['ItemImage']['filename'], array('w' => 100, 'crop' => 1))); ?></td>
	<td class="name"><?php echo $html->link($order['Item']['name'], array('controller' => 'item', 'action' => 'summary', 'prefix' => 'admin', $order['Item']['id'])); ?></td>
	<td class="description"><?php echo $order['Item']['description']; ?></td>
	<td class="quantity"><?php echo $order['ItemNY']['quantity']; ?></td>
	<td class="quantity"><?php echo $order['ItemLA']['quantity']; ?></td>
	<td class="quantity"><?php echo $order['ItemWH']['quantity']; ?></td>
	<td class="note-link"><?php echo $html->link(sprintf('order notes(%s)', 1), ''); ?></td>
</tr>
<tr>
	<td></td>
	<td colspan="6">
<div class="notesPlace">
<div class="header">Notes</div>

<div class="notesForm">
<form id="itemNotes" method="post" action="/admin/item/save_note/">
<input type="hidden" name="data[Note][item]" value="<?php echo $order['Item']['id']; ?>" />
<textarea name="data[Note][note]">Start Typing...</textarea>
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
				<option value="<?php echo $id; ?>"><?php echo $text; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type="submit" value="Save" class="button gray-background black-text"/>
</div>
</form>
</div>
<div class="notesList">
	<?php foreach ($order['Note'] as $note): ?>
		<div class="note">
			<p><?php echo date('D, j M \a\t g:ia', strtotime($note['Note']['created'])); ?></p>
			<p><?php echo $note['Note']['note']; ?></p>
			<div class="bottomMenu"><a href="#">edit</a><input type="hidden" value="<?php echo $note['Note']['id']; ?>" name="id" />&nbsp;|&nbsp;<a href="#">comments(<?php echo count($note['Comments']); ?>)</a></div>
			<div class="commentForm">
				<?php foreach ($note['Comments'] as $comment): ?>
					<div>
						<p><?php echo date('D, j M \a\t g:ia', strtotime($comment['created'])); ?></p>
						<p><?php echo $comment['note']; ?></p>
						<div class="bottomMenu"><a href="#">edit</a><input type="hidden" value="<?php echo $comment['id']; ?>" name="id" /></div>
					</div>
				<?php endforeach; ?>
				<form method="post" action="/admin/item/save_note/">
				<input type="hidden" name="data[Note][item]" value="<?php echo $order['Item']['id']; ?>" />
				<input type="hidden" name="data[Note][parent]" value="<?php echo $note['Note']['id']; ?>" />
				<textarea name="data[Note][note]">Start Typing...</textarea>
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
								<option value="<?php echo $id; ?>"><?php echo $text; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<input type="submit" value="Save" class="button gray-background black-text"/>
				</div>
				</form>
			</div>
		</div>
	<?php endforeach; ?>
</div>
<?php if ($order['NoteCount'] > 10): ?>
<div class="view-all-notes"><?php echo $html->link('view all notes', array('controller' => 'item', 'action' => 'summary', 'prefix' => 'admin', $order['Item']['id'])); ?></div>
<?php endif; ?>
	</td>
</tr>
<?php endforeach; ?>
</table>
<div class="process-lucca-paging"><?php echo $paginator->numbers(); ?></div>
<?php else: ?>
<?php endif; ?>
