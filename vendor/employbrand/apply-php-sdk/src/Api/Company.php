<?php

namespace EmploybrandApply\Api;


use EmploybrandApply\Entity\Company as CompanyEntity;


class Company extends AbstractApi
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
