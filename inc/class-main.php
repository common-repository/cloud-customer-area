<?php

namespace CloudCustomerArea\Inc;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class Main
{
    /**
     * @var array
     */
    public $default_settings = [
        'general' => [
            'customer_roles' => [],
            'customer_dir_name' => 'user_login',
            'customer_can_upload' => 0,
            'file_types' => [],
            'file_size_limit' => '',
        ],
        'customize' => [
            'label_name' => 'Name',
            'label_date' => 'Date',
            'label_type' => 'Type',
            'label_file_size' => 'Size',
            'label_download' => 'Download',
            'label_select' => 'Choose file to upload',
            'label_upload' => 'Upload file',
            'label_supported' => '<strong>Supported files:</strong> %s',
            'label_size' => '<strong>File size limit:</strong> %s',
            'label_err_supported' => 'An error has occurred, file type not supported.',
            'label_err_size' => 'An error has occurred, file size exceeds the limit.',
            'label_err_generic' => 'An error has occurred, please try again or contact us.',
            'label_uploaded' => 'The file was successfully uploaded.',
            'label_nofiles' => 'No files available.',
            'label_guest' => 'Before accessing this content, <a href="%s">please log in</a>.',
            'label_logout' => 'Don\'t forget to <a href="%s">log out</a>.',
            'table_bg' => '#fff',
            'table_color' => '#555',
            'table_border_color' => '#ccc',
            'table_thead_bg' => '#eee',
            'table_thead_color' => '#000',
        ],
        'oauth' => [
            'id_client' => '',
            'client_secret' => '',
            'access_token' => ['access_token' => ''],
        ],
    ];

    /**
     * @var null|\CloudCustomerArea\Inc\GoogleDrive
     */
    private $google_api = null;

    public function __construct()
    {
        add_action('init', function () {
            $client_id = $this->get_settings('id_client', 'oauth');
            $client_secret = $this->get_settings('client_secret', 'oauth');
            $redirect_url = $this->get_settings('redirect_url', 'oauth');
            if ($client_id && $client_secret && $redirect_url) {
                $this->google_api = new \CloudCustomerArea\Inc\GoogleDrive();
            }
        });
    }

    /**
     * @param $setting
     * @param $type
     * @return false|mixed
     */
    public function get_settings($setting = '', $type = '')
    {
        if ($setting == 'redirect_url') {
            return admin_url('admin.php?page=' . CCA_STRING) . '&action=oauth';
        }

        $default_settings = !empty($this->default_settings[$type]) ? $this->default_settings[$type] : false;
        $settings = get_option(CCA_SLUG . '_' . $type, $default_settings);

        return !empty($settings[$setting]) ? $settings[$setting] : $default_settings[$setting];
    }

    /**
     * @param $setting
     * @param $type
     * @param $value
     * @return bool
     */
    public function update_setting($setting = '', $type = '', $value = '')
    {
        if (empty($this->default_settings[$type])) {
            return false;
        }
        $settings = get_option(CCA_SLUG . '_' . $type, $this->default_settings[$type]);
        $settings[$setting] = $value;
        return update_option(CCA_SLUG . '_' . $type, $settings);
    }

    /**
     * @return bool
     */
    private function is_pro_version_active()
    {
        $return = false;
        $pro_version = in_array('cloud-customer-area-pro/cloud-customer-area-pro.php', apply_filters('active_plugins', get_option('active_plugins')));
        if ($pro_version) {
            $return = true;
        }
        return $return;
    }

    /**
     * @param $log
     * @return void
     */
    public function write_log($log)
    {
        if (true === WP_DEBUG && !empty($log)) {
            $log_message = "LOG - CLOUD CUSTOMER AREA\n\n" . (is_array($log) || is_object($log) ? print_r($log, true) : $log);
            error_log($log_message . "\n\n");
        }
    }

    /**
     * @return bool
     */
    public function current_user_can()
    {
        $return = false;
        $customer_roles = ['administrator', 'customer_area_user'];
        if (is_user_logged_in() && !empty(array_intersect(wp_get_current_user()->roles, $customer_roles))) {
            $return = true;
        }
        return apply_filters('cca_current_user_can', $return);
    }

