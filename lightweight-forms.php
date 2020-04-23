<?php
/**
* @package lightweight-forms
*/
/*
Plugin Name: lightweight-forms
Description: Plugin to provide lightweight feedback and community forms to wordpress
*/


defined('ABSPATH') or die("You can't run this standalone script!");

require_once ABSPATH.'wp-admin/includes/upgrade.php';

require_once ABSPATH.'wp-content/plugins/lightweight-forms/shortcode/LightweightCommunityForm.php';
require_once ABSPATH.'wp-content/plugins/lightweight-forms/shortcode/LightweightContactForm.php';
require_once ABSPATH.'wp-content/plugins/lightweight-forms/assets/LightweightAjaxHandler.php';
require_once ABSPATH.'wp-content/plugins/lightweight-forms/settings/LightweightSettings.php';

class LightweightForms {

	private $shortcode_community_form = "lightweight_community_form";
    private $shortcode_contact_form = "lightweight_contact_form";

    function __construct() {

    }

    function ajax_actions() {
        $ajaxhandler = new LightweightAjaxHandler();
        //We need to specify both the nopriv (not logged in user) and priv (logged in user) hooks
        add_action( 'wp_ajax_nopriv_submit_community_form', array($ajaxhandler, 'handle_community_request'));
		add_action( 'wp_ajax_submit_community_form', array($ajaxhandler, 'handle_community_request'));
        add_action( 'wp_ajax_nopriv_submit_contact_form', array($ajaxhandler, 'handle_contact_request'));
        add_action( 'wp_ajax_submit_contact_form', array($ajaxhandler, 'handle_contact_request'));
    }

    function shortcodes() {
    	$community_form = new LightweightCommunityForm();
    	add_shortcode($this->shortcode_community_form, array($community_form, 'create_shortcode'));
        $contact_form = new LightweightContactForm();
        add_shortcode($this->shortcode_contact_form, array($contact_form, 'create_shortcode'));
    }

    function activate_settings_page() {
        if (is_admin()) {
            $settings = new LightweightSettings();
            add_action('admin_menu', array($settings, 'add_menu'));
            add_action('admin_init', array($settings, 'register_settings'));
        }
    }

    function activate() {

    }

    function deactivate() {

    }

    function remove() {

    }
}


if (class_exists('LightweightForms')) {
    $pluginHandler = new LightweightForms();
    $pluginHandler->shortcodes();
    $pluginHandler->ajax_actions();
    $pluginHandler->activate_settings_page();

    register_activation_hook(__FILE__, array($pluginHandler, 'activate'));

    register_deactivation_hook(__FILE__, array($pluginHandler, 'deactivate'));

    register_uninstall_hook(__FILE__, array($pluginHandler, 'remove'));
}