$(document).ready(function() {
	$('a.image').fancybox();
	
	$('.product-visuals dl dd').click(function(){
	
		var image_path = $(this).children('.hidden').attr('src');
		
		var m_image_file = image_path.replace('&w=74&h=74','&w=400');
		
		var l_image_file = image_path.replace('&w=74&h=74','&w=600');
		
		$('.product-visuals .image img').attr('src',m_image_file);
		
		$('.product-visuals .image').attr('href',l_image_file);
		
		$('.product-visuals dl dd').attr('class','');
		
		$(this).attr('class','active');
		
	});
	
});