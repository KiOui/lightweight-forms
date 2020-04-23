<?php

require_once ABSPATH.'wp-content/plugins/lightweight-forms/settings/LightweightSettings.php';

class LightweightCommunityForm {

    function __construct() {
        $settings = new LightweightSettings();
        $this->site_key = $settings->get_captcha_site_key();
    }

	function include_scripts() {
		wp_enqueue_script("lightweight-community-form", "/wp-content/plugins/lightweight-forms/js/lightweight-community-form.js", array("jquery"));
		wp_localize_script('lightweight-community-form', 'ajax_vars', array('ajax_url'=>admin_url('admin-ajax.php')));
        wp_localize_script('lightweight-community-form', 'lightweight_community_form_vars', array('action'=>'lightweight_community_form_submit'));

        if ($this->site_key) {
            wp_enqueue_script("lightweight-google-recaptcha", "https://www.google.com/recaptcha/api.js?render=" . $this->site_key, array());
            wp_enqueue_script("lightweight-google-recaptcha-integration", "/wp-content/plugins/lightweight-forms/js/lightweight-recaptcha-integration.js", array('lightweight-google-recaptcha'));
        }
	}

    function create_shortcode() {
    	$this->include_scripts();
        ob_start();
    	?> 
        <p class="lightweight-title">Title</p>
        <input type='text' name='post_name' id='lightweight_community_form_post_name'/><br>
        <p class="lightweight-title">Message</p>
        <?php
    	$settings = array('media_buttons' => false, 'quicktags' => false, 'teeny' => true, 'textarea_rows' => 5);
    	wp_editor('', 'lightweight_community_form_editor', $settings);
        if ($this->site_key) {
    	   ?>
           <input type='submit' value='Submit' onclick='lightweight_captcha_send("<?php echo $this->site_key; ?>", "lightweight_community_form_submit", lightweight_community_form_send_captcha)'/>
            <?php
        }
        else {
            ?>
            <input type='submit' value='Submit' onclick='lightweight_community_form_send()'/>
            <?php
        }
        return ob_get_clean();
    }
}
