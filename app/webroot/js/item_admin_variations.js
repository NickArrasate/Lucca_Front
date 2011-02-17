$(document).ready(function() {
		$('table.stripe>tbody>tr:even').addClass("dark-background"); 
		
		$.cookie("delete_url", null);
		
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
		
		$('.delete-variation').click(function(e){
			$.cookie("delete_url", $(this).attr('href'));
				e.preventDefault();
				// modal then run the function
				 $('#delete-dialog').dialog('open');
				return false;
			
		});
	});