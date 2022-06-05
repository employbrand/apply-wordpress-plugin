<?php

namespace EmploybrandApply;

use EmploybrandApply\Api\Company;
use EmploybrandApply\Api\Environment;
use EmploybrandApply\Api\Vacancy;
use EmploybrandApply\Api\Webhook;
use EmploybrandApply\Exceptions\Http\InternalServerError;
use EmploybrandApply\Exceptions\Http\NotFound;
use EmploybrandApply\Exceptions\Http\NotValid;
use EmploybrandApply\Exceptions\Http\PerformingMaintenance;
use EmploybrandApply\Exceptions\Http\TooManyAttempts;
use EmploybrandApply\Exceptions\Http\Unauthenticated;
use Exception;
use GuzzleHttp\Client;


class EmploybrandApplyClient
{


    private $guzzle;

    private $url = 'https://api.apply.employbrand.app';

    private $vacancies;

    private $environments;

    private $company;

    private $webhooks;


    public function __construct(string $companyId, string $token)
    {
        $this->guzzle = new Client([
            'base_uri' => $this->url,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'X-Company' => $companyId,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        return $this;
    }


    public function makeAPICall(string $url, string $method = 'GET', array $options = [])
    {
        if( !in_array($method, ['GET', 'POST', 'PUT', 'DELETE']) ) {
            throw new Exception('Invalid method type');
        }

        $response = $this->guzzle->request($method, $url, $options);

        switch ( $response->getStatusCode() ) {
            case 401:
                throw new Unauthenticated($response->getBody());
            case 404:
                throw new NotFound($response->getBody());
            case 422:
                throw new NotValid($response->getBody());
            case 429:
                throw new TooManyAttempts($response->getBody());
            case 500:
                throw new InternalServerError($response->getBody());
            case 503:
                throw new PerformingMaintenance($response->getBody());
        }

        return json_decode($response->getBody()->getContents(), true);
    }


    public function vacancies(): Vacancy
    {
        if( $this->vacancies == null )
            $this->vacancies = new Vacancy($this);

        return $this->vacancies;
    }


    public function environments(): Environment
    {
        if( $this->environments == null )
            $this->environments = new Environment($this);

        return $this->environments;
    }


    public function webhooks(): Webhook
    {
        if( $this->webhooks == null )
            $this->webhooks = new Webhook($this);

        return $this->webhooks;
    }


    public function company(): Company
    {
        if( $this->company == null )
            $this->company = new Company($this);

        return $this->company;
    }

}
