<?php

namespace App\Helpers;

class CurlRequest
{
    private $url;
    private $headers = [];
    private $body = [];
    private $method;
    private $curl;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function execute($path)
    {
        $this->curl = curl_init();
        curl_setopt($this->curl, CURLOPT_URL, $this->url . $path);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->body);

        $response = curl_exec($this->curl);
        $error = curl_error($this->curl);

        curl_close($this->curl);

        if ($error) {
            throw new \Exception("cURL Error: " . $error);
        }

        return $response;
    }

    public function get($path)
    {
        $this->setMethod("GET");
        return $this->execute($path);
    }

    public function post($path)
    {
        $this->setMethod("POST");
        return $this->execute($path);
    }

    public function put($path)
    {
        $this->setMethod("PUT");
        return $this->execute($path);
    }

    public function delete($path)
    {
        $this->setMethod("DELETE");
        return $this->execute($path);
    }
}