    /**
     * @return array
     */
    public function get_roles()
    {
        global $wp_roles;
        $roles = [];
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
        foreach ($editable_roles as $key => $role) {
            if (!in_array($key, ['administrator', 'customer_area_user'])) {
                $roles[] = [
                    'role' => $key,
                    'label' => $role['name'],
                ];
            }
        }
        return $roles;
    }

    /**
     * @return void
     */
    public function init_plugin()
    {
        // Plugin
        add_action('after_setup_theme', function () {
            if (current_user_can('customer_area_user') && !is_admin()) {
                show_admin_bar(false);
            }
        });
        add_filter('plugin_action_links', function ($links, $file) {
            if ($file == 'cloud-customer-area/cloud-customer-area.php') {
                $links[] = sprintf('<a href="%s"> %s </a>', menu_page_url(CCA_STRING, false), __('Settings', 'cloud-customer-area'));
                if (!$this->is_pro_version_active()) {
                    $links[] = sprintf('<a href="%s" style="font-weight: bold;"> %s </a>', 'https://www.andreadegiovine.it/risorse/plugin/cloud-customer-area?utm_source=tools_plugin_page&utm_medium=plugin_page&utm_campaign=cloud_customer_area', __('Get PRO', 'cloud-customer-area'));
                }
            }
            return $links;
        }, 10, 2);
        add_action('init', function () {
            load_plugin_textdomain(CCA_STRING, false, CCA_PATH . 'languages');
        });
        add_action('init', function () {
            $all_actived_plugins = apply_filters('active_plugins', get_option('active_plugins'));
            if (in_array('cloud-customer-area-pro/cloud-customer-area-pro.php', $all_actived_plugins) &&
                version_compare(CCA_PRO_VER, CCA_PRO_MIN_VER, '<')) {
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
                deactivate_plugins('cloud-customer-area-pro/cloud-customer-area-pro.php');
                wp_die(sprintf(__('"Cloud Customer Area" requires the minimum version %s of "Cloud Customer Area PRO".', 'cloud-customer-area'), CCA_PRO_MIN_VER));
            }
        });

        // Settings page
        add_action('admin_menu', function () {
            add_action('admin_init', function () {
                register_setting(CCA_STRING . '-general', CCA_STRING . '-pro_license_key');
                register_setting(CCA_STRING . '-general', CCA_STRING . '-pro_last_license_check');
                register_setting(CCA_STRING . '-general', CCA_SLUG . '_general');
                register_setting(CCA_STRING . '-customize', CCA_SLUG . '_customize');
                register_setting(CCA_STRING . '-oauth', CCA_SLUG . '_oauth');
            });
            add_menu_page(__('Cloud Customer Area settings', 'cloud-customer-area'), __('Customer Area', 'cloud-customer-area'), 'administrator', CCA_STRING, function () {
                require_once(CCA_PATH . 'part/settings_page.php');
            }, 'dashicons-cloud');
        });

        // Admin assets
        add_action('admin_enqueue_scripts', function () {
            wp_register_style('admin-' . CCA_STRING, CCA_URL . 'assets/css/backend.css', false, '1.0.0');
            wp_enqueue_style('admin-' . CCA_STRING);
        });

        // Frontend assets
        add_action('wp_head', function () {
            $table_bg = $this->get_settings('table_bg', 'customize');
            $table_border_color = $this->get_settings('table_border_color', 'customize');
            $table_color = $this->get_settings('table_color', 'customize');
            $table_thead_bg = $this->get_settings('table_thead_bg', 'customize');
            $table_thead_color = $this->get_settings('table_thead_color', 'customize');
            ?>
            <style>
                :root {
                    --cca-table-bg: <?php echo $table_bg; ?>;
                    --cca-table-border-color: <?php echo $table_border_color; ?>;
                    --cca-table-text-color: <?php echo $table_color; ?>;
                    --cca-table-head-bg: <?php echo $table_thead_bg; ?>;
                    --cca-table-head-text-color: <?php echo $table_thead_color; ?>;
                    --cca-table-loading-img: url(<?php echo CCA_URL; ?>assets/loading.svg);
                    --cca-loading-width: 0%;
                }
            </style>
            <?php
        });
        add_action('wp_enqueue_scripts', function () {
            wp_register_style('frontend-' . CCA_STRING, CCA_URL . 'assets/css/frontend.css', false, '1.0.0');
            wp_enqueue_style('frontend-' . CCA_STRING);

            wp_enqueue_script('frontend-' . CCA_STRING, CCA_URL . 'assets/js/frontend.js', ['jquery'], false, true);
            wp_localize_script(
                'frontend-' . CCA_STRING,
                'frontend_' . CCA_SLUG,
                [
                    'ajaxurl' => admin_url('admin-ajax.php'),
                    'token' => wp_create_nonce(CCA_SLUG . '_token'),
                    'download_label' => $this->get_settings('label_download', 'customize'),
                    'unload_label' => $this->get_settings('label_download', 'customize'),
                    'upload_max_chunk_size' => apply_filters('cca_upload_max_chunk_size', 3) * 1024 * 1024
                ]
            );
        });

        // Shortcode
        add_shortcode('cloud-customer-area', function () {
            $output = '';
            if ($this->current_user_can()) {
                $output = apply_filters('cca_form_upload', $output);
                $output .= '<table class="' . CCA_STRING . '-table">';
                $output .= '<thead><tr><th>' . $this->get_settings('label_name', 'customize') . '</th><th>' . $this->get_settings('label_date', 'customize') . '</th><th>' . $this->get_settings('label_type', 'customize') . '</th><th>' . $this->get_settings('label_file_size', 'customize') . '</th><th>' . $this->get_settings('label_download', 'customize') . '</th></tr></thead><tbody>';
                $output .= '<tr class="' . CCA_STRING . '-table-loading"><td colspan="5"></td></tr>';
                $output .= '</tbody></table>';
                if (is_user_logged_in()) {
                    $output .= '<div class="' . CCA_STRING . '-table-logout">' . sprintf($this->get_settings('label_logout', 'customize'), wp_logout_url($_SERVER['REQUEST_URI'])) . '</div>';
                }
            } else {
                $output .= '<div class="' . CCA_STRING . '-guest">' . sprintf($this->get_settings('label_guest', 'customize'), wp_login_url($_SERVER['REQUEST_URI'])) . '</div>';
            }
            return '<div class="' . CCA_STRING . '-container">' . $output . '</div>';
        });

        try {
            // Google Drive init + connect action
            add_action('init', function () {
                if (is_admin() && $this->google_api && !empty($_GET['page']) && $_GET['page'] == CCA_STRING && !empty($_GET['action']) && $_GET['action'] == 'oauth' && !empty($_GET['code'])) {
                    $token = $this->google_api->getToken($_GET['code']);
                    if ($token && !empty(json_decode($token)->access_token)) {
                        $this->google_api->updateToken(json_decode($token));
                    }
                    wp_redirect($this->get_settings('redirect_url', 'oauth'));
                    exit;
                }
            });

            // Ajax actions
            add_action('wp_ajax_cca_get_files', [$this, 'ajax_action_get_files']);
            add_action('wp_ajax_nopriv_cca_get_files', [$this, 'ajax_action_get_files']);
            add_action('wp_ajax_cca_get_file_info', [$this, 'ajax_action_get_file_info']);
            add_action('wp_ajax_nopriv_cca_get_file_info', [$this, 'ajax_action_get_file_info']);
            add_action('wp_ajax_cca_download_file', [$this, 'ajax_action_download_file']);
            add_action('wp_ajax_nopriv_cca_download_file', [$this, 'ajax_action_download_file']);
        } catch (\Exception $e) {
            wp_die('', '', ['response' => 500]);
        }

        // Utilities
        $this->applyUpdates();
    }

