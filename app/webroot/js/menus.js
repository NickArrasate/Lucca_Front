$(document).ready(function () {
	$('.menus .dropdown .locations').hover(function() {
		if (!$(this).hasClass('open')) {
			$('.dropdown-toggle').dropdown('toggle');
		}
	}, function() {
		if ($(this).hasClass('open')) {
			$('.dropdown-toggle').dropdown('toggle');
		}
	});

	$('.dropdown-toggle').off('click').click(function() {
		var link = $(this).attr('href');
		
		$(location).attr('href', link);
	});
});