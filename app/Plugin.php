<?php

namespace EmploybrandApply;

use Carbon_Fields\Carbon_Fields;
use EmploybrandApply\Crons\SyncAll;
use EmploybrandApply\Http\V1\Controllers\WebhookController;
use EmploybrandApply\PostType\EnvironmentType;
use EmploybrandApply\PostType\Vacancy;
use EmploybrandApply\Page\Options;


class Plugin
{

    public function __construct()
    {
        add_action('after_setup_theme', [$this, 'loadCarbon']);


        /*
         * Register post types
         */
        Vacancy::register();
        EnvironmentType::register();


        /*
         * Register controllers
         */
        WebhookController::register();


        /*
         * Register settings
         */
        Options::register();

        /*
         * Register cronjobs
         */
        SyncAll::register();
    }


    function loadCarbon()
    {
        Carbon_Fields::boot();
    }

}