    /**
     * @return void
     */
    public function ajax_action_get_files()
    {
        $nonce = !empty($_REQUEST['token']) && wp_verify_nonce($_REQUEST['token'], CCA_SLUG . '_token') ? true : false;
        $customer_roles = $this->get_settings('customer_roles', 'general');
        if ($this->current_user_can() && (!in_array('guest', $customer_roles) ? $nonce : true)) {
            $user_folder = $this->get_user_folder();
            $user_files = false;
            if (!empty($user_folder)) {
                $user_files_request = $this->google_api->listFiles("'" . $user_folder . "' in parents and trashed=false and mimeType!='application/vnd.google-apps.folder'");
                $user_files = $user_files_request && json_decode($user_files_request) ? json_decode($user_files_request) : false;
            }
            if (!$user_files) {
                $return_output[] = [
                    'name' => $this->get_settings('label_err_generic', 'customize'),
                    'date' => ' - ',
                    'icon' => ' - ',
                    'id' => null,
                    'size' => false
                ];
            } else {
                foreach ($user_files->files as $file) {
                    $date = explode('T', $file->modifiedTime)[0];
                    $date = date(get_option('date_format'), strtotime($date));
                    $return_output[] = [
                        'name' => $file->name,
                        'date' => $date,
                        'icon' => !empty($file->iconLink) ? $file->iconLink : '',
                        'id' => $file->id,
                        'size' => !empty($file->size) && strpos($file->mimeType, 'application/vnd.google-apps') === false ? number_format(($file->size / (1024 * 1024)), 2, ',', '') . 'MB' : false
                    ];
                }
                if (empty($return_output)) {
                    $return_output[] = [
                        'name' => $this->get_settings('label_nofiles', 'customize'),
                        'date' => ' - ',
                        'icon' => ' - ',
                        'id' => null,
                        'size' => false
                    ];
                }
            }
            do_action('cca_files_list_event', $return_output, wp_get_current_user()->ID);
            echo json_encode($return_output);
        }
        exit;
    }

