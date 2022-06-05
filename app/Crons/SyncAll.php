<?php

namespace EmploybrandApply\Crons;


use EmploybrandApply\Sync\SyncCompany;
use EmploybrandApply\Sync\SyncEnvironments;
use EmploybrandApply\Sync\SyncVacancies;


class SyncAll
{

    public static function register()
    {
        /*
         * Schedule daily
         */
        if( !wp_next_scheduled('employbrand_apply_daily_sync') ) {
            wp_schedule_event(strtotime('tomorrow 01:00'), 'daily', 'employbrand_apply_daily_sync');
        }

        /*
         * Perform cronjob
         */
        add_action( 'employbrand_apply_daily_sync', function () {

            $syncCompany = new SyncCompany();
            $syncCompany->sync();

            $syncEnvironment = new SyncEnvironments();
            $syncEnvironment->syncAll();

            $syncVacancies = new syncVacancies();
            $syncVacancies->syncAll();
        } );
    }

}
