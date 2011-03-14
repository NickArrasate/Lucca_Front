<?php
	$javascript->link('jquery', false);
	$javascript->link('jquery.ui', false);
	$javascript->link('jquery.cookie', false);
	$javascript->link('item_admin_grid', false);
?>
<div class="process-lucca-filter">
<select name="itemTypesFilter" id="filter_item_type">
<?php foreach ($itemTypesFilter as $id => $name): ?>
	<option value="<?php echo $id; ?>" <?php echo ($selectedType == $id) ? 'selected="selected"' : ''; ?>><?php echo $name; ?></option>
<?php endforeach; ?>
</select>
</div>
<?php if (!empty($orders)): ?>
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
<?php foreach($orders as $order): ?>
<tr class="item">
	<td class="image"><?php echo $html->image($resizeimage->resize(WWW_ROOT . '/files/'. $order['ItemImage']['filename'], array('w' => 100, 'crop' => 1))); ?></td>
	<td class="name"><?php echo $html->link($order['Item']['name'], array('controller' => 'item', 'action' => 'summary', 'prefix' => 'admin', $order['Item']['id'])); ?></td>
	<td class="description"><?php echo $order['Item']['description']; ?></td>
	<td class="quantity"><?php echo $order['ItemNY']['quantity']; ?></td>
	<td class="quantity"><?php echo $order['ItemLA']['quantity']; ?></td>
	<td class="quantity"><?php echo $order['ItemWH']['quantity']; ?></td>
	<td class="note-link"><?php echo $html->link(sprintf('order notes(%s)', $order['NoteOrderCount']), ''); ?></td>
</tr>
<tr>
	<td></td>
	<td colspan="6">
		<div class="notesPlace">
		<div class="header"><span><?php echo sprintf('Notes (%s)', $order['NoteCount']); ?></span></div>
		<div class="notesArea">
		<div class="notesForm">
		<form id="itemNotes" method="post" action="/admin/orders/save_note/">
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
						<form method="post" action="/admin/orders/save_note/">
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
		</div>
	</td>
</tr>
<?php endforeach; ?>
</table>
<div class="process-lucca-paging"><?php echo $paginator->numbers(); ?></div>
	<script>
	$(function () {
		$('.header').css('float', 'none').parent().find('.notesArea').hide();
		$('.header span').click(function () {
			$(this).parent().parent().find('.notesArea').slideToggle('slow');
		})
		$('td.quantity').click(function () {
				formContainer = $(this).parent().next().find('td[colspan=7]');
				quantityContainers = $(this).parent().find('td.quantity');
				if (formContainer.length == 0) {
						formContainer = $(this).parent().after('<tr><td colspan="7" align="right"></td></tr>').next().find('td[colspan=7]');
						formContainer.html($('<div/>').addClass('details').width(300).css('text-align', 'center')).find('div')
								.hide()
								.append('<dd class="little-input"><dl><dd><label>LA</label><input type="text" value="" name="data[InventoryQuantity][1]"></dd> </dl> </dd><dd class="little-input"> <dl> <dd><label>NY</label><input type="text" value="" name="data[InventoryQuantity][2]"></dd> </dl> </dd><dd class="little-input"> <dl> <dd><label>WH</label><input type="text" value="" name="data[InventoryQuantity][3]"></dd> </dl> </dd><dd><input type="submit" class="gray-background button black-text" value="Save Changes" name="submit"></dd>')
								.find('label').css({'float': 'left', 'clear': 'both'}).parent()
								.find('input[name="data[InventoryQuantity][1]"]').val($(quantityContainers[1]).text()).parent().parent().parent().parent()
								.find('input[name="data[InventoryQuantity][2]"]').val($(quantityContainers[0]).text()).parent().parent().parent().parent()
								.find('input[name="data[InventoryQuantity][3]"]').val($(quantityContainers[2]).text()).parent().parent().parent().parent()
								.find('input[type=submit]') 
								.click(function (event) {
									event.preventDefault();

									$.post('/admin/orders/admin_update_quantity/'+item_id, formContainer.find('input').serialize(), function (response) {
										response = $.parseJSON(response);
										if (response.status) {
										}
									});
								});
				}
				formContainer.find('div').slideToggle('slow');
		});	
		});
	</script>
<?php else: ?>
<?php endif; ?>
