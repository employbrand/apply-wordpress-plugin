<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\EmploybrandApplyClient;


class ApiPaginator extends AbstractApi
{


    private string $model;

    private string $uri;

    private array $query = [];

    private $currentPage = 0;

    private int $lastPage = 0;

    private int $total = 0;

    private int $perPage = 25;


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
    public function query(array $query): ApiPaginator
    {
        $this->query = $query;
        return $this;
    }


    /**
     * Load a single page
     *
     * @param int $page
     * @param int|null $perPage
     * @return array
     */
    public function page(int $page = 1, int $perPage = null): array
    {
        if($perPage != null) $this->perPage = $perPage;
        $this->currentPage = $page;

        $all = $this->getRequest($this->uri, array_merge($this->query, ['page' => $page, $this->perPage]));

        $this->total = $all[ 'meta' ][ 'total' ];
        $this->lastPage = $all[ 'meta' ][ 'last_page' ];

        return \array_map(function ($entity) {
            return new $this->model($entity);
        }, $all[ 'data' ]);
    }


    /**
     * Load all the results (multiple calls)
     *
     * @return array
     */
    public function all(): array
    {
        $results = [];

        while($this->hasNext()) {
            $results = array_merge($results, $this->getNext());
        }
        return $results;
    }


    /**
     * Load the next page
     *
     * @return array
     */
    public function getNext(): array
    {
        if(!$this->hasNext()) return [];

        return $this->page($this->currentPage+1);
    }


    /**
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->lastPage == 0 || $this->lastPage > $this->currentPage;
    }


    /**
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }


    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }


    /**
     * @return mixed
     */
    public function getLastPage()
    {
        return $this->lastPage;
    }


    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

}
