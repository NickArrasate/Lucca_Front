<?php
	$javascript->link('jquery', false);
	$javascript->link('jquery.ui', false);
	$javascript->link('jquery.cookie', false);
	$javascript->link('item_admin_grid', false);
	$javascript->link('item_admin_process_lucca', false);
?>
<div class="process-lucca-filter">
<select name="itemTypesFilter" id="filter_item_type">
<?php foreach ($itemTypesFilter as $id => $name): ?>
	<option value="<?php echo $id; ?>" <?php echo ($selectedType == $id) ? 'selected="selected"' : ''; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
</div>
<?php if (!empty($luccaOriginalItems)): ?>
<div class="process-lucca-paging">[<?php echo$html->link('view all', array('all', 'view_all')); ?>]&nbsp;<?php echo $paginator->numbers(); ?></div>
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
<?php foreach($luccaOriginalItems as $luccaOriginalItem): ?>
<tr class="item" id="<?php echo sprintf('luccaItem_%s', $luccaOriginalItem['Item']['id']); ?>">
	<td class="image"><?php echo $html->image($resizeimage->resize(WWW_ROOT . '/files/'. $luccaOriginalItem['ItemImage']['filename'], array('w' => 100, 'crop' => 1))); ?></td>
	<td class="name divider"><?php echo $html->link($luccaOriginalItem['Item']['name'], array('controller' => 'item', 'action' => 'summary', 'prefix' => 'admin', $luccaOriginalItem['Item']['id'])); ?></td>
	<td class="description divider"><?php echo $luccaOriginalItem['Item']['description']; ?></td>
	<td class="quantity divider"><?php echo $luccaOriginalItem['ItemNY']['quantity']; ?></td>
	<td class="quantity divider"><?php echo $luccaOriginalItem['ItemLA']['quantity']; ?></td>
	<td class="quantity divider"><?php echo $luccaOriginalItem['ItemWH']['quantity']; ?></td>
	<td class="note-link"><?php echo $html->link(sprintf('orders(%s)', $luccaOriginalItem['OrderCount']), ''); ?>&nbsp;|&nbsp;<?php echo $html->link(sprintf('notes(%s)', $luccaOriginalItem['NoteCount']), ''); ?></td>
</tr>
<tr>
	<td class="image">&nbsp;</td>
	<td colspan="6">
		<div class="notesPlace">
		<div class="notesArea">
		<div class="notesForm">
		<form id="itemNotes" method="post" action="/admin/orders/save_note/">
		<input type="hidden" name="data[Note][item]" value="<?php echo $luccaOriginalItem['Item']['id']; ?>" />
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
		<div class="notesList notes">
			<?php foreach ($luccaOriginalItem['Note'] as $note): ?>
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
						<form method="post" action="/admin/orders/save_note/">
						<input type="hidden" name="data[Note][item]" value="<?php echo $luccaOriginalItem['Item']['id']; ?>" />
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
		<div class="notesList orders">
			<?php foreach ($luccaOriginalItem['Order'] as $note): ?>
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
						<form method="post" action="/admin/orders/save_note/">
						<input type="hidden" name="data[Note][item]" value="<?php echo $luccaOriginalItem['Item']['id']; ?>" />
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
		<?php if ($luccaOriginalItem['NoteCount'] > 10): ?>
		<div class="view-all-notes"><?php echo $html->link('view all notes', array('controller' => 'item', 'action' => 'summary', 'prefix' => 'admin', $luccaOriginalItem['Item']['id'])); ?></div>
		<?php endif; ?>
		</div>
	</td>
</tr>
<?php endforeach; ?>
</table>
<div class="process-lucca-paging"><?php echo $paginator->numbers(); ?></div>
<?php else: ?>
<h3 class="center notifications">Theare are currently no lucca originals products</h3>
<?php endif; ?>
