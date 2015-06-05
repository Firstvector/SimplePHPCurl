<?php
namespace SimplePHPCurl;

class AccessToken implements AccessTokenInterface
{
    private $url = '';
    private $fields = [];
    private $requestType = '';
    /**
     * @var string
     *   Authorization type.
     */
    private $type = '';
    /**
     * @var string
     *   Authorization token.
     */
    private $token = '';

    /**
     * @param string $type
     * @param string $token
     */
    public function __construct($type, $token)
    {
        $this->setAuthType($type)->setToken($token);
    }

    protected function prepareToken()
    {
    }

    final public function setRequestMethod($type)
    {
        $this->requestType = strtoupper($type);

        return $this;
    }

    final public function getRequestMethod()
    {
        return $this->requestType;
    }

    final public function setRequestFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    final public function getRequestFields()
    {
        return $this->fields;
    }

    final public function setRequestUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    final public function getRequestUrl()
    {
        return $this->url;
    }

    final public function setAuthType($type)
    {
        $this->type = $type;

        return $this;
    }

    final public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get type of the authorization header.
     *
     * @return string
     */
    final public function getAuthType()
    {
        return $this->type;
    }

    /**
     * Get the authorization header.
     *
     * @return string
     */
    final public function getToken()
    {
        return $this->token;
    }

    final public function isDataReady()
    {
        return (bool) $this->token;
    }

    final public function getHeader()
    {
        $this->prepareToken();

        return $this->isDataReady() ? "$this->type $this->token" : '';
    }
}
