	$(document).ready(function() {
	
		$.cookie("delete_url", null);

		$('.notesPlace textarea').focus(function () {
			if ($(this).val() == 'Start Typing...') {
				$(this).css('color', '#000000').val("");
			}
		});
		$('.notesPlace textarea').blur(function () {
			if ($(this).val() == "") {
				$(this).css('color', '#9B9B99').val("Start Typing...");
				$(this).parent().children('.hiddenFields').hide();
			}
		});
		$('.notesPlace textarea').keypress(function () {
			$(this).parent().children('.hiddenFields').show();
		});
		$('div.bottomMenu a:first-child').click(function () {
			link = this;
			$.ajax({
				url: '/admin/item/edit_note/' + $(link).parent().children('input').val(),
				type: 'GET',
				dataType: 'html',
				success: function (html) {
					$(link).parent().hide();
					container = $(link).parent().parent();
					container.children('p').remove();
					container.prepend(html);
					container.children('form').css('width', '70%');
					container.children('form').children('textarea').css('color', '#000000');
					container.children('form').children('.hiddenFields').css('width', '70%').show();
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
			$(this).parent().parent().children('.commentForm').toggle();
			return false;
		});

	
		$('#select_item_type').change(function(){
			selectedItemType = $('#select_item_type').val();
			
			textSortMode = "";
			selectedSortMode = $('#filterMenu').val();
			if (selectedSortMode != "") {
				textSortMode = "/filter:" + selectedSortMode;
			}
			item_status = $('#item_statuses .active').text();
			item_status = item_status.replace(/ \([0-9]+\)/, '');
			if(item_status == 'Works in Progress'){
				item_status = 'Unpublished';
			}
			window.location.href = '/admin/item/grid/' + selectedItemType + '/' + item_status + textSortMode;
		});
		$('#filterMenu').change(function () {
			selectedItemType = $('#select_item_type').val();
			
			textSortMode = "";
			selectedSortMode = $('#filterMenu').val();
			if (selectedSortMode != "") {
				textSortMode = "/filter:" + selectedSortMode;
			}
			item_status = $('#item_statuses .active').text();
			item_status = item_status.replace(/ \([0-9]+\)/, '');
			if(item_status == 'Works in Progress'){
				item_status = 'Unpublished';
			}
			window.location.href = '/admin/item/grid/' + selectedItemType + '/' + item_status + textSortMode;
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

function applySelectedFilter() {
	selectedItemType = $('#select_item_type').val();
	
	textSortMode = "";
	selectedSortMode = $('#SortMenu').val();
	if (selectedSortMode != "") {
		textSortMode = "/sort:" + selectedSortMode;
	}
	item_status = $('#item_statuses .active').text();
	item_status = item_status.replace(/ \([0-9]+\)/, '');
	if(item_status == 'Works in Progress'){
		item_status = 'Unpublished';
	}
	window.location.href = '/admin/item/grid/' + selectedItemType + '/' + item_status + textSortMode;
}
