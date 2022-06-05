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

        update_option('employbrand_apply_vacancy_slug', $company->vacancySlug);
        update_option('employbrand_apply_environment_types', $environmentTypes);
    }

}
