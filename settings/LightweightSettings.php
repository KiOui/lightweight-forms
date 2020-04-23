<?php
    
    class LightWeightSettings {

        function register_settings() {
            register_setting('lightweight-form-options', 'lightweight-captcha-site-key', array(
                'sanitize_callback'=>array($this, 'sanitize_captcha'),
                'type'=>'string',
                'default'=>''
            ));
            register_setting('lightweight-form-options', 'lightweight-captcha-secret-key', array(
                'sanitize_callback'=>array($this, 'sanitize_captcha'),
                'type'=>'string',
                'default'=>''
            ));
            register_setting('lightweight-form-options', 'lightweight-anonymous-user-id', array(
                'sanitize_callback'=>array($this, 'sanitize_user_id'),
                'type'=>'int',
                'default'=>'1'
            ));
            register_setting('lightweight-form-options', 'lightweight-administrator-email', array(
                'sanitize_callback'=>array($this, 'sanitize_email'),
                'type'=>'string',
                'default'=>''
            ));
        }

        function sanitize_user_id($input) {
            $filtered = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
            if ($filtered) {
                return $filtered;
            }
            else {
                return 1;
            }
        }

        function sanitize_captcha($input)
        {
            return sanitize_text_field($input);
        }

        function sanitize_email($input) {
            $filtered = filter_var($input, FILTER_VALIDATE_EMAIL);
            if ($filtered) {
                return $filtered;
            }
            else {
                return '';
            }
        }

        function add_menu() {
            add_options_page('Lightweight Settings', 'Lightweight menu', 'manage_options', 'lightweight-settings-page', array($this, 'create_settings_page'));
        }

        function create_settings_page() {
            ?>
            <div class="wrap">
            <h1>Lightweight Forms</h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'lightweight-form-options' ); ?>
                <?php do_settings_sections( 'lightweight-form-options' ); ?>
                <table class="form-table">
                    <tr valign="top">
                    <th scope="row">Captcha site key</th>
                    <td><input type="text" name="lightweight-captcha-site-key" value="<?php echo esc_attr( get_option('lightweight-captcha-site-key') ); ?>" /></td>
                    </tr>
                     
                    <tr valign="top">
                    <th scope="row">Captcha secret key</th>
                    <td><input type="text" name="lightweight-captcha-secret-key" value="<?php echo esc_attr( get_option('lightweight-captcha-secret-key') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                    <th scope="row">Anonymous user ID</th>
                    <td><input type="text" name="lightweight-anonymous-user-id" value="<?php echo esc_attr( get_option('lightweight-anonymous-user-id') ); ?>" /></td>
                    </tr>
                    <tr valign="top">
                    <th scope="row">Administrator E-mail adress</th>
                    <td><input type="email" name="lightweight-administrator-email" value="<?php echo esc_attr( get_option('lightweight-administrator-email') ); ?>" /></td>
                    </tr>
                </table> 
                <?php submit_button(); ?>
            </form>
            </div>
            <?php
        }

        function get_captcha_site_key() {
            if (empty(get_option('lightweight-captcha-secret-key')) || empty(get_option('lightweight-captcha-site-key'))) {
                return False;
            }
            else {
                return get_option('lightweight-captcha-site-key');
            }
        }

        function get_captcha_secret_key() {
            if (empty(get_option('lightweight-captcha-secret-key')) || empty(get_option('lightweight-captcha-site-key'))) {
                return False;
            }
            else {
                return get_option('lightweight-captcha-secret-key');
            }
        }

        function get_email_address() {
            if (empty(get_option('lightweight-administrator-email'))) {
                return get_option('admin_email');
            }
            else {
                return get_option('lightweight-administrator-email');
            }
        }
    }