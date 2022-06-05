<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\EmploybrandApplyClient;


class ApiList extends AbstractApi
{


    private string $model;

    private string $uri;

    private array $query = [];


    /**
     * @param EmploybrandApplyClient $client
     * @param string $uri
     * @param string $model
     */
    public function __construct(EmploybrandApplyClient $client, string $uri, string $model)
    {
        parent::__construct($client);

        $this->uri = $uri;
        $this->model = $model;

        return $this;
    }


    /**
     * Add query parameters
     *
     * @param array $query
     * @return $this
     */
    public function query(array $query): ApiList
    {
        $this->query = $query;
        return $this;
    }


    /**
     * Load all the results (multiple calls)
     *
     * @return array
     */
    public function all(): array
    {
        $all = $this->getRequest($this->uri, $this->query);

        return \array_map(function ($entity) {
            return new $this->model($entity);
        }, $all);
    }

}
