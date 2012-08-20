	$(document).ready(function() {
	
	
		$('.notesPlace textarea').focus(function () {
			if ($(this).val() == 'Start Typing...') {
				$(this).css('color', '#000000').val("");
			}
			$(this).parent().children('.hiddenFields').slideDown('slow');
		});
		$('.notesPlace textarea').blur(function () {
			if ($(this).val() == "") {
				$(this).css('color', '#9B9B99').val("Start Typing...");
				$(this).parent().children('.hiddenFields').slideUp('slow');
			}
		});
		/*
		$('.notesPlace textarea').keypress(function () {
			$(this).parent().children('.hiddenFields').slideDown();
		});
		*/
		$('div.bottomMenu a:first-child').click(function () {
			link = this;
			$.ajax({
				url: '/admin/item/edit_note/' + $(link).parent().children('input').val(),
				type: 'GET',
				dataType: 'html',
				success: function (html) {
					$(link).parent().hide();
					container = $(link).parent().parent();
					container.children('p').hide();
					container.prepend(html);
					container.children('form').css('width', '70%');
					container.children('form').children('textarea').css('color', '#000000');
					container.children('form').children('.hiddenFields').css('width', '70%').show();
					container.find('div.cancel-edit-note').find('a').click(function (event) {
						event.preventDefault();
						
						$(this).parent().parent().parent().parent()
							.find('.bottomMenu').show().parent()
							.children('p').show().parent()
							.children('form').hide();
					});
					container.children('form').submit(function () {
						form = this;
						$.ajax({
							url: '/admin/item/edit_note/' + $(this).children('input[name="data[Note][id]"]').val(),
							type: 'POST',
							data: $(form).serialize(),
							dataType: 'html',
							success: function (html) {
								container.children('form').remove();
								container.prepend(html);
								$(link).parent().show();
							}
						});
						return false;
					});
				}
			})
			return false;
		});
		$('div.bottomMenu a:last-child').click(function () {
			$(this).parent().parent().children('.commentForm').slideToggle('slow');
			return false;
		});
		$('dl.subheader select').change(function (event) {
			var category = $('select[name="data[categories]"]').val();
			var subcategory = $('select[name="data[subcategories]"]').val();
			var location = $('select[name="data[locations]"]').val();
			var other = $('select[name="data[other]"]').val();
			
			item_status = $('#item_statuses .active').text();
			item_status = item_status.replace(/ \([0-9]+\)/, '');
			if(item_status == 'Works in Progress'){
				item_status = 'Unpublished';
			}

			pagination_status = $('.pagination a.underline').text();
			if (pagination_status == 'View All' || pagination_status == '') {
				pagination_status = '';
			} else {
				pagination_status = '/all';
			}

			var path = {
				'prefix': '',
				'controller': '',
				'action': '',
				'params': ''
			};

			var currentPath = window.location;
			var newPath = currentPath.protocol + '//' + currentPath.host + '/';
			var parsedPath = currentPath.pathname.replace(/^\/|\/$/g, '').split('/');
			if (parsedPath[0] == 'admin') {
				path.prefix = 'admin/';
				parsedPath.shift();
			}
			path.controller = parsedPath.shift() + '/';
			path.action = parsedPath.shift() + '/';
			if (!parsedPath.length) {
				path.params = parsedPath.join('/');
			}

			var filterParams = category + '/' + item_status + pagination_status + '/subcategory:' + subcategory + '/location:' + location + '/other:' + other;
		
//			window.location.href = '/admin/item/grid/' + category + '/' + item_status + pagination_status + '/subcategory:' + subcategory + '/location:' + location + '/other:' + other;
			window.location.href = newPath + path.prefix + path.controller + path.action + filterParams;
		});

		$('dl.subnavigation form').submit(function (event) {
			event.preventDefault();

			var item_status = $('#item_statuses .active').text();
			item_status = item_status.replace(/ \([0-9]+\)/, '');
			if(item_status == 'Works in Progress'){
				item_status = 'Unpublished';
			}

			var pagination_status = $('.pagination a.underline').text();
			if (pagination_status == 'View All' || pagination_status == '') {
				pagination_status = '';
			} else {
				pagination_status = '/all';
			}

			var path = {
				'prefix': '',
				'controller': '',
				'action': '',
				'params': ''
			};

			var currentPath = window.location;
			var newPath = currentPath.protocol + '//' + currentPath.host + '/';
			var parsedPath = currentPath.pathname.replace(/^\/|\/$/g, '').split('/');
			if (parsedPath[0] == 'admin') {
				path.prefix = 'admin/';
				parsedPath.shift();
			}
			path.controller = parsedPath.shift() + '/';
			path.action = 'search/';
			parsedPath.shift();
			if (parsedPath.length) {
				path.params = parsedPath.join('/');
			}

			var filterParams = 'all/' + item_status + pagination_status + '/subcategory:all/location:all/other:all';
			var queryString = '/search:' + $(this).find('input[type="text"]').val() + '/';

//			window.location.href = '/admin/item/grid/' + category + '/' + item_status + pagination_status + '/subcategory:' + subcategory + '/location:' + location + '/other:' + other;
			$(this).get(0).setAttribute('action', newPath + path.prefix + path.controller + path.action + filterParams + queryString);
			$(this).get(0).submit();
		}); 
		
		$('#noteFilter').change(function () {
			selectedSortMode = $('#noteFilter').val();
			if (selectedSortMode != "") {
				textSortMode = "/filter:" + selectedSortMode;
			}
			selectedItemId = $('input[name="data[Note][item]"]').val();
			window.location.href = '/admin/item/summary/' + selectedItemId + textSortMode;
		});

		if ($.isFunction($().dragsort)) {
		$("ul.grid-output").dragsort(
			{ 
				dragSelector: "li.item", 
				dragEnd: function() {
					var curLeft = parseInt($(this).attr('left'));
					var nextLeft = $(this).next().attr('left') || false;
					var prevLeft = $(this).prev().attr('left') || false;

					var data = {
						current: curLeft,
						next: nextLeft,
						prev: prevLeft,
						occurrence: {
							category: $('select[name="data[categories]"]').val() || 0,
							subcategory: $('select[name="data[subcategories]"]').val() || 0,
							location: $('select[name="data[locations]"]').val() || 0
						}
					};

					if (nextLeft && parseInt(nextLeft) < curLeft) {
						nextLeft = parseInt(nextLeft);

						var selector = [];
						for (i = nextLeft; i < curLeft; i += 2) {
							selector.push("li[left=" + i + "]");
						}
						
						var movedItems = $.makeArray($(selector.join(",")));

						$(this).attr('left', $(this).next().attr('left'));
						$(this).attr('right', $(this).next().attr('right'));

						for (itemIndex in movedItems) {
							$(movedItems[itemIndex]).attr('left', parseInt($(movedItems[itemIndex]).attr('left')) + 2);
							$(movedItems[itemIndex]).attr('right', parseInt($(movedItems[itemIndex]).attr('right')) + 2);
						}
					}

					if (prevLeft && parseInt(prevLeft) > curLeft) {
						prevLeft = parseInt(prevLeft);

						var selector = [];
						for (i = (curLeft + 2); i <= prevLeft; i += 2) {
							selector.push("li[left=" + i + "]");
						}
						
						var movedItems = $.makeArray($(selector.join(",")));

						$(this).attr('left', $(this).prev().attr('left'));
						$(this).attr('right', $(this).prev().attr('right'));

						for (itemIndex in movedItems) {
							$(movedItems[itemIndex]).attr('left', parseInt($(movedItems[itemIndex]).attr('left')) - 2);
							$(movedItems[itemIndex]).attr('right', parseInt($(movedItems[itemIndex]).attr('right')) - 2);
						}
					}

					$.post(
						'/admin/item/reordering',
						{data: JSON.stringify(data)},
						function (response) {}
					);
				}, 
				dragBetween: false, 
				placeHolderTemplate: '<li class="placeholder"></li>'
			}
		);
		}
		
		$('#ItemLuccaOriginal').change(function (event) {
			if ($(this).is(':checked')) {
				$('dd.little-input input').attr('readonly', true).css('background-color', '#E6E6E6');
				$('select[name="data[Item][parent_id]"]').attr('disabled', 'disabled');
			} else {
				$('dd.little-input input').attr('readonly', false).css('background-color', '#FFFFFF');
				$('select[name="data[Item][parent_id]"]').attr('disabled', false);
			}
		});
		
		if ($.isFunction($().dialog)) {
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
		}
		
		$('.delete-item').click(function(e){
			$.cookie("delete_url", $(this).attr('href'));
				e.preventDefault();
				// modal then run the function
				 $('#delete-dialog').dialog('open');
				return false;
			
		});
		if ($.isFunction($().cookie)) {
		$.cookie("delete_url", null);
		}
});
