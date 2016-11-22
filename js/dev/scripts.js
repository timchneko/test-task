var switchPage = function(index) {
	$('.activePage').hide().removeClass('activePage');
	$('.index' + index).addClass('active').siblings().removeClass('active');
	$('.page' + index).addClass('activePage').show();
}

var loadPage = function(index) {
	if (!$('.page' + index).length) {
		$.post('index.php', {page: index}, function(data, status) {
			if (!data || status != "success") return;
			var div = document.createElement('div');
			$(div).addClass('page' + index).html(data).appendTo('.comments');
			switchPage(index);
		});
	} else {
		switchPage(index);
	}
}

$('document').ready(function() {
	$('.pagination').each(function() {
		// left arrow
		$(this).children().first().click(function() {
			var index = $('.pagination li.active').first().text();
			loadPage(index - 1);
		});
		// right arrow
		$(this).children().last().click(function() {
			var index = $('.pagination li.active').first().text();
			loadPage(+index + 1);
		});
	});
	// page index
	$('.pagination li.pageIndex').click(function() {
		if ($(this).hasClass('active')) return;
		loadPage($(this).text());
	});
	$('.preview').click(function() {
		var valid = $('#commentForm').valid();
		if (valid) {
			var request = {preview: 1};
			$('.form-control').each(function() { request[this.name] = this.value });
			$.post('index.php', request, function(data, status) {
				if (!data || status != "success") return;
				$('.modal-message').empty();
				$('.modal-message').prepend(data);
				$('.modal').modal();
			});
		}
	})
});