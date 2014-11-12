$(document).ready(function () {
	$('.menus .dropdown .locations').hover(function() {
		if (!$(this).hasClass('open')) {
			$('.dropdown-toggle').dropdown('toggle');
		}
	}, function() {
		if ($(this).hasClass('open')) {
			$('.dropdown-toggle').dropdown('toggle');
		}
	})
});