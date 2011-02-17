jQuery(function($) { 

	//initially the last three sections are hidden......
	$('#delivery_pickup').hide();
	$('#time_place').hide();
	$('#pay').hide();
	//....and the titles of each have edit buttons. 
	// if any of these edit buttons are clicked on, that sends an ajax request to the corresponding controller
	$('p.delivery_pickup').append('<a href="#">Edit</a>');
	$('p.time_place').append('<a href="#">Edit</a>');
	$('p.pay').append('<a href="#">Edit</a>');

	$('input[name="quantity"]').click(function(){
	
		var base_data = $('form[name="quantity"]').serialize();
		// i find it odd that the button data isnt being passed along. 
		var quantity = $(this).attr('value')
		var data = base_data + '&quantity=' + quantity;
		$.ajax({
		   type: "post",
		   // not sure why i have to put the entire url for the request to go through.....
		   url: "http://localhost/cupcakebits/order/deliveryPickup",
		   // quantity, flavor and discount sent out to the delivery_pickup action
		   data: data,
		   dataType: 'json',
		   success: function(response){
				// unhide the next section, and hide the current section
				$('#quantity').fadeOut();
				$('#delivery_pickup').fadeIn();
				// if the variables exists
				$('p.quantity').append(' : ' + response.quantity + '<a href="#">Edit</a>');
				$('#price_total').html(response.price);
		   },
		   error:function (xhr, ajaxOptions){
				alert(xhr.status);
			} 
		 });

	});
	
	
	$('input[name="pickup_delivery"]').click(function(){
	
		var base_data = $('form[name="pickup_delivery"]').serialize();
		// i find it odd that the button data isnt being passed along. 
		var pickup_delivery = $(this).attr('value')
		var data = base_data + '&pickup_delivery=' + pickup_delivery;
		$.ajax({
		   type: "post",
		   // not sure why i have to put the entire url for the request to go through.....
		   // i should probably route all of these requests to the same controller + an identifier to help route the request values to the appropriate controller
		   url: "http://localhost/cupcakebits/order/timePlace",
		   // quantity, flavor and discount sent out to the delivery_pickup action
		   data: data,
		   dataType: 'json',
		   success: function(response){
				// unhide the next section, and hide the current section
				$('#pickup_delivery').fadeOut();
				$('#time_place').fadeIn();
				// if the variables exists
				$('p.pickup_delivery').append(' : ' + response.pickup_delivery + '<a href="#">Edit</a>');
				$('#price_total').html(response.price);
		   },
		   error:function (xhr, ajaxOptions){
				alert(xhr.status);
			} 
		 });

	});
	
	
	
	

});