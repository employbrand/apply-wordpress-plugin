<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Entity\CompanyEntity;


class CompanyApi extends AbstractApi
{

    /**
     * Get company settings
     *
     * @return CompanyEntity
     */
    public function get(): CompanyEntity
    {
        return new CompanyEntity($this->getRequest('company'));
    }


}
