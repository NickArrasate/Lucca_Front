<?php if (!empty($itemsWithNotes) || !empty($itemsWithOrders)): ?>
	<?php if (!empty($itemsWithOrders)): ?>
		<h1>Orders</h1>
		<table border="0" width="100%">
			<?php foreach ($itemsWithOrders as $item): ?>
				<tr><td colspan="3"><?php echo $html->link($item['Item']['name'], LUCCA_DOMAIN . '/admin' . Router::url(array('controller' => 'item', 'action' => 'summary', $item['Item']['id']), true)); ?></td></tr>
					<?php foreach ($item['Orders'] as $note): ?>
						<tr><td>&nbsp;</td><td colspan="2"><?php echo date('d/m/Y') . ' - ' . $note['note']; ?></td></td>
						<?php if (!empty($note['Comments'])): ?>
								<?php foreach ($note['Comments'] as $comment): ?>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td><?php echo date('d/m/Y') . ' - ' . $comment['note']; ?></td></tr>
								<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
			<?php endforeach; ?>
		</table>
		<br/>
	<?php endif; ?>
	<?php if (!empty($itemsWithNotes)): ?>
		<h1>All Notes</h1>
		<table border="0" width="100%">
			<?php foreach ($itemsWithNotes as $item): ?>
				<tr>
					<td rowspan="2" width="120"><?php echo $html->image(LUCCA_DOMAIN . Router::url($resizeimage->resize(WWW_ROOT . '/files/'. $item['ItemImage'][0]['filename'], array('w' => 100, 'h' => 100, 'crop' => 1)), true)); ?></td>
					<td colspan="2"><?php echo $html->link($item['Item']['name'], LUCCA_DOMAIN . '/admin' . Router::url(array('controller' => 'item', 'action' => 'summary', $item['Item']['id']), true)); ?></td>
				</tr>
				<tr><td colspan="2"><?php echo $item['Item']['description']; ?></td></tr>
					<?php foreach ($item['AllNotes'] as $note): ?>
						<tr><td>&nbsp;</td><td colspan="2"><?php echo date('d/m/Y') . ' - ' . $note['note']; ?></td></td>
						<?php if (!empty($note['Comments'])): ?>
								<?php foreach ($note['Comments'] as $comment): ?>
								<tr><td>&nbsp;</td><td>&nbsp;</td><td><?php echo date('d/m/Y') . ' - ' . $comment['note']; ?></td></tr>
								<?php endforeach; ?>
						<?php endif; ?>
					<?php endforeach; ?>
				<tr><td colspan="3"><hr/></td></tr>
			<?php endforeach; ?>
		</table>
		<br/>
	<?php endif; ?>
<?php else: ?>
	<h1>New orders or notes for previous week not found</h1>
<?php endif; ?>
