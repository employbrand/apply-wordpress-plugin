<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Exceptions\Http\NotFound;
use EmploybrandApply\Entity\VacancyEntity;


class VacancyApi extends AbstractApi
{

    /**
     * Get vacancies with pagination
     *
     * @return ApiPaginator
     */
    public function list(): ApiPaginator
    {
        return new ApiPaginator($this->client, 'vacancies', VacancyEntity::class);
    }


    /**
     * Get a vacancy by id
     *
     * @param $id
     * @return VacancyEntity
     */
    public function getById($id): VacancyEntity
    {
        return new VacancyEntity($this->getRequest('vacancies/' . $id));
    }


    /**
     * Get a vacancy by full email address
     *
     * @param $email
     * @return VacancyEntity
     * @throws NotFound
     */
    public function getByEmail($email): VacancyEntity
    {
        $response = $this->getRequest('vacancies', ['email' => $email]);

        if( count($response['data']) === 0 )
            throw new NotFound();

        return new VacancyEntity($response[ 'data' ][ 0 ]);
    }


    /**
     * Create a new vacancy
     *
     * @param array|object $data
     * @return VacancyEntity
     */
    public function create($data): VacancyEntity
    {
        return new VacancyEntity($this->postRequest('vacancies', $data));
    }


    /**
     * Update a vacancy
     *
     * @param $id
     * @param array|object $data
     * @return VacancyEntity
     */
    public function update($id, $data): VacancyEntity
    {
        return new VacancyEntity($this->putRequest('vacancies/' . $id, $data));
    }


    /**
     * Delete a vacancy
     *
     * @param $id
     * @return VacancyEntity
     */
    public function delete($id): VacancyEntity
    {
        return new VacancyEntity($this->deleteRequest('vacancies/' . $id));
    }


}
