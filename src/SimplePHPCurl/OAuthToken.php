<?php
namespace SimplePHPCurl;

class OAuthToken extends AccessToken
{
    private $headers = [];
    private $secretKeys = [];

    public function __construct($consumer_key, $consumer_secret, $access_key, $access_secret)
    {
        $this->secretKeys['consumer'] = $consumer_secret;
        $this->secretKeys['access'] = $access_secret;

        $this->headers['oauth_consumer_key'] = $consumer_key;
        $this->headers['oauth_token'] = $access_key;
        $this->headers['oauth_version'] = '1.0';
        $this->headers['oauth_signature_method'] = 'HMAC-SHA1';
    }

    protected function prepareToken()
    {
        $this->headers['oauth_timestamp'] = time();
        $this->headers['oauth_nonce'] = md5($this->headers['oauth_timestamp']);

        $headers = [];
        $params = $this->headers + $this->getRequestFields();

        ksort($params);

        $this->headers['oauth_signature'] = urlencode(base64_encode(hash_hmac(
            'sha1',
            self::encodeUrlParts([$this->getRequestMethod(), $this->getRequestUrl(), http_build_query($params)]),
            self::encodeUrlParts($this->secretKeys),
            true
        )));

        ksort($this->headers);

        foreach ($this->headers as $name => $value) {
            $headers[] = $name . '="' . $value . '"';
        }

        $this->setAuthType('OAuth')->setToken(implode(', ', $headers));
    }

    private static function encodeUrlParts(array $data)
    {
        return implode('&', array_map('rawurlencode', $data));
    }
}