    /**
     * @return void
     */
    public function ajax_action_get_file_info()
    {
        $nonce = !empty($_REQUEST['token']) && wp_verify_nonce($_REQUEST['token'], CCA_SLUG . '_token') ? true : false;
        $fileId = !empty($_REQUEST['file']) && is_string($_REQUEST['file']) ? $_REQUEST['file'] : false;
        $customer_roles = $this->get_settings('customer_roles', 'general');
        if ($this->current_user_can() && (!in_array('guest', $customer_roles) ? $nonce : true) && $fileId) {

            $fileInfo = $this->google_api->getFileInfo($fileId);

            if (!$fileInfo || empty(json_decode($fileInfo)->name)) {
                wp_send_json_error($this->get_settings('label_err_generic', 'customize'));
            }

            $fileInfo = json_decode($fileInfo);
            $fileName = $fileInfo->name;
            $fileMime = $fileInfo->mimeType;
            $fileParts = 1;
            $fileMethod = 'download';

            if (strpos($fileInfo->mimeType, 'application/vnd.google-apps') !== false) {
                // Is Google App file
                $fileType = str_replace('application/vnd.google-apps.', '', $fileInfo->mimeType);
                $fileExportTypeMap = [
                    'audio' => [
                        'mime' => 'audio/mpeg',
                        'ext' => '.mp3'
                    ],
                    'document' => [
                        'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'ext' => '.docx'
                    ],
                    'drawing' => [
                        'mime' => 'application/pdf',
                        'ext' => '.pdf'
                    ],
                    'file' => [
                        'mime' => 'application/octet-stream',
                        'ext' => ''
                    ],
                    'presentation' => [
                        'mime' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'ext' => '.pptx'
                    ],
                    'spreadsheet' => [
                        'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'ext' => '.xlsx'
                    ]
                ];
                if (empty($fileExportTypeMap[$fileType])) {
                    wp_send_json_error(__('File mime not supported.', 'cloud-customer-area'));
                }
                $fileName = $fileInfo->name . $fileExportTypeMap[$fileType]['ext'];
                $fileMime = $fileExportTypeMap[$fileType]['mime'];
                $fileMethod = 'export';
            } else {
                $chunkSizeBytes = apply_filters('cca_download_max_chunk_size', 3) * 1024 * 1024;
                $fileParts = ceil($fileInfo->size / $chunkSizeBytes);
            }
            $fileName = apply_filters('cca_download_file_name', $fileName);
            do_action('cca_download_event', $fileName, wp_get_current_user()->ID);
            $return = [
                'name' => $fileName,
                'mime' => $fileMime,
                'parts' => $fileParts,
                'method' => $fileMethod
            ];
            echo json_encode($return);
        }
        exit;
    }

