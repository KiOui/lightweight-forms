# Lightweight forms
Welcome to the Lightweight forms Wordpress plugin GitHub page. The idea to create this plugin was because, at the time of writing this plugin, no plugin existed that was very lightweight (so not a lot of tweaking and stuff that no-one needs) and had Google reCAPTCHA v3 integration. If you need an easy, very lightweight plugin to handle a contact form or community form (a form which users can post posts with) you can use this plugin.

## Installation
Installing is as simple as copying this directory to your ```plugins``` folder in the Wordpress ```wp-content``` folder. After this is done you need to activate the plugin under _Plugins_ in the admin dashboard of Wordpress.

## Plugin settings
The plugin settings can be found under _Settings_ -> _Lightweight menu_ in the Wordpress admin dashboard.

### Enabling reCAPTCHA
reCAPTCHA can be enabled by entering the reCAPTCHA site and secret key to the Plugin settings.

## Shortcodes
This plugin features two shortcodes:

- ```lightweigh_community_form```
- ```lightweight_contact_form```

### Community form
The community form requires one setting to be set in the plugin settings. The anonymous user id must be set to a default user. If someone is not logged in and can access the community form, a post will be posted under the user corresponding to the anonymous user id.

### Contact form
The contact form requires one setting to be set in the plugin settings. The administrator e-mail address must be set the the e-mail address that should receive the contact requests.

