let LIGHTWEIGHT_CONTACT_NAME = 'lightweight_contact_form_name';
let LIGHTWEIGHT_CONTACT_EMAIL = 'lightweight_contact_form_email';
let LIGHTWEIGHT_CONTACT_POST_NAME = 'lightweight_contact_form_post_name';
let LIGHTWEIGHT_CONTACT_POST_EDITOR = 'lightweight_contact_form_editor';
let LIGHTWEIGHT_LOADER = 'lightweight-loading-icon-id';

function lightweight_contact_enable_loader() {
	document.getElementById(LIGHTWEIGHT_LOADER).style.display = 'flex';
}

function lightweight_contact_disable_loader() {
	document.getElementById(LIGHTWEIGHT_LOADER).style.display = 'none';
}

function lightweight_contact_form_send_captcha(token) {
	jQuery(function($) {
		lightweight_contact_enable_loader();
		let data = {
			'action': "submit_contact_form",
			'post_name': document.getElementById(LIGHTWEIGHT_CONTACT_POST_NAME).value,
			'post_editor': tinymce.get(LIGHTWEIGHT_CONTACT_POST_EDITOR).getContent(),
			'name': document.getElementById(LIGHTWEIGHT_CONTACT_NAME).value,
			'email': document.getElementById(LIGHTWEIGHT_CONTACT_EMAIL).value,
			'token': token,
			'action-captcha': lightweight_contact_form_vars.action,
		};
		$.ajax({type: 'POST', url:ajax_vars.ajax_url, data, dataType:'json', asynch: true, success:
				function(returnedData){
					if (returnedData.error) {
						window.alert(returnedData.errormsg);
						lightweight_contact_disable_loader();
					}
					else {
						let path = window.location.pathname;
						window.history.pushState("string", "Email sending succeeded", path + '?sent=true');
						location.reload();
						lightweight_contact_disable_loader();
					}
				}}).fail(function(){
					lightweight_contact_disable_loader();
					console.log("The server returned an error code.");
		});
	});
}

function lightweight_contact_form_send() {
	jQuery(function($) {
		lightweight_contact_enable_loader();
		let data = {
			'action': "submit_contact_form",
			'post_name': document.getElementById(LIGHTWEIGHT_CONTACT_POST_NAME).value,
			'post_editor': tinymce.get(LIGHTWEIGHT_CONTACT_POST_EDITOR).getContent(),
			'name': document.getElementById(LIGHTWEIGHT_CONTACT_NAME).value,
			'email': document.getElementById(LIGHTWEIGHT_CONTACT_EMAIL).value,
		};
		$.ajax({type: 'POST', url:ajax_vars.ajax_url, data, dataType:'json', asynch: true, success:
				function(returnedData){
					if (returnedData.error) {
						window.alert(returnedData.errormsg);
						lightweight_contact_disable_loader();
					}
					else {
						let path = window.location.pathname;
						window.history.pushState("string", "Email sending succeeded", path + '?sent=true');
						location.reload();
						lightweight_contact_disable_loader();
					}
				}}).fail(function(){
					lightweight_contact_disable_loader();
					console.log("The server returned an error code.");
		});
	});
}