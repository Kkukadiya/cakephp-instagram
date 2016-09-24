<?php

namespace Instagram\Utility;

use Cake\Core\Configure;
use Cake\Http\Client;

/*
 * Instagram client
 * 
 */

class InstagramClient
{

    /**
     * Instagram API endpoint
     * 
     * @var string URL
     */
    public $apiEndpoint = "";

    /**
     * Instagram API endpoint URL
     * 
     * @var string URL
     */
    public $apiEndpointUrl = "";

    /**
     * Access token
     * 
     * @var string
     */
    public $_accessToken = "";

    /**
     * Instagram Client ID
     * 
     * @var string
     */
    protected $_clientId = "";

    /**
     * Instagram Client Secret
     * 
     * @var string
     */
    protected $_clientSecret = "";

    /**
     * 
     * The $options array accepts the following keys:
     * 
     * - clientId: Instagram API Key 
     * - clientSecret: Instagram API Secret
     * - redirectURL: Redirect URL
     * 
     * @param array $options 
     */
    public function __construct($options = [])
    {
        if (empty($options['clientId']) || empty($options['clientSecret'])) {
            throw new Exception\MissingCredentialsException('Api Key or Secret is missing.');
        }

        $this->_clientId = $options['clientId'];
        $this->_clientSecret = $options['clientSecret'];

        $this->apiEndpoint = Configure::read('Instagram.apiEndpoint');
        $this->apiEndpointUrl = Configure::read('Instagram.apiEndpoint') . '/' . Configure::read('Instagram.apiVersion');
    }

    /**
     * Fetches oauth access token from Instagram
     * 
     * @param string $code Access code
     * @param string $redirectUrl Auth redirection url
     * @return mixed API Response
     */
    public function getAccessToken($code = null, $redirectUrl = null)
    {
        if (empty($code) || empty($redirectUrl)) {
            return false;
        }

        $http = new Client();
        $response = $http->post($this->apiEndpoint . '/oauth/access_token', [
            'client_id' => $this->_clientId,
            'client_secret' => $this->_clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $redirectUrl,
            'code' => $code
        ]);

        return $response->body('json_decode');
    }

    /**
     * Gets/Sets access token
     * 
     * @param string $token Authentication token
     * @return mixes String if $token is empty, true otherwise
     */
    public function accessToken($token = null)
    {
        if ($token === null) {
            return $this->_accessToken;
        }
        $this->_accessToken = $token;
        return true;
    }

    /**
     * Returns recent media
     * 
     * The $options array accepts the following keys:
     * 
     * - user_id: User id (self will be used if not set)
     * - url: API url (mostly used in pagination (next_url), if set other 
     *   options will be ignored)
     * - count: Count of media to return
     * - min_id: Return media later than this min_id
     * - max_id: Return media earlier than this max_ids
     * 
     * @param array $options List of options for this API method
     * @return mixed API Response or False if no access token
     */
    public function getMedia(array $options = [])
    {
        if (empty($this->_accessToken)) {
            return false;
        }

        $userId = "self";
        if (!empty($options['user_id'])) {
            $userId = $options['user_id'];
            unset($options['user_id']);
        }

        if (!empty($options['url'])) {
            $endpoint = $options['url'];
            $options = [];
        } else {
            $options += ['access_token' => $this->_accessToken];
            $endpoint = $this->apiEndpointUrl .
                    "/users/{$userId}/media/recent";
        }

        $http = new Client();
        $response = $http->get($endpoint, $options);

        return $response->body('json_decode');
    }

}
