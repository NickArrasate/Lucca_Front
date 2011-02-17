	$(document).ready(function() {
	
		$.cookie("delete_url", null);
	
		$('#select_item_type').change(function(){
			var item_type = $(this).val();
			var item_status = $('#item_statuses .active').text();
			item_status = item_status.replace(/ \([0-9]+\)/, '');
			if(item_status == 'Works in Progress'){
				item_status = 'Unpublished';
			}
			window.location.href = '/admin/item/grid/' + item_type + '/' + item_status;
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