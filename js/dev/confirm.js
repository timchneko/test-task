;(function() {
	$('document').ready(function() {
		// post editing
		$('.review').not('#revPreview').each(function() {
			var review = $(this);
			var edit = review.find('.edit');
			if (edit.length < 1) {
				return;
			}
			var commentTextNode = review.find('p');
			edit.click(function(event) {
				event.preventDefault();
				edit.hide();
				var form = review.find('form');
				if (form.length < 1) {
					form = $('.editForm').first().clone().insertAfter(commentTextNode);
					// save editing
					form.find('.save').click(function(event) {
						event.preventDefault();
						var request = {
							edit: 1,
							id: review.attr('id'), 
							text: textarea.val()
						};
						$.post('index.php', request, function(data, status) {
							if (!data || status != "success") return;
							form.addClass('hidden');
							commentTextNode.text(data);
							commentTextNode.show();
						});
						edit.show();
					});
					// cancel editing
					form.find('.cancel').click(function(event) {
						event.preventDefault();
						edit.show();
						form.addClass('hidden');
						commentTextNode.show();
					});
				}
				var textarea = form.find('textarea');
				textarea.text(commentTextNode.text());
				commentTextNode.after(form).hide();
				form.removeClass('hidden');
			});
			var confirm = function(event) {
				event.preventDefault();
				var request = {
					confirm: 1,
					id: review.attr('id')
				};
				$.post('index.php', request, function(data, status) {
					if (!data || status != "success") return;
					review.removeClass('alert-danger').addClass('alert-success');
					$(event.target).removeClass('btn-success').addClass('btn-danger').text('Отказать').off().click(cancel);
				});
			}
			var cancel = function(event) {
				event.preventDefault();
				var request = {
					confirm: 0,
					id: review.attr('id')
				};
				$.post('index.php', request, function(data, status) {
					if (!data || status != "success") return;
					review.removeClass('alert-success').addClass('alert-danger');
					$(event.target).removeClass('btn-danger').addClass('btn-success').text('Подтвердить').off().click(confirm);
				});
			}
			// confirm review
			review.find('.confirm').click(confirm);
		});
	});
})()