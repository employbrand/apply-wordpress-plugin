<?php

namespace EmploybrandApply\Sync;


class SyncCompany extends SyncBase
{

    public function sync()
    {
        $company = $this->employbrandApplyClient->company()->get();

        $environmentTypes = [];
        foreach($company->environmentTypes as $et) {
            $environmentTypes[] = $et->toArray();
        }

        $applicationFormFields = [];
        foreach($company->applicationFormFields as $formField) {
            $applicationFormFields[] = $formField->toArray();
        }

        update_option('employbrand_apply_vacancy_slug', $company->vacancySlug);
        update_option('employbrand_apply_environment_types', $environmentTypes);
        update_option('employbrand_apply_application_form', $applicationFormFields);
    }

}
