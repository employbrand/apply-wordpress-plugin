<?php

namespace EmploybrandApply;

use EmploybrandApply\Api\CandidateApi;
use EmploybrandApply\Api\CompanyApi;
use EmploybrandApply\Api\EnvironmentApi;
use EmploybrandApply\Api\FileApi;
use EmploybrandApply\Api\VacancyApi;
use EmploybrandApply\Api\WebhookApi;
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

    private $candidates;

    private $company;

    private $webhooks;

    private $files;


    public function __construct(string $companyId, string $token, $environmentId = 1)
    {
        return $this->init($companyId, $token, $environmentId);
    }


    public function init(string $companyId, string $token, int $environmentId): EmploybrandApplyClient
    {
        $this->guzzle = new Client([
            'base_uri' => $this->url,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'X-Company' => $companyId,
                'X-Environment' => $environmentId,
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


    public function vacancies(): VacancyApi
    {
        if( $this->vacancies == null )
            $this->vacancies = new VacancyApi($this);

        return $this->vacancies;
    }


    public function environments(): EnvironmentApi
    {
        if( $this->environments == null )
            $this->environments = new EnvironmentApi($this);

        return $this->environments;
    }


    public function webhooks(): WebhookApi
    {
        if( $this->webhooks == null )
            $this->webhooks = new WebhookApi($this);

        return $this->webhooks;
    }


    public function candidates(): CandidateApi
    {
        if( $this->candidates == null )
            $this->candidates = new CandidateApi($this);

        return $this->candidates;
    }


    public function files(): FileApi
    {
        if( $this->files == null )
            $this->files = new FileApi($this);

        return $this->files;
    }


    public function company(): CompanyApi
    {
        if( $this->company == null )
            $this->company = new CompanyApi($this);

        return $this->company;
    }

}
