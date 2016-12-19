;(function() {
	var setCookie = function(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	var getCookie = function(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i = 0; i < ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0) == ' ') {
	            c = c.substring(1);
	        }
	        if (c.indexOf(name) == 0) {
	            return c.substring(name.length, c.length);
	        }
	    }
	    return "";
	}

	$('document').ready(function() {
		// form submit
		$("button[type='submit']").click(function(event) {
			event.preventDefault();
			var valid = $('#loginForm').valid();
			if (valid) {
				var request = {};
				$('.form-control').each(function() { request[this.name] = this.value });
				$.post('index.php', request, function(data, status) {
					if (!data || status != "success") return;
					setCookie('username', $('#inputUsername').val(), 1);
					setCookie('hash', data, 1);
					window.location = '/';
				});
			}
		});
	});
})()