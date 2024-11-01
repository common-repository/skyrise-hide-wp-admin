<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Skyrise Hide WP Admin
 * @version   1.0.0
 * @author    Oskar Lebuda <oskar.lebuda@skyrise.tech>
 * @license   MIT
 * @link      http://skyrise.tech
 * @copyright 2018 Skyrise
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
	exit;
}

global $wpdb;

$wpdb->delete($wpdb->prefix . 'options', ['option_name' => 'skyrise_hwa']);