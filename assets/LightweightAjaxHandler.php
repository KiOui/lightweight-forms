<?php

require_once ABSPATH.'wp-content/plugins/lightweight-forms/settings/LightweightSettings.php';

class LightweightAjaxHandler {

	private $MAIL_TEMPLATE = "lightweight_template_contact_mail.html";

	private $REPLACE_NAME = "__name__";
	private $REPLACE_TITLE = "__title__";
	private $REPLACE_MAIL = "__email_address__";
	private $REPLACE_MESSAGE = "__message__";

	function __construct() {
		$this->settings = new LightweightSettings();
	}

	function handle_community_request() {
		if (!empty($_POST['post_name']) && !empty($_POST['post_editor'])) {

			$private_key = $this->settings->get_captcha_secret_key();

			if ($private_key) {
				$token = $_POST['token'];
				$action = $_POST['action-captcha'];
				 
				// call curl to POST request
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $private_key, 'response' => $token)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);
				$arrResponse = json_decode($response, true);

				if(!($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5)) {
					echo json_encode(array("error"=>True, "errormsg"=>"Captcha verificatie gefaald"));
					wp_die();
				}
			}

			$post_name = filter_var($_POST['post_name'], FILTER_SANITIZE_STRING);
			$post_editor = wp_kses_post($_POST['post_editor']);

			$user_id = get_current_user_id();

			if ($user_id == 0) {
				$user_id = get_option('lightweight-anonymous-user-id');
			}

			$settings = array(
				"post_author"=>$user_id,
				"post_content"=>$post_editor,
				"post_title"=>$post_name,
				"post_status"=>"publish"
			);
			$retvalue = wp_insert_post($settings);
			if ($retvalue == 0) {
				echo json_encode(array("error"=>True, "errormsg"=>"Er ging iets fout met het plaatsen van uw bericht, probeer het later opnieuw"));
			}
			else {
				echo json_encode(array("error"=>False, "postid"=>$retvalue));
			}
		}
		else {
			echo json_encode(array("error"=>True, "errormsg"=>"Vul alstublieft een titel en bericht in"));
		}
		wp_die();
	}

	function handle_contact_request() {
		if (empty($_POST['post_name']) && empty($_POST['post_editor']) && empty($_POST['name']) && empty($_POST['email'])) {
			echo json_encode(array("error"=>True, "errormsg"=>"Vul alstublieft een van de velden in"));
		}
		else {
			$email_template = file_get_contents(dirname(__FILE__) . '/mails/' . $this->MAIL_TEMPLATE);

			$private_key = $this->settings->get_captcha_secret_key();

			if ($private_key) {
				$token = $_POST['token'];
				$action = $_POST['action-captcha'];
				 
				// call curl to POST request
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $private_key, 'response' => $token)));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);
				$arrResponse = json_decode($response, true);

				if(!($arrResponse["success"] == '1' && $arrResponse["action"] == $action && $arrResponse["score"] >= 0.5)) {
					echo json_encode(array("error"=>True, "errormsg"=>"Captcha verificatie gefaald, probeer het opnieuw"));
					wp_die();
				}
			}

			$post_name = filter_var($_POST['post_name'], FILTER_SANITIZE_STRING);
			$post_editor = wp_kses_post($_POST['post_editor']);
			$client_name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
			$client_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

			$email_template = str_replace($this->REPLACE_MESSAGE, $post_editor, $email_template);
			$email_template = str_replace($this->REPLACE_TITLE, $post_name, $email_template);
			$email_template = str_replace($this->REPLACE_NAME, $client_name, $email_template);
			$email_template = str_replace($this->REPLACE_MAIL, $client_email, $email_template);

			$admin_email = $this->settings->get_email_address();

			$headers_mail = array();
	        $headers_mail[] = "From: kanikervanaf.nl <noreply@kanikervanaf.nl>";
	        $headers_mail[] = "Bcc: " . $client_email;
	        $headers_mail[] = "Content-Type: text/html";

		    $retvalue = wp_mail($admin_email, "Kanikervanaf: Contact formulier", $email_template, $headers_mail);

		    if ($retvalue) {
		    	echo json_encode(array("error"=>False));
		    }
			else {
				echo json_encode(array("error"=>True, "errormsg"=>"De mail kon niet worden verstuurd"));
			}
		}
		wp_die();
	}
}