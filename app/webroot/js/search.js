$(document).ready(function () {
	$('li.search').click(function (event) {
		if (event.target.tagName == event.currentTarget.tagName) {
			$(this).parent().find('div.searchform').toggle();
		}
	});

	$('li.search form').submit(function (event) {
		event.preventDefault();

		var path = {
			'prefix': '',
			'controller': 'item/',
			'action': 'search/',
			'params': 'all/all/all'
		};

		var currentPath = window.location;
		var newPath = currentPath.protocol + '//' + currentPath.host + '/';

		var queryString = '/search:' + $(this).find('input[type="text"]').val() + '/';

		$(this).get(0).setAttribute('action', newPath + path.prefix + path.controller + path.action + path.params + queryString);
		$(this).get(0).submit();
	}); 
});
