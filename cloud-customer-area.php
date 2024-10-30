<?php

namespace CloudCustomerArea;

/*
Plugin Name: Cloud Customer Area
Plugin URI: https://totalpress.org/plugins/cloud-customer-area?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=cloud-customer-area
Description: The only plugin to create and manage a reserved customer area, using cloud services (ex: Google Drive).
Author: TotalPress.org
Author URI: https://totalpress.org/plugins/cloud-customer-area?utm_source=wp-dashboard&utm_medium=installed-plugin&utm_campaign=cloud-customer-area
Text Domain: cloud-customer-area
Domain Path: /languages/
Version: 2.0.8
*/

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

define('CCA_PATH', plugin_dir_path(__FILE__));
define('CCA_URL', plugin_dir_url(__FILE__));
define('CCA_STRING', 'cloud-customer-area');
define('CCA_SLUG', 'cloud_customer_area');
define('CCA_VER', get_file_data(__FILE__, array('Version' => 'Version'), false)['Version'] );
define('CCA_PRO_MIN_VER', '2.0.0');

foreach (glob(CCA_PATH . "inc/*.php") as $file) {
    include_once $file;
}

$main = new \CloudCustomerArea\Inc\Main();
$main->init_plugin();

$currentVersion = CCA_VER;
register_activation_hook(__FILE__, function () use ($currentVersion) {

    add_role('customer_area_user', __('Cloud Area User', 'cloud-customer-area'), []);

    $request_url = add_query_arg(
        ['id' => 470, 'action' => 'activate', 'domain' => md5(get_home_url()), 'v' => $currentVersion],
        'https://totalpress.org/wp-json/totalpress/v1/plugin-growth'
    );
    wp_remote_get($request_url);
});
register_deactivation_hook(__FILE__, function () use ($currentVersion) {
    $request_url = add_query_arg(
        ['id' => 470, 'action' => 'deactivate', 'domain' => md5(get_home_url()), 'v' => $currentVersion],
        'https://totalpress.org/wp-json/totalpress/v1/plugin-growth'
    );
    wp_remote_get($request_url);
});