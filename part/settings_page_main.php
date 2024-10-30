<?php

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

$main = new \CloudCustomerArea\Inc\Main();
?>
<form method="post" action="options.php">
    <?php settings_fields(CCA_STRING . '-general'); ?>
    <?php do_settings_sections(CCA_STRING . '-general'); ?>
    <?php
    $customer_dir_name = $main->get_settings('customer_dir_name', 'general');
    ?>
    <p><?php printf(__('To <span class="cca-pro-required">unlock all the features</span>, please <a href="%s" target="_blank" title="Get PRO version">get the PRO version</a>.', 'cloud-customer-area'), 'https://www.andreadegiovine.it/risorse/plugin/cloud-customer-area/?utm_source=tools_plugin_page&utm_medium=plugin_page&utm_campaign=cloud_customer_area'); ?></p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('User roles enabled', 'cloud-customer-area'); ?></span></th>
            <td>
                <label class="switch">
                    <input type="checkbox" checked disabled />
                    <span class="slider"></span>
                </label> <?php _e('Administrator', 'cloud-customer-area'); ?><br><br>
                <label class="switch">
                    <input type="checkbox" checked disabled />
                    <span class="slider"></span>
                </label> <?php _e('Cloud Area User', 'cloud-customer-area'); ?><br><br>
                <span class="cca-pro-required">
                    <label class="switch">
                        <input type="checkbox" disabled />
                        <span class="slider"></span>
                    </label> <?php _e('Guest User', 'cloud-customer-area'); ?>
                </span>
                <?php foreach ($main->get_roles() as $role) { ?>
                    <br><br>
                    <span class="cca-pro-required">
                        <label class="switch">
                            <input type="checkbox" disabled />
                            <span class="slider"></span>
                        </label> <?php _e($role['label'], 'cloud-customer-area'); ?>
                    </span>
                <?php } ?>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Create customers folders using', 'cloud-customer-area'); ?></th>
            <td>
                <select name="<?php echo CCA_SLUG . '_general'; ?>[customer_dir_name]">
                    <option value="user_login" <?php selected($customer_dir_name, 'user_login'); ?>><?php _e('User login', 'cloud-customer-area'); ?></option>
                    <option value="display_name" <?php selected($customer_dir_name, 'display_name'); ?>><?php _e('Display name', 'cloud-customer-area'); ?></option>
                    <option value="user_email" <?php selected($customer_dir_name, 'user_email'); ?>><?php _e('User email', 'cloud-customer-area'); ?></option>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Customer can upload files', 'cloud-customer-area'); ?></span></th>
            <td><label class="switch">
                    <input type="checkbox" disabled />
                    <span class="slider"></span>
                </label></td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Restrict accepted file types', 'cloud-customer-area'); ?></span></th>
            <td>
                <span class="cca-pro-required">
                    <label class="switch">
                        <input type="checkbox" disabled />
                        <span class="slider"></span>
                    </label> <?php _e('Images', 'cloud-customer-area'); ?><br><br>
                    <label class="switch">
                        <input type="checkbox" disabled />
                        <span class="slider"></span>
                    </label> <?php _e('Videos', 'cloud-customer-area'); ?><br><br>
                    <label class="switch">
                        <input type="checkbox" disabled />
                        <span class="slider"></span>
                    </label> <?php _e('Documents', 'cloud-customer-area'); ?><br><br>
                    <label class="switch">
                        <input type="checkbox" disabled />
                        <span class="slider"></span>
                    </label> <?php _e('Archives', 'cloud-customer-area'); ?>
                </span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('File size limit (KB)', 'cloud-customer-area'); ?></span></th>
            <td>
                <input type="number" disabled />
            </td>
        </tr>
    </table>

    <?php submit_button(); ?>
</form>