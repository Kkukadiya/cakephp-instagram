<?php

namespace Instagram\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Client;

/**
 * Instagram component
 */
class InstagramComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    /**
     * Instagram access token
     * 
     * @var string
     */
    public $accessToken = "";

    /**
     * Startup method
     * 
     * Sets various configurations
     * 
     * @param Event $event
     * @return void
     */
    public function startup(Event $event)
    {
        $this->config('apiEndpoint', Configure::read('Instagram.apiEndpoint'));
        $this->config('apiEndpointUrl', Configure::read('Instagram.apiEndpoint') . '/' . Configure::read('Instagram.apiVersion'));
    }

    /**
     * Fetches oauth access token from Instagram
     * 
     * @param string $code Access code
     * @return mixed API Response
     */
    public function getAccessToken($code = null)
    {
        if (empty($code)) {
            return false;
        }

        $http = new Client();
        $response = $http->post($this->config('apiEndpoint') . '/oauth/access_token', [
            'client_id' => Configure::read('Instagram.config.clientId'),
            'client_secret' => Configure::read('Instagram.config.clientSecret'),
            'grant_type' => 'authorization_code',
            'redirect_uri' => Configure::read('Instagram.config.redirectUri'),
            'code' => $code
        ]);

        return $response->body('json_decode');
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
        if (empty($this->accessToken)) {
            return false;
        }

        $options += ['access_token' => $this->accessToken];

        $userId = "self";

        if (!empty($options['user_id'])) {
            $userId = $options['user_id'];
            unset($options['user_id']);
        }

        if (!empty($options['url'])) {
            $endpoint = $options['url'];
            unset($options['url']);
            unset($options['count']);
            unset($options['min_id']);
            unset($options['max_id']);
        } else {
            $endpoint = $this->config('apiEndpointUrl') .
                    "/users/{$userId}/media/recent";
        }

        $http = new Client();
        $response = $http->get($endpoint, $options);

        return $response->body('json_decode');
    }

}
