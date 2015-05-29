$(document).ready(function() {
    
	$('#send_email').click(function(e) {
		$.ajax({
			url: "/trade/request_reset_pwd/",
			async: true,
			type: "POST",
			dataType: 'json',
			data: {"data" : {
					'Trade' : {
						'email' : $('#email').val()
					}
				}   
			},
			beforeSend: function() {
				if ($('#email').val() == '') {
					$('#forgot_form_message').text('Please enter your email');
					return false;
				}
			},
			success: function(result) {
				$('#forgot_form_message').text(result.message);
			},
			error: function(event) {
				console.log(event);
				$('#forgot_form_message').text("Password reset error");
			}
		}); 
		
	});

    
    
});