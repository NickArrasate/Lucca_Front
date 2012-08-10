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
			'params': ''
		};

		var currentPath = window.location;
		var newPath = currentPath.protocol + '//' + currentPath.host + '/';
		var parsedPath = currentPath.pathname.replace(/^\/|\/$/g, '').split('/');

		parsedPath.shift();
		parsedPath.shift();

		if (parsedPath.length) {
			for (var i = 0; i < parsedPath.length; i++) {
				if (parsedPath[i].match(/^(search|page)/) != null) {
					parsedPath.splice(i, 1);
					i--;
				}
			}

			path.params = parsedPath.join('/');
		} else {
			path.params = ["all", "all", "all"].join('/');
		}

		var queryString = '/search:' + $(this).find('input[type="text"]').val() + '/';

		$(this).get(0).setAttribute('action', newPath + path.prefix + path.controller + path.action + path.params + queryString);
		$(this).get(0).submit();
	}); 
});
