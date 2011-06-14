	$(document).ready(function() {
	
		$.cookie("delete_url", null);
	
		$('.notesPlace textarea').focus(function () {
			if ($(this).val() == 'Start Typing...') {
				$(this).css('color', '#000000').val("");
			}
			$(this).parent().children('.hiddenFields').slideDown('slow');
		});
		$('.notesPlace textarea').blur(function () {
			if ($(this).val() == "") {
				$(this).css('color', '#9B9B99').val("Start Typing...");
				$(this).parent().children('.hiddenFields').slideUp('slow');
			}
		});
		/*
		$('.notesPlace textarea').keypress(function () {
			$(this).parent().children('.hiddenFields').slideDown();
		});
		*/
		$('div.bottomMenu a:first-child').click(function () {
			link = this;
			$.ajax({
				url: '/admin/item/edit_note/' + $(link).parent().children('input').val(),
				type: 'GET',
				dataType: 'html',
				success: function (html) {
					$(link).parent().hide();
					container = $(link).parent().parent();
					container.children('p').hide();
					container.prepend(html);
					container.children('form').css('width', '70%');
					container.children('form').children('textarea').css('color', '#000000');
					container.children('form').children('.hiddenFields').css('width', '70%').show();
					container.find('div.cancel-edit-note').find('a').click(function (event) {
						event.preventDefault();
						
						$(this).parent().parent().parent().parent()
							.find('.bottomMenu').show().parent()
							.children('p').show().parent()
							.children('form').hide();
					});
					container.children('form').submit(function () {
						form = this;
						$.ajax({
							url: '/admin/item/edit_note/' + $(this).children('input[name="data[Note][id]"]').val(),
							type: 'POST',
							data: $(form).serialize(),
							dataType: 'html',
							success: function (html) {
								container.children('form').remove();
								container.prepend(html);
								$(link).parent().show();
							}
						});
						return false;
					});
				}
			})
			return false;
		});
		$('div.bottomMenu a:last-child').click(function () {
			$(this).parent().parent().children('.commentForm').slideToggle('slow');
			return false;
		});
		$('dl.subheader select').change(function (event) {
			var category = $('select[name="data[categories]"]').val();
			var subcategory = $('select[name="data[subcategories]"]').val();
			var location = $('select[name="data[locations]"]').val();
			var other = $('select[name="data[other]"]').val();
			
			item_status = $('#item_statuses .active').text();
			item_status = item_status.replace(/ \([0-9]+\)/, '');
			if(item_status == 'Works in Progress'){
				item_status = 'Unpublished';
			}

			pagination_status = $('.pagination a.underline').text();
			if (pagination_status == 'View All' || pagination_status == '') {
				pagination_status = '';
			} else {
				pagination_status = '/all';
			}

			window.location.href = '/admin/item/grid/' + category + '/' + item_status + pagination_status + '/subcategory:' + subcategory + '/location:' + location + '/other:' + other;
		});
		
		$('#noteFilter').change(function () {
			selectedSortMode = $('#noteFilter').val();
			if (selectedSortMode != "") {
				textSortMode = "/filter:" + selectedSortMode;
			}
			selectedItemId = $('input[name="data[Note][item]"]').val();
			window.location.href = '/admin/item/summary/' + selectedItemId + textSortMode;
		});
		
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
		
		$('.delete-item').click(function(e){
			$.cookie("delete_url", $(this).attr('href'));
				e.preventDefault();
				// modal then run the function
				 $('#delete-dialog').dialog('open');
				return false;
			
		});
	
	});
