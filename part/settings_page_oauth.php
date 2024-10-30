<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

$main = new \CloudCustomerArea\Inc\Main();
$google_api = new \CloudCustomerArea\Inc\GoogleDrive();
?>
<form method="post" action="options.php">
    <?php settings_fields( CCA_STRING.'-oauth' ); ?>
    <?php do_settings_sections( CCA_STRING.'-oauth' ); ?>
    <?php
    $id_client = $main->get_settings('id_client', 'oauth');
    $client_secret = $main->get_settings('client_secret', 'oauth');
    $access_token = $main->get_settings('access_token', 'oauth');
    $redirect_url = $main->get_settings('redirect_url', 'oauth');
    ?>
    <p><?php printf(__('All information about the plugin configuration is available on the <a href="%s" target="_blank" title="WordPress plugin developer">Developer website</a>.', 'cloud-customer-area'), 'https://www.andreadegiovine.it/risorse/plugin/cloud-customer-area?utm_source=tools_plugin_page&utm_medium=plugin_page&utm_campaign=cloud_customer_area' ); ?></p>
    <table class="form-table">
        <tr valign="top">
            <th scope="row"><?php _e('OAuth 2.0 - Redirect URI', 'cloud-customer-area'); ?></th>
            <td><label>
                <input type="text" onClick="this.setSelectionRange(0, this.value.length)" value="<?php echo $redirect_url; ?>" readonly />
                </label></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('OAuth 2.0 - ID client', 'cloud-customer-area'); ?></th>
            <td><label>
                <input type="text" name="<?php echo CCA_SLUG.'_oauth'; ?>[id_client]" value="<?php echo $id_client; ?>" />
                </label></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('OAuth 2.0 - Client secret', 'cloud-customer-area'); ?></th>
            <td><label>
                <input type="text" name="<?php echo CCA_SLUG.'_oauth'; ?>[client_secret]" value="<?php echo $client_secret; ?>" />
                </label></td>
        </tr>
        <tr valign="top">
            <th scope="row"><?php _e('OAuth 2.0 - Access Token', 'cloud-customer-area'); ?></th>
            <td><label>
                <input type="text" value="<?php echo (isset($access_token->access_token) ? $access_token->access_token : ''); ?>" readonly />
                </label></td>
        </tr>
    </table>


    <?php if( empty($id_client) || empty($client_secret) ){ ?>
    <div class="<?php echo CCA_STRING; ?>-notice notice-error"><div class="notice-icon"></div><div class="notice-text"><?php _e('Fill in the required fields and "Save changes".', 'cloud-customer-area'); ?></div></div>
    <?php } elseif( empty( $access_token->access_token ) ) {
    echo '<br><a class="button button-hero load-customize hide-if-no-customize" href="'.$google_api->getConnectUrl().'">'.__('Connect to Google Drive', 'cloud-customer-area').'</a>';
} else { ?>
    <div class="<?php echo CCA_STRING; ?>-notice notice-success"><div class="notice-icon"></div><div class="notice-text"><?php _e('Google Drive connected.', 'cloud-customer-area'); ?></div></div>
    <?php } ?>

    <?php submit_button(); ?>
</form>