<?php

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}

$main_page = menu_page_url(CCA_STRING,false);
$customize_page = $main_page . '&action=customize';
$oauth_page = $main_page . '&action=oauth';
$request_page = isset($_GET['action']) && !empty($_GET['action']) && in_array($_GET['action'], array('oauth', 'customize') ) ? $_GET['action'] : 'main';

?>
<div class="wrap <?php echo CCA_STRING; ?>-options-page">
    <h1><?php _e('Cloud Customer Area settings', CCA_STRING); ?></h1>

    <nav class="nav-tab-wrapper wp-clearfix" aria-label="Secondary menu">
        <a href="<?php echo $main_page; ?>" class="nav-tab<?php echo ($request_page == 'main' ? ' nav-tab-active' : ''); ?>"><?php _e('General Settings', CCA_STRING ); ?></a>
        <a href="<?php echo $customize_page; ?>" class="nav-tab<?php echo ($request_page == 'customize' ? ' nav-tab-active' : ''); ?>"><?php _e('Labels & Style', CCA_STRING ); ?></a>
        <a href="<?php echo $oauth_page; ?>" class="nav-tab<?php echo ($request_page == 'oauth' ? ' nav-tab-active' : ''); ?>"><?php _e('Google Drive API', CCA_STRING ); ?></a>
    </nav>

    <div class="option-page-<?php echo $request_page;?>">

        <?php 
        if( file_exists(plugin_dir_path( __FILE__ ) . '/settings_page_'.$request_page.'.php') ){
            if( !has_action('cca_settings_page_'.$request_page) ){
                include(plugin_dir_path( __FILE__ ) . '/settings_page_'.$request_page.'.php');
            } else {
                do_action('cca_settings_page_'.$request_page);
            }
        }
        ?>

    </div>
</div>