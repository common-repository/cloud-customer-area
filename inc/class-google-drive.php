<?php

namespace CloudCustomerArea\Inc;

if (!defined('ABSPATH')) {
    die('Invalid request.');
}

class GoogleDrive
{
    /**
     * @var Main
     */
    private $main;

    /**
     * @var string
     */
    private $client_id;

    /**
     * @var string
     */
    private $client_secret;

    /**
     * @var string
     */
    private $redirect_url;

    /**
     * @var string
     */
    private $oauth_token;

    /**
     * @var string
     */
    private $last_request;

    /**
     * @var int
     */
    private $request_attemps = 0;

    /**
     * @var int
     */
    private $max_request_attempts = 3;

    public function __construct()
    {
        $this->main = new Main();

        $this->client_id = $this->main->get_settings('id_client', 'oauth');
        $this->client_secret = $this->main->get_settings('client_secret', 'oauth');
        $this->redirect_url = $this->main->get_settings('redirect_url', 'oauth');
        $this->oauth_token = $this->main->get_settings('access_token', 'oauth');
    }

    // Utils

    /**
     * @param $url
     * @param $content
     * @param $method
     * @param $headers
     * @param $returnHeaders
     * @return false|string
     */
    public function sendAPIRequest($url, $content, $method = 'POST', $headers = false, $returnHeader = false)
    {
        $args = [
            'method' => $method,
            'timeout' => 300,
            'httpversion' => '1.0',
            'sslverify' => false,
            'headers' => ['Content-Type: application/json'],
            'body' => $content
        ];
        if ($headers) {
            $args['headers'] = $headers;
        }

        $this->checkRequestAttemps([$url, $args]);

        $response = wp_remote_request($url, $args);

        array_unshift($args, ['url' => $url]);
        unset($args['timeout'], $args['sslverify'], $args['httpversion']);

        $error = false;
        if (is_wp_error($response)) {
            $error = [
                'code' => implode(' - ', $response->get_error_codes()),
                'message' => implode(' - ', $response->get_error_messages())
            ];
        } elseif (!in_array(wp_remote_retrieve_response_code($response), [200, 201, 206, 308])) {
            $error = [
                'code' => wp_remote_retrieve_response_code($response),
                'message' => wp_remote_retrieve_body($response)
            ];
        }
        if ($error) {
            $this->main->write_log("Try API request \n\nRequest:\n" . print_r($args, true) . "\n\nResponse Error:\n\n" . print_r($error, true));
            if(404 == $error['code']){
                return 404;
            }
            return false;
        }
        $return = $returnHeader ? wp_remote_retrieve_header($response, $returnHeader) : wp_remote_retrieve_body($response);
        $this->main->write_log("Try API request \n\nRequest:\n" . print_r($args, true) . "\n\nResponse:\n" . print_r($return, true));
        return $return;
    }

    /**
     * @param $request
     * @return void
     * @throws \Exception
     */
    private function checkRequestAttemps($request = [])
    {
        $serialized_request = serialize($request);
        if ($serialized_request == $this->last_request) {
            $this->request_attemps = $this->request_attemps + 1;
            if ($this->request_attemps > $this->max_request_attempts) {
                $this->main->write_log(__('Max request attemps', 'cloud-customer-area'));
                throw new \Exception($this->main->get_settings('label_err_generic', 'customize'), 500);
            }
        } else {
            $this->last_request = $serialized_request;
        }
    }

    /**
     * @return false|string
     */
    public function getConnectUrl()
    {
        if (empty($this->client_id) || empty($this->client_secret)) {
            return false;
        }
        $base_url = 'https://accounts.google.com/o/oauth2/auth';
        $url_query = [
            'response_type' => 'code',
            'access_type' => 'offline',
            'approval_prompt' => 'force',
            'client_id' => $this->client_id,
            'redirect_uri' => rawurlencode($this->redirect_url),
            'state' => '',
            'scope' => rawurlencode('https://www.googleapis.com/auth/drive'),
        ];
        $url = add_query_arg($url_query, $base_url);
        return $url;
    }

    /**
     * @return string[]
     */
    public function getOauthHeaders()
    {
        $token = $this->getValidToken();
        return [
            'Authorization' => 'Bearer "' . $token->access_token . '"',
            'Accept' => 'application/json',
        ];
    }

    // Oauth token

