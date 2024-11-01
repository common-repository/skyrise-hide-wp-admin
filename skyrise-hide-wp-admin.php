<?php
/*
Plugin Name: Skyrise Hide WP Admin
Description: Skyrise plugin to hide wp-admin page when user is not logged
Author: Skyrise <office@skyrise.tech>
Author URI: http://skyrise.tech
Version: 1.0.0
Text Domain: skyrise-hide-wp-admin
License: MIT
*/

if (defined('ABSPATH') && !class_exists('Skyrise_Hide_WP_Admin')) {

	class Skyrise_Hide_WP_Admin {

		protected static $instance = null;

		public function __construct() {
			add_action('plugins_loaded', [$this, 'pluginLoaded'], 9);
			add_action('admin_init', [$this, 'adminInit']);
		}

		public static function getInstance() {
	    	if (null === self::$instance) {
	    		self::$instance = new self;
	    	}
        
	    	return self::$instance;
		}

	    public static function pluginLoaded() {
			if (is_admin() && !is_user_logged_in() && !defined('DOING_AJAX')) {
                self::redirect('/' . self::getRedirectUrl());
			}
		}
		
		public function adminInit() {
			add_settings_section(
				'skyrise-hide-wp-admin-section',
				'Skyrise Hide WP Admin',
				[$this, 'getOptionSectionTemplate'],
				'general'
			);

			add_settings_field(
				'skyrise_hwa',
				sprintf('<label for="skyrise_hwa">%s</label>', __('Redirect URL', 'skyrise_hwa')),
				[$this, 'getOptionInputTemplate'],
				'general',
				'skyrise-hide-wp-admin-section'
			);
			
			register_setting('general', 'skyrise_hwa', 'sanitize_title_with_dashes');
		}

		public function getOptionInputTemplate(){
				echo sprintf(
					'<code>%s</code><input id="skyrise_hwa" type="text" name="skyrise_hwa" value="%s" />',
					trailingslashit(home_url()),
					$this->getRedirectUrl()
				);
		}

		public function getOptionSectionTemplate() {
			echo __('Enter URL address that user be redirected when would enter to WP Admin page without login', 'skyrise_hwa');
		}

		private function redirect($location, $status = 301) {
			header('Location:' . $location, $status);
			die();
		}

		private function getRedirectUrl() {
			return get_option('skyrise_hwa') ? get_option('skyrise_hwa') : '404';
		}
	}

	add_action('plugins_loaded', ['Skyrise_Hide_WP_Admin', 'getInstance'], 1);
}
