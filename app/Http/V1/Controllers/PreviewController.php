<?php

namespace EmploybrandApply\Http\V1\Controllers;

use EmploybrandApply\EmploybrandApplyClient;
use EmploybrandApply\Exceptions\Http\NotFound;
use EmploybrandApply\Sync\SyncCompany;
use EmploybrandApply\Sync\SyncVacancies;


class PreviewController extends BaseController
{

    public static function register()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'employbrand-apply/v1', '/preview', [
                'methods' => 'GET',
                'callback' => [new self, 'handleWebhook'],
            ] );
        } );
    }


    public function handleWebhook( $request ) {

        if($request->get_param('id') == '') return $this->invalid($request);
        if($request->get_param('version') == '') return $this->invalid($request);

        $client = new EmploybrandApplyClient(carbon_get_theme_option('employbrand_apply_company_id'), carbon_get_theme_option('employbrand_apply_api_key'));

        try {
            $vacancy = $client->vacancies()->getById($request->get_param('id'));

            if( $vacancy->version != $request->get_param('version') ) {
                return $this->invalid($request);
            }

            $sync = new SyncVacancies();
            $wpId = $sync->syncSingle($vacancy->id, true);

            var_dump($wpId);

            header("Location: " . get_permalink($wpId));
            exit;
        }
        catch(NotFound $exception) {
            return $this->invalid($request);
        }
    }


}