    /**
     * @param $code
     * @return false|string
     */
    public function getToken($code = '')
    {
        if (empty($code)) {
            return false;
        }
        $args = [
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_url,
            'grant_type' => 'authorization_code',
        ];
        $response = $this->sendAPIRequest('https://accounts.google.com/o/oauth2/token', $args);
        return $response;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function updateToken($token)
    {
        $token->expires_in = time() - 10 + $token->expires_in;
        $this->main->update_setting('access_token', 'oauth', $token);
        return $token;
    }

    /**
     * @return bool
     */
    private function checkToken()
    {
        if (!isset($this->oauth_token->access_token)) {
            return false;
        }
        if (!empty($this->oauth_token->expires_in) && $this->oauth_token->expires_in > time()) {
            return true;
        }
        $args = [
            'access_token' => $this->oauth_token->access_token,
        ];
        $response = $this->sendAPIRequest('https://accounts.google.com/o/oauth2/tokeninfo', $args);
        if ($response) {
            return true;
        }
        return false;
    }

    /**
     * @return false|string
     */
    private function refreshToken()
    {
        if (!isset($this->oauth_token->refresh_token)) {
            return false;
        }
        $args = [
            'refresh_token' => $this->oauth_token->refresh_token,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'refresh_token',
        ];
        $response = $this->sendAPIRequest('https://accounts.google.com/o/oauth2/token', $args);
        return $response;
    }

    /**
     * @return false|mixed|string
     */
    private function getValidToken()
    {
        $check_request = $this->checkToken();
        if (!$check_request) {
            $store_refresh_token = $this->oauth_token->refresh_token;
            $refreshed_request = $this->refreshToken();
            if (!$refreshed_request || empty(json_decode($refreshed_request)->access_token)) {
                return $this->getValidToken();
            }
            $refreshed_request = json_decode($refreshed_request);
            $refreshed_request->refresh_token = $store_refresh_token;
            $this->oauth_token = $this->updateToken($refreshed_request);
        }
        return $this->oauth_token;
    }

    // Actions

    /**
     * @param $name
     * @return false|string
     */
    public function createFolder($name = false)
    {
        $args = [
            'name' => $name,
            'mimeType' => 'application/vnd.google-apps.folder',
        ];
        $headers = $this->getOauthHeaders();
        $headers['Content-Type'] = 'application/json';
        $response = $this->sendAPIRequest('https://www.googleapis.com/drive/v3/files', json_encode($args), 'POST', $headers);
        return $response;
    }

    /**
     * @param $fileID
     * @return false|string
     */
    public function getFileInfo($fileID = false)
    {
        $args = [
            'key' => $this->client_id,
            'fields' => 'name, mimeType, size',
        ];
        $headers = $this->getOauthHeaders();
        $response = $this->sendAPIRequest('https://www.googleapis.com/drive/v3/files/' . $fileID, $args, 'GET', $headers);
        return $response;
    }

    /**
     * @param $q
     * @return false|string
     */
    public function listFiles($q = '')
    {
        $args = [
            'key' => $this->client_id,
            'fields' => 'nextPageToken, files(id, name, modifiedTime, iconLink, mimeType, size)',
        ];
        if (!empty($q)) {
            $args['q'] = $q;
        }
        $headers = $this->getOauthHeaders();
        $response = $this->sendAPIRequest('https://www.googleapis.com/drive/v3/files', $args, 'GET', $headers);
        return $response;
    }

    /**
     * @param $fileId
     * @param $part
     * @return false|string
     */
    public function downloadFile($fileId = false, $part = false)
    {
        $chunkSizeBytes = apply_filters('cca_download_max_chunk_size', 3) * 1024 * 1024;
        $chunkStart = ($chunkSizeBytes * ($part - 1)) + ($part - 1);
        $chunkEnd = $chunkStart + $chunkSizeBytes;
        $headers = $this->getOauthHeaders();
        unset($headers['Accept']);
        $headers['Range'] = sprintf('bytes=%s-%s', $chunkStart, $chunkEnd);
        $content = $this->sendAPIRequest('https://www.googleapis.com/drive/v3/files/' . $fileId . '/?alt=media', '', 'GET', $headers);
        return $content;
    }

    /**
     * @param $fileId
     * @param $mime
     * @return false|string
     */
    public function exportFile($fileId = false, $mime = false)
    {
        $headers = $this->getOauthHeaders();
        unset($headers['Accept']);
        $content = $this->sendAPIRequest('https://www.googleapis.com/drive/v3/files/' . $fileId . '/export?mimeType=' . $mime, '', 'GET', $headers);
        return $content;
    }
}
