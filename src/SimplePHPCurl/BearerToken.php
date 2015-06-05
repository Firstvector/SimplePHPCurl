<?php
namespace SimplePHPCurl;


class BearerToken extends AccessToken
{
    /**
     * @param string $url
     *   URL for requesting the token.
     * @param string $consumer_key
     *   Consumer API key of the application.
     * @param string $consumer_secret
     *   Consumer API secret key of the application.
     */
    public function __construct($url, $consumer_key, $consumer_secret)
    {
        $response = (new Request($url))
            ->authorization(new parent('Basic', base64_encode("$consumer_key:$consumer_secret")))
            ->post(['grant_type' => 'client_credentials'])
            ->headers(['Content-Type' => 'application/x-www-form-urlencoded'])
            ->execute();

        if (is_object($response) && !isset($response->errors)) {
            $this->setAuthType(ucfirst($response->token_type))->setToken($response->access_token);
        }
    }
}
