<?php
namespace SimplePHPCurl;

/**
 * Base object for access token.
 *
 * @package SimplePHPCurl
 */
class AccessToken
{
    /**
     * @var string
     *   Access token type.
     */
    private $type = '';
    /**
     * @var string
     *   Authorization token.
     */
    private $token = '';
    /**
     * @var string
     *   Requesting URL.
     */
    private $requestUrl = '';
    /**
     * @var string
     *   HTTP requesting method.
     */
    private $requestMethod = '';
    /**
     * @var array
     *   Requesting fields.
     */
    private $requestFields = [];

    /**
     * Constructor for basic authorization.
     *
     * @param string $type
     * @param string $token
     */
    public function __construct($type, $token)
    {
        $this->setType($type);
        $this->setToken($token);
    }

    final public function __toString()
    {
        $this->prepareToken();

        return empty($this->token) ? '' : "$this->type $this->token";
    }

    final public function setType($type)
    {
        $this->type = ucfirst((string) $type);
    }

    final public function setToken($token)
    {
        $this->token = (string) $token;
    }

    final public function setRequestUrl($url)
    {
        $this->requestUrl = (string) $url;
    }

    final public function setRequestMethod($method)
    {
        $this->requestMethod = strtoupper((string) $method);
    }

    final public function setRequestFields(array $fields)
    {
        $this->requestFields = $fields;
    }

    final public function getType()
    {
        return $this->type;
    }

    final public function getToken()
    {
        return $this->token;
    }

    final public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    final public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    final public function getRequestFields()
    {
        return $this->requestFields;
    }

    /**
     * Method for deferred preparation the token.
     */
    protected function prepareToken()
    {
    }
}
