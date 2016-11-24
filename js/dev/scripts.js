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

// Class for client-side image resizing
var Image = function() {
	this.state = 0;
	this.maxWidth = 320;
	this.maxHeight = 240;
	this.canvas = null;
	this.img = null;
	this.reader = null;
	this.type = null;
	this.load = function(file) {
		if (file instanceof File) {
			this.state = 0;
			this.canvas = this.canvas || document.createElement("canvas");
			this.img = this.img || document.createElement("img");
			this.reader = this.reader || new FileReader();
			this.type = file.type;
			var self = this;
			this.reader.onload = function(e) {
				self.img.src = e.target.result
				self.state = e.target.readyState;
			};
			this.reader.readAsDataURL(file);
		}
	};
	this.resize = function() {
		var width = this.img.width;
		var height = this.img.height;
		if (width > height) {
		  if (width > this.maxWidth) {
		    height *= this.maxWidth / width;
		    width = this.maxWidth;
		  }
		} else {
		  if (height > this.maxHeight) {
		    width *= this.maxHeight / height;
		    height = this.maxHeight;
		  }
		}
		this.canvas.width = width;
		this.canvas.height = height;
		this.canvas.getContext("2d").drawImage(this.img, 0, 0, width, height);
	};
	this.toDataURL = function() {
		return this.canvas.toDataURL(this.type);
	};
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
	var image = new Image();
	// load image into object
	$('.file-input').change(function() {
		image.load(this.files[0]);
	});
	// show comment preview
	$('.preview').click(function(event) {
		event.preventDefault();
		var valid = $('#commentForm').valid();
		if (valid) {
			$('.form-control').each(function() { 
				this.name != 'email' 	? $('#' + this.name + 'Preview').text(this.value) 
										: $('#emailPreview').attr('href', 'mailto:' + this.value);
			});
			if (image.state == 2) {
				window.img = image.resize();
				$('#imagePreview').attr('src', image.toDataURL());
			}
			$('.modal').modal();
		}
	});
	$("button[type='submit']").click(function(event) {
		event.preventDefault();
		var valid = $('#commentForm').valid();
		if (valid) {
			var request = {submit: 1};
			if (image.state == 2) {
				window.img = image.resize();
				request.image = image.toDataURL();
			}
			$('.form-control').each(function() { request[this.name] = this.value });
			$.post('index.php', request, function(data, status) {
				if (!data || status != "success") return;
				var comments = $('.comments').empty();
				comments.prepend(data);
			});
		}
	});
});