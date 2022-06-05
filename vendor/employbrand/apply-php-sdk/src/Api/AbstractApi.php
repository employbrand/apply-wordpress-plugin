<?php

namespace EmploybrandApply\Api;

use EmploybrandApply\EmploybrandApplyClient;


abstract class AbstractApi
{

    protected $baseUri = '/app/v1/';

    protected $client;


    public function __construct(EmploybrandApplyClient $client)
    {
        $this->client = $client;
    }


    protected function getRequest(string $uri, array $query = [])
    {
        return $this->client->makeAPICall($this->baseUri . $uri, 'GET', [
            'query' => $query
        ]);
    }


    protected function postRequest(string $uri, $params = [])
    {
        $body = $this->prepareJsonBody($params);

        return $this->client->makeAPICall($this->baseUri . $uri, 'POST', [
            'json' => $body
        ]);
    }


    protected function putRequest(string $uri, $params = [])
    {
        $body = $this->prepareJsonBody($params);

        return $this->client->makeAPICall($this->baseUri . $uri, 'PUT', [
            'json' => $body
        ]);
    }


    protected function deleteRequest(string $uri)
    {
        return $this->client->makeAPICall($this->baseUri . $uri, 'DELETE');
    }


    private static function prepareJsonBody($params): ?array
    {
        if( is_object($params) && method_exists($params, 'toArray') ) {
            return $params->toArray();
        }

        return $params;
    }


}
