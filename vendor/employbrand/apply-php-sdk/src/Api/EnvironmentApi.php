<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Entity\EnvironmentEntity;


class EnvironmentApi extends AbstractApi
{

    /**
     * Get environments with pagination
     *
     * @return ApiPaginator
     */
    public function list(): ApiPaginator
    {
        return new ApiPaginator($this->client, 'environments', EnvironmentEntity::class);
    }


    /**
     * Get an environment by id
     *
     * @param $id
     * @return EnvironmentEntity
     */
    public function getById($id): EnvironmentEntity
    {
        return new EnvironmentEntity($this->getRequest('environments/' . $id));
    }


}
