<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Ech_Lfg
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// delete options in wp_options DB when uninstall the plugin
delete_option('ech_lfg_apply_test_msp');
delete_option('ech_lfg_brand_name');
delete_option('ech_lfg_submitBtn_color');
delete_option('ech_lfg_submitBtn_hoverColor');
delete_option('ech_lfg_submitBtn_text_color');
delete_option('ech_lfg_submitBtn_text_hoverColor');
delete_option('ech_lfg_apply_recapt');
delete_option('ech_lfg_recapt_site_key');
delete_option('ech_lfg_recapt_secret_key');
delete_option('ech_lfg_recapt_score');


