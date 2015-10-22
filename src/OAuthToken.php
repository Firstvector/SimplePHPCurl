<?php
namespace SimplePHPCurl;

/**
 * OAuth Access Token.
 *
 * @package SimplePHPCurl
 */
class OAuthToken extends AccessToken
{
    private $headers = [];
    private $secretKeys = [];

    public function __construct($consumer_key, $consumer_secret, $access_key, $access_secret)
    {
        $this->secretKeys['consumer'] = (string) $consumer_secret;
        $this->secretKeys['access'] = (string) $access_secret;

        $this->headers['oauth_consumer_key'] = (string) $consumer_key;
        $this->headers['oauth_token'] = (string) $access_key;
        $this->headers['oauth_version'] = '1.0';
        $this->headers['oauth_signature_method'] = 'HMAC-SHA1';
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareToken()
    {
        $this->headers['oauth_timestamp'] = time();
        $this->headers['oauth_nonce'] = md5($this->headers['oauth_timestamp']);

        $params = $this->headers + $this->getRequestFields();

        ksort($params);

        $this->headers['oauth_signature'] = urlencode(base64_encode(hash_hmac(
            'sha1',
            self::encode([$this->getRequestMethod(), $this->getRequestUrl(), http_build_query($params)]),
            self::encode($this->secretKeys),
            true
        )));

        ksort($this->headers);

        foreach ($this->headers as $name => $value) {
            $this->headers[$name] = sprintf('%s="%s"', $name, $value);
        }

        $this->setType('OAuth');
        $this->setToken(implode(', ', $this->headers));
    }

    private static function encode(array $data)
    {
        return implode('&', array_map('rawurlencode', $data));
    }
}
