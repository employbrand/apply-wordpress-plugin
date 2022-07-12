<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Entity\CandidateEntity;


class CandidateApi extends AbstractApi
{

    /**
     * Get candidates with pagination
     *
     * @return ApiPaginator
     */
    public function list(): ApiPaginator
    {
        return new ApiPaginator($this->client, 'candidates', CandidateEntity::class);
    }


    /**
     * Get a candidate by id
     *
     * @param $id
     * @return CandidateEntity
     */
    public function getById($id): CandidateEntity
    {
        return new CandidateEntity($this->getRequest('candidates/' . $id));
    }


    /**
     * Create a new candidate
     *
     * @param array|object $data
     * @return CandidateEntity
     */
    public function create($data): CandidateEntity
    {
        return new CandidateEntity($this->postRequest('candidates', $data));
    }


    /**
     * Update a candidate
     *
     * @param $id
     * @param array|object $data
     * @return CandidateEntity
     */
    public function update($id, $data): CandidateEntity
    {
        return new CandidateEntity($this->putRequest('candidates/' . $id, $data));
    }


    /**
     * Delete a candidate
     *
     * @param $id
     * @return CandidateEntity
     */
    public function delete($id): CandidateEntity
    {
        return new CandidateEntity($this->deleteRequest('candidates/' . $id));
    }


}
