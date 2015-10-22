<?php
namespace SimplePHPCurl;

/**
 * Bearer Access Token.
 *
 * @package SimplePHPCurl
 */
class BearerToken extends AccessToken
{
    /**
     * @var string
     *   URL for requesting the token.
     */
    private $url = '';
    /**
     * @var string
     *   Consumer key of application.
     */
    private $consumer_key = '';
    /**
     * @var string
     *   Consumer secret key of application.
     */
    private $consumer_secret = '';

    public function __construct($url, $consumer_key, $consumer_secret)
    {
        $this->url = (string) $url;
        $this->consumer_key = (string) $consumer_key;
        $this->consumer_secret = (string) $consumer_secret;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareToken()
    {
        $response = (new Request($this->url))
          ->authorization(new parent('Basic', base64_encode("$this->consumer_key:$this->consumer_secret")))
          ->post(['grant_type' => 'client_credentials'])
          ->headers(['Content-Type' => 'application/x-www-form-urlencoded'])
          ->execute();

        if (is_object($response) && !isset($response->errors)) {
            $this->setType($response->token_type);
            $this->setToken($response->access_token);
        }
    }
}
