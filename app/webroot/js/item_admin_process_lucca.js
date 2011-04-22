$(function () {
		$('.notesPlace').parent().parent().hide();
		$('.notesPlace').find('.notes').hide();
		$('.notesPlace').find('.orders').hide();
		$('td.quantity').click(function () {
				formContainer = $(this).parent().next().find('td[colspan=7]');
				quantityContainers = $(this).parent().find('td.quantity');
				if (formContainer.length == 0) {
						formContainer = $(this).parent().after('<tr><td colspan="7" align="right"></td></tr>').next().find('td[colspan=7]');
						formContainer.css('padding', '0px');
						formContainer.html($('<div/>').addClass('details').width(190).css({'text-align': 'center', 'margin-right': '38px'})).find('div')
								.hide()
								.append('<dd class="little-input"><dl><dd><label>NY</label><input type="text" value="" name="data[InventoryQuantity][2]"></dd> </dl> </dd><dd class="little-input"> <dl> <dd><label>LA</label><input type="text" value="" name="data[InventoryQuantity][1]"></dd> </dl> </dd><dd class="little-input"> <dl> <dd><label>WH</label><input type="text" value="" name="data[InventoryQuantity][3]"></dd> </dl> </dd><dd><input type="submit" class="gray-background button black-text" value="Save Changes" name="submit"></dd>')
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
											formContainer.hide();
										}
									});
								});
				}
				formContainer.find('div').slideToggle('slow');
		});	
		$('td.note-link a:first-child').click(function (event) {
			event.preventDefault();

			formRowContainer = $(this).parent().parent().next();
			formContainer = formRowContainer.find('.notesArea');
			if (formContainer.length == 0) {
				formRowContainer = $(this).parent().parent().next().next();
				formContainer = formRowContainer.find('.notesArea');
			}

			if (formRowContainer.css('display') == 'none') {
				formRowContainer.fadeIn('slow');
				formContainer.find('.notes').slideUp('slow');
				formContainer.find('.notes').data('isDisplay', false);
				formContainer.find('input[type=checkbox][value=3]').attr('checked', true);
				formContainer.find('select').val(3);
				formContainer.find('textarea').focus();
			} else {
				if (formContainer.find('.notes').data('isDisplay')) {
					formContainer.find('.notes').slideToggle('slow');
					formContainer.find('.notes').data('isDisplay', false);
					formContainer.find('input[type=checkbox][value=3]').attr('checked', true);
					formContainer.find('select').val(3);
					formContainer.find('textarea').focus();
				} else {
					formRowContainer.fadeOut('slow');
				}
			}
			formRowContainer.find('.orders').slideToggle('slow');
			formRowContainer.find('.orders').data('isDisplay', true);
		});

		$('td.note-link a:last-child').click(function (event) {
			event.preventDefault();

			formRowContainer = $(this).parent().parent().next();
			formContainer = formRowContainer.find('.notesArea');
			if (formContainer.length == 0) {
				formRowContainer = $(this).parent().parent().next().next();
				formContainer = formRowContainer.find('.notesArea');
			}

			if (formRowContainer.css('display') == 'none') {
				formRowContainer.fadeIn('slow');
				formContainer.find('.orders').slideUp('slow');
				formContainer.find('.orders').data('isDisplay', false);
			} else {
				if (formContainer.find('.orders').data('isDisplay')) {
					formContainer.find('.orders').slideToggle('slow');
					formContainer.find('.orders').data('isDisplay', false);
					formContainer.find('input[type=checkbox][value=3]').attr('checked', false);
					formContainer.find('select').val(1);
				} else {
					formRowContainer.fadeOut('slow');
				}
			}
			formRowContainer.find('.notes').slideToggle('slow');
			formRowContainer.find('.notes').data('isDisplay', true);
		});
		$('#filter_item_type').change(function(){
			selectedItemType = $('#filter_item_type').val();
			
			window.location.href = '/admin/orders/process_lucca/' + selectedItemType + '/';
		});
});
