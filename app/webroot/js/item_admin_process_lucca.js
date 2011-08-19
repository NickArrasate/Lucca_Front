$(function () {
		$('.notesPlace').parent().parent().hide();
		$('.notesPlace').find('.notes').hide();
		$('.notesPlace').find('.orders').hide();
		$('tr.products-rows').fadeOut();
		$('td.note-link a.orders-block').click(function (event) {
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

		$('td.note-link a.notes-block').click(function (event) {
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

		$('td.note-link a.products-block').click(function (event) {
			event.preventDefault();

			if ($('tr[parentItem=' + $(this).attr('itemId') + ']').css('display') == 'none') {
				$('tr[parentItem=' + $(this).attr('itemId') + ']').fadeIn('slow');
			} else {
				$('tr[parentItem=' + $(this).attr('itemId') + ']').fadeOut('slow');
			}
		});

		$('#filter_item_type').change(function(){
			selectedItemType = $('#filter_item_type').val();
			
			window.location.href = '/admin/orders/process_lucca/' + selectedItemType + '/';
		});
});
