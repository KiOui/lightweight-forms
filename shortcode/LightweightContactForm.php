<?php

require_once ABSPATH.'wp-content/plugins/lightweight-forms/settings/LightweightSettings.php';

class LightweightContactForm {

    function __construct() {
        $settings = new LightweightSettings();
        $this->site_key = $settings->get_captcha_site_key();
    }

	function include_scripts() {
		wp_enqueue_script("lightweight-contact-form", "/wp-content/plugins/lightweight-forms/js/lightweight-contact-form.js", array("jquery"));
		wp_localize_script('lightweight-contact-form', 'ajax_vars', array('ajax_url'=>admin_url('admin-ajax.php')));
        wp_localize_script('lightweight-contact-form', 'lightweight_contact_form_vars', array('action'=>'lightweight_contact_form_submit'));

        if ($this->site_key) {
            wp_enqueue_script("lightweight-google-recaptcha", "https://www.google.com/recaptcha/api.js?render=" . $this->site_key, array());
            wp_enqueue_script("lightweight-google-recaptcha-integration", "/wp-content/plugins/lightweight-forms/js/lightweight-recaptcha-integration.js", array('lightweight-google-recaptcha'));
        }
	}

    function include_styles() {
        wp_enqueue_style("lightweight-contact-form", "/wp-content/plugins/lightweight-forms/css/lightweight-contact-form.css");
    }

    function create_shortcode() {
    	$this->include_scripts();
        $this->include_styles();
    	$sent = filter_var($_GET['sent'], FILTER_VALIDATE_BOOLEAN);
        ob_start();
        ?>
            <div class="lightweight-contact-form">
            <div style="display: none;" class="lightweight-loading-icon" id="lightweight-loading-icon-id"><img src="/wp-content/plugins/lightweight-forms/gif/loader.gif"></div>
        <?php
    	if ($sent) {
    		?>
    		  <div class="lightweight-alert-succeeded">De mail is verzonden</div>
    		<?php
    	}
    	?>
        	<p class="lightweight-title">Name</p>
            <input type='text' name='name' id='lightweight_contact_form_name'/><br>
            <p class="lightweight-title">E-mail address</p>
            <input type='email' name='email' id='lightweight_contact_form_email'/><br>
            <p class="lightweight-title">Title</p>
            <input type='text' name='post_name' id='lightweight_contact_form_post_name'/><br>
            <p class="lightweight-title">Message</p>
        <?php
    	$settings = array('media_buttons' => false, 'quicktags' => false, 'teeny' => true, 'textarea_rows' => 5);
    	wp_editor('', 'lightweight_contact_form_editor', $settings);
        if ($this->site_key) {
    	   ?>
                <input type='submit' value='Send' onclick='lightweight_captcha_send("<?php echo $this->site_key; ?>", "lightweight_contact_form_submit", lightweight_contact_form_send_captcha)'/>
            <?php
        }
        else {
            ?>
                <input type='submit' value='Send' onclick='lightweight_contact_form_send()'/>
            <?php
        }
        ?>
            </div>
        <?php
        return ob_get_clean();
    }
}
