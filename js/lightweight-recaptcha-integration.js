function lightweight_captcha_send(site_key, action, callback /*, args */) {
	var args = Array.prototype.slice.call(arguments, 2);
	grecaptcha.ready(function() {
	    grecaptcha.execute(site_key, {action: action}).then(function(token) {
	    	args.unshift(token);
	    	callback.apply(this, args);
	    });
	});
}