    /**
     * @return void
     */
    public function ajax_action_download_file()
    {
        $nonce = !empty($_REQUEST['token']) && wp_verify_nonce($_REQUEST['token'], CCA_SLUG . '_token') ? true : false;
        $fileId = !empty($_REQUEST['file']) && is_string($_REQUEST['file']) ? $_REQUEST['file'] : false;
        $method = !empty($_REQUEST['method']) && is_string($_REQUEST['method']) ? $_REQUEST['method'] : false;
        $mime = !empty($_REQUEST['mime']) && is_string($_REQUEST['mime']) ? $_REQUEST['mime'] : false;
        $part = !empty($_REQUEST['part']) && is_string($_REQUEST['part']) ? $_REQUEST['part'] : false;
        $customer_roles = $this->get_settings('customer_roles', 'general');
        if ($this->current_user_can() && (!in_array('guest', $customer_roles) ? $nonce : true) && $fileId && $method && $mime && $part) {
            $content = $this->download_file($fileId, $method, $mime, $part);
            header('content-type: "text/plain; charset=us-ascii"');
            echo $content;
        }
        exit;
    }

    /**
     * @param $fileId
     * @param $method
     * @param $mime
     * @param $part
     * @return false|string
     */
    private function download_file($fileId, $method, $mime, $part)
    {
        if ($method == 'export') {
            $response = $this->google_api->exportFile($fileId, $mime);
        } else {
            $response = $this->google_api->downloadFile($fileId, $part);
        }
        if (strlen($response) < 1) {
            return $this->download_file($fileId, $method, $mime, $part);
        }
        return $response;
    }

    /**
     * @return mixed
     */
    public function get_user_folder()
    {
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        if ($user_id !== 0) {
            $folder_id = get_user_meta($user_id, CCA_SLUG . '_folder', true);
        } else {
            $folder_id = get_option(CCA_SLUG . '_guest_folder', '');
        }
        if (!empty($folder_id)) {
            $folder_info_request = $this->google_api->getFileInfo($folder_id);
            if (404 == $folder_info_request) {
                if ($user_id !== 0) {
                    update_user_meta($user_id, CCA_SLUG . '_folder', '');
                } else {
                    update_option(CCA_SLUG . '_guest_folder', '');
                }
                return $this->get_user_folder();
            }
            if (!$folder_info_request) {
                return $this->get_user_folder();
            }
            if (!empty(json_decode($folder_info_request)->name)) {
                return $folder_id;
            }
        }

        if ($user_id !== 0) {
            $new_folder_use = $this->get_settings('customer_dir_name', 'general');
            if (!in_array($new_folder_use, ['user_login', 'display_name', 'user_email'])) {
                $new_folder_use = 'user_login';
            }
            $new_folder_name = $current_user->$new_folder_use;
        } else {
            $new_folder_name = 'guest-users';
        }

        $new_folder_name = apply_filters('cca_new_folder_name', $new_folder_name, $user_id);
        do_action('cca_folder_creation_event', $new_folder_name, $user_id);

        $create_folder_request = $this->google_api->createFolder($new_folder_name);

        $created_folder_id = $create_folder_request && !empty(json_decode($create_folder_request)->id) ? json_decode($create_folder_request)->id : false;

        if ($created_folder_id) {
            if ($user_id !== 0) {
                update_user_meta($current_user->ID, CCA_SLUG . '_folder', $created_folder_id);
            } else {
                update_option(CCA_SLUG . '_guest_folder', $created_folder_id);
            }
        } else {
            return $this->get_user_folder();
        }

        return $created_folder_id;
    }

    private function applyUpdates()
    {
        $installedVersion = get_option('cloud_customer_area_version', null);
        $currentVersion = CCA_VER;

        if (version_compare($installedVersion, $currentVersion, '=')) {
            return;
        }

        if (version_compare($installedVersion, $currentVersion, '<')) {
            // Apply updates
        }

        update_option('cloud_customer_area_version', $currentVersion);
        update_option('cloud_customer_area_installation_time', time());

        if(!empty($installedVersion)){
            $request_url = add_query_arg(
                ['id' => 470, 'action' => 'updated', 'domain' => md5(get_home_url()), 'v' => $currentVersion],
                'https://totalpress.org/wp-json/totalpress/v1/plugin-growth'
            );
            wp_remote_get($request_url);
        }
    }
}