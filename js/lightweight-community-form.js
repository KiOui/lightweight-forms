let LIGHTWEIGHT_POST_NAME = 'lightweight_community_form_post_name';
let LIGHTWEIGHT_POST_EDITOR = 'lightweight_community_form_editor';

function lightweight_community_enable_loader() {

}

function lightweight_community_disable_loader() {

}

function lightweight_community_form_send_captcha(token) {
	jQuery(function($) {
		lightweight_community_enable_loader();
		let data = {
			'action': "submit_community_form",
			'post_name': document.getElementById(LIGHTWEIGHT_POST_NAME).value,
			'post_editor': tinymce.get(LIGHTWEIGHT_POST_EDITOR).getContent(),
			'token': token,
			'action-captcha': lightweight_community_form_vars.action,
		};
		$.ajax({type: 'POST', url:ajax_vars.ajax_url, data, dataType:'json', asynch: true, success:
				function(returnedData){
					if (returnedData.error) {
						window.alert(returnedData.errormsg);
						lightweight_community_disable_loader();
					}
					else {
						location.reload();
						lightweight_community_disable_loader();
					}
				}}).fail(function(){
					lightweight_community_disable_loader();
					console.log("The server returned an error code.");
		});
	});
}

function lightweight_community_form_send() {
	jQuery(function($) {
		lightweight_community_enable_loader();
		let data = {
			'action': "submit_community_form",
			'post_name': document.getElementById(LIGHTWEIGHT_POST_NAME).value,
			'post_editor': tinymce.get(LIGHTWEIGHT_POST_EDITOR).getContent(),
		};
		$.ajax({type: 'POST', url:ajax_vars.ajax_url, data, dataType:'json', asynch: true, success:
				function(returnedData){
					if (returnedData.error) {
						window.alert(returnedData.errormsg);
						lightweight_community_disable_loader();
					}
					else {
						location.reload();
						lightweight_community_disable_loader();
					}
				}}).fail(function(){
					lightweight_community_disable_loader();
					console.log("The server returned an error code.");
		});
	});
}