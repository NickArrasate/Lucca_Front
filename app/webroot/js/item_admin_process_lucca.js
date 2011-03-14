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
						formContainer.html($('<div/>').addClass('details').width(190).css({'text-align': 'center', 'margin-right': '38px'})).find('div')
								.hide()
								.append('<dd class="little-input"><dl><dd><label>LA</label><input type="text" value="" name="data[InventoryQuantity][1]"></dd> </dl> </dd><dd class="little-input"> <dl> <dd><label>NY</label><input type="text" value="" name="data[InventoryQuantity][2]"></dd> </dl> </dd><dd class="little-input"> <dl> <dd><label>WH</label><input type="text" value="" name="data[InventoryQuantity][3]"></dd> </dl> </dd><dd><input type="submit" class="gray-background button black-text" value="Save Changes" name="submit"></dd>')
								.find('dd.little-input').css('width', '60px').parent()
								.find('label').css({'float': 'left', 'clear': 'both'}).parent()
								.find('input[name="data[InventoryQuantity][1]"]').val($(quantityContainers[1]).text()).parent().parent().parent().parent()
								.find('input[name="data[InventoryQuantity][2]"]').val($(quantityContainers[0]).text()).parent().parent().parent().parent()
								.find('input[name="data[InventoryQuantity][3]"]').val($(quantityContainers[2]).text()).parent().parent().parent().parent()
								.find('input[type=submit]') 
								.click(function (event) {
									event.preventDefault();

									formContainer = $(this).parent().parent();
									item_id = formContainer.parent().parent().next().find('input[name="data[Note][item]"]').val();
									$.post('/admin/orders/update_quantity/'+item_id, formContainer.find('input').serialize(), function (response) {
										response = JSON.parse(response);
										if (response.succes) {
											productRow = $('#luccaItem_' + response.item_id);
											formContainer = productRow.next().find('div');
											quantityContainers = productRow.find('td.quantity');
											$(quantityContainers[1]).text(formContainer.find('input[name="data[InventoryQuantity][1]"]').val());
											$(quantityContainers[0]).text(formContainer.find('input[name="data[InventoryQuantity][2]"]').val());
											$(quantityContainers[2]).text(formContainer.find('input[name="data[InventoryQuantity][3]"]').val());
											formContainer.parent().parent().hide();
										}
									});
								});
				}
				formContainer.find('div').slideToggle('slow');
		});	
		$('td.note-link a').click(function (event) {
			event.preventDefault();

			formContainer = $(this).parent().parent().next().find('.notesForm');
			if (formContainer.length == 0) {
				formContainer = $(this).parent().parent().next().next().find('.notesForm');
			}
			formContainer.parent().show();
			formContainer.find('input[type=checkbox][value=3]').attr('checked', true);
			formContainer.find('select').val(3);
			formContainer.find('textarea').focus();
		});
		$('#filter_item_type').change(function(){
			selectedItemType = $('#filter_item_type').val();
			
			window.location.href = '/admin/orders/process_lucca/' + selectedItemType + '/';
		});
});
