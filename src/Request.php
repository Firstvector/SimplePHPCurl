<?php
namespace SimplePHPCurl;

/**
 * cURL Request.
 *
 * @package SimplePHPCurl
 */
class Request
{
    /**
     * @var AccessToken
     *   Authorization header string.
     */
    private $auth;
    /**
     * @var array
     *   Curl query options.
     */
    private $options = [];
    /**
     * @var resource
     *   Curl resource handler.
     */
    private $handler;
    private $headers = [];

    /**
     * @param string $url
     *   Url on which will be sent the query.
     */
    public function __construct($url)
    {
        $this->handler = curl_init();
        $this->options += [
            CURLOPT_URL => (string) $url,
            CURLOPT_RETURNTRANSFER => true,
        ];
    }

    /**
     * Close Curl connection after completing the request.
     */
    public function __destruct()
    {
        if ('' === curl_error($this->handler)) {
            curl_close($this->handler);
        }
    }

    /**
     * @param AccessToken $token
     *   Authorization token object that contains "type" and "token".
     *
     * @return self
     */
    public function authorization(AccessToken $token)
    {
        $this->auth = $token;
        $this->auth->setRequestUrl($this->options[CURLOPT_URL]);

        return $this;
    }

    /**
     * @param array $params
     *   An associative array with pairs of query param name and it value.
     *
     * @return self
     */
    public function post(array $params)
    {
        if (null !== $this->auth) {
            $this->auth->setRequestMethod(__FUNCTION__);
            $this->auth->setRequestFields($params);
        }

        $this->options += [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $params,
        ];

        return $this;
    }

    /**
     * @param array $params
     *   An associative array with pairs of query param name and it value.
     *
     * @return self
     */
    public function get(array $params = [])
    {
        if (null !== $this->auth) {
            $this->auth->setRequestMethod(__FUNCTION__);
            $this->auth->setRequestFields($params);
        }

        if (!empty($params)) {
            $this->options[CURLOPT_URL] .= '?' . http_build_query($params);
        }

        $this->options[CURLOPT_HTTPGET] = true;

        return $this;
    }

    public function verbose()
    {
        $this->options[CURLOPT_VERBOSE] = true;

        return $this;
    }

    public function headers(array $headers)
    {
        $this->headers += $headers;

        return $this;
    }

    /**
     * @return mixed|bool
     *   Query result or false if it fails.
     */
    public function execute()
    {
        if (isset($this->options[CURLOPT_POSTFIELDS])) {
            if (isset($this->headers['Content-Type'])) {
                $params = [];

                foreach ($this->options[CURLOPT_POSTFIELDS] as $field => $value) {
                    $params[] = "$field=$value";
                }

                $this->options[CURLOPT_POSTFIELDS] = implode(';', $params);
            } else {
                $this->headers['Content-Type'] = 'application/json';
                $this->options[CURLOPT_POSTFIELDS] = json_encode($this->options[CURLOPT_POSTFIELDS]);
            }

            $this->headers['Content-Length'] = strlen($this->options[CURLOPT_POSTFIELDS]);
        }

        if (null !== $this->auth) {
            $this->headers['Authorization'] = (string) $this->auth;
        }

        foreach ($this->headers as $header => $value) {
            $this->options[CURLOPT_HTTPHEADER][] = "$header: $value";
        }

        if (curl_setopt_array($this->handler, $this->options)) {
            $data = curl_exec($this->handler);
            $json = json_decode($data);

            return empty($json) ? $data : $json;
        }

        return false;
    }
}
