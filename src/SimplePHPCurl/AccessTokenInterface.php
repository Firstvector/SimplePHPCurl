<?php
namespace SimplePHPCurl;

interface AccessTokenInterface
{
    /**
     * Get type of authorization header.
     *
     * @return string
     */
    public function getAuthType();

    /**
     * Get the authorization header.
     *
     * @return string
     */
    public function getToken();
}
