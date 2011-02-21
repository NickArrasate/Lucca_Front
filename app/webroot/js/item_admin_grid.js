	$(document).ready(function() {
	
		$.cookie("delete_url", null);

		$('#itemNotes textarea').focus(function () {
			if ($(this).val() == 'Start Typing...') {
				$(this).val("");
			}
			$('.hiddenFields').show();
		});
		$('#itemNotes textarea').blur(function () {
			if ($(this).val() == "") {
				$(this).val("Start Typing...");
				$('.hiddenFields').hide();
			}
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
