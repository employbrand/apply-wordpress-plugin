<?php

namespace EmploybrandApply\Http\V1\Controllers;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use EmploybrandApply\Sync\SyncCompany;
use EmploybrandApply\Sync\SyncEnvironments;
use EmploybrandApply\Sync\SyncVacancies;


class WebhookController extends BaseController
{

    public static function register()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'employbrand-apply/v1', '/handle-webhook', [
                'methods' => 'POST',
                'callback' => [new self, 'handleWebhook'],
            ] );
        } );
    }


    public function handleWebhook( $request ) {

        if($request->get_param('type') == '') return $this->invalid($request);

        switch($request->get_param('type')) {
            case 'environment-created':
            case 'environment-updated':
            case 'environment-deleted':

                if($request->get_param('environment_id') == '') return $this->invalid($request);

                $syncEnvironments = new SyncEnvironments();
                $syncEnvironments->syncSingle($request->get_param('environment_id'));

                break;
            case 'vacancy-created':
            case 'vacancy-updated':
            case 'vacancy-deleted':

                if($request->get_param('vacancy_id') == '') return $this->invalid($request);

                $syncVacancies = new SyncVacancies();
                $syncVacancies->syncSingle($request->get_param('vacancy_id'));

                break;
            case 'environment-type-created':
            case 'environment-type-updated':
            case 'environment-type-deleted':
            case 'settings-changed':

                $syncCompany = new SyncCompany();
                $syncCompany->sync();

                break;
        }

    }


}
