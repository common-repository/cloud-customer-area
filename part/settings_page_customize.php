<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

$main = new \CloudCustomerArea\Inc\Main();
?>
<form method="post" action="options.php">
    <?php settings_fields( CCA_STRING.'-customize' ); ?>
    <?php do_settings_sections( CCA_STRING.'-customize' ); ?>
    <?php
    $label_name = $main->get_settings('label_name', 'customize');
    $label_date = $main->get_settings('label_date', 'customize');
    $label_type = $main->get_settings('label_type', 'customize');
    $label_file_size = $main->get_settings('label_file_size', 'customize');
    $label_download = $main->get_settings('label_download', 'customize');    
    $label_err_generic = $main->get_settings('label_err_generic', 'customize');    
    $label_nofiles = $main->get_settings('label_nofiles', 'customize');
    $label_guest = $main->get_settings('label_guest', 'customize');
    $label_logout = $main->get_settings('label_logout', 'customize');
    $table_bg = $main->get_settings('table_bg', 'customize');
    $table_color = $main->get_settings('table_color', 'customize');
    $table_border_color = $main->get_settings('table_border_color', 'customize');
    $table_thead_bg = $main->get_settings('table_thead_bg', 'customize');
    $table_thead_color = $main->get_settings('table_thead_color', 'customize');
    ?>
    <p><?php printf(__('To <span class="cca-pro-required">unlock all the features</span>, please <a href="%s" target="_blank" title="Get PRO version">get the PRO version</a>.', 'cloud-customer-area'), 'https://www.andreadegiovine.it/risorse/plugin/cloud-customer-area/?utm_source=tools_plugin_page&utm_medium=plugin_page&utm_campaign=cloud_customer_area' ); ?></p>
    <p><?php _e('Use the "<strong>[cloud-customer-area]</strong>" shortcode on your pages to show the table of files and the upload form.', 'cloud-customer-area'); ?></p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('Label for Name', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_name]" value="<?php echo $label_name;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for Date', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_date]" value="<?php echo $label_date;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for Type', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_type]" value="<?php echo $label_type;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for Size', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_file_size]" value="<?php echo $label_file_size;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for Download', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_download]" value="<?php echo $label_download;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for Choose file', 'cloud-customer-area'); ?></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for Upload file', 'cloud-customer-area'); ?></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for Supported files', 'cloud-customer-area'); ?><br><small><?php _e('use %s to list options', 'cloud-customer-area'); ?></small></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for File size limit', 'cloud-customer-area'); ?><br><small><?php _e('use %s to list options', 'cloud-customer-area'); ?></small></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for type error', 'cloud-customer-area'); ?></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for size error', 'cloud-customer-area'); ?></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for generic error', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_err_generic]" value="<?php echo $label_err_generic;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><span class="cca-pro-required"><?php _e('Label for upload confirm', 'cloud-customer-area'); ?></span></th>
            <td>
                <input type="text" disabled />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for no files avaiable', 'cloud-customer-area'); ?></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_nofiles]" value="<?php echo $label_nofiles;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for guest users', 'cloud-customer-area'); ?><br><small><?php _e('use %s to add url', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_guest]" value="<?php echo htmlentities($label_guest);?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Label for Log out link', 'cloud-customer-area'); ?><br><small><?php _e('use %s to add url', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[label_logout]" value="<?php echo htmlentities($label_logout);?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Table background', 'cloud-customer-area'); ?><br><small><?php _e('hex color (ex: #fff)', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[table_bg]" value="<?php echo $table_bg;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Table font color', 'cloud-customer-area'); ?><br><small><?php _e('hex color (ex: #fff)', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[table_color]" value="<?php echo $table_color;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Table border color', 'cloud-customer-area'); ?><br><small><?php _e('hex color (ex: #fff)', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[table_border_color]" value="<?php echo $table_border_color;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Table head background', 'cloud-customer-area'); ?><br><small><?php _e('hex color (ex: #fff)', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[table_thead_bg]" value="<?php echo $table_thead_bg;?>" />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('Table head font color', 'cloud-customer-area'); ?><br><small><?php _e('hex color (ex: #fff)', 'cloud-customer-area'); ?></small></th>
            <td>
                <input type="text" name="<?php echo CCA_SLUG.'_customize'; ?>[table_thead_color]" value="<?php echo $table_thead_color;?>" />
            </td>
        </tr>
    </table>
    <p><?php _e('For more customizations use CSS rules.', 'cloud-customer-area'); ?></p>

    <?php submit_button(); ?>
</form>