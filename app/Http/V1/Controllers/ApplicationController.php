<?php

namespace EmploybrandApply\Http\V1\Controllers;


use EmploybrandApply\EmploybrandApplyClient;


class ApplicationController extends BaseController
{

    public static function register()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'employbrand-apply/v1', '/apply', [
                'methods' => 'POST',
                'callback' => [new self, 'handleApply'],
            ] );
        } );
    }


    public function handleApply( $request ) {

        $candidateData = [
            'files' => [],
            'sources' => [
                "website"
            ],
            'notify' => true
        ];

        $fields = [];

        /*
         * Application for vacancy
         */
        if($request->get_param('vacancy_id') != '') {

            global $wpdb;
            $wpId = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_vacancy_id' AND `meta_value` = %s LIMIT 0,1", $request->get_param('vacancy_id')));
            $wpId = (count($wpId) == 0 ? null : $wpId[ 0 ]->post_id);

            if($wpId == null) {
                return $this->invalid($request, 'Vacancy ID does not exists.');
            }

            $candidateData['vacancy_id'] = $request->get_param('vacancy_id');
            $candidateData['environment_id'] = get_post_meta($wpId, '_eb_environment_id', true);

            /*
             * Get custom fields
             */
            $fields = get_post_meta($wpId, '_form_fields', true);
            if($fields == null) $fields = [];
        }
        /*
         * Application for environment
         */
        else if($request->get_param('environment_id') != '') {

            global $wpdb;
            $wpId = $wpdb->get_results($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_eb_environment_id' AND `meta_value` = %s LIMIT 0,1", $request->get_param('environment_id')));
            $wpId = (count($wpId) == 0 ? null : $wpId[ 0 ]->post_id);

            if($wpId == null) {
                return $this->invalid($request, 'Environment ID does not exists.');
            }

            $candidateData['environment_id'] = $request->get_param('environment_id');

            /*
             * Get custom fields
             */
            $fields = get_option('employbrand_apply_application_form');
        }
        else {
            return $this->invalid($request, 'Environment ID or Vacancy ID missing.');
        }

        /*
         * Validation
         */
        foreach($fields as $field) {

            if($field[ 'required' ]) {

                if( $field[ 'type' ] === 'file' ) {

                    if($_FILES["field-" . $field['id']] == null) {
                        return $this->invalid($request, 'File ' . $field[ 'name' ] . ' is required.');
                    }
                }
                else {

                    if( $request->get_param('field-' . $field[ 'id' ]) == '' ) {
                        return $this->invalid($request, 'Field ' . $field[ 'name' ] . ' is required.');
                    }
                }
            }
        }

        /*
         * Client
         */
        $client = new EmploybrandApplyClient(carbon_get_theme_option('employbrand_apply_company_id'), carbon_get_theme_option('employbrand_apply_api_key'), $candidateData['environment_id']);

        /*
         * Set values
         */
        foreach($fields as $field) {

            if($field['type'] === 'file') {
                /*
                 * File
                 */

                if($_FILES["field-" . $field['id']] == null) continue;

                $file = $_FILES["field-" . $field['id']];

                try {
                    $file = $client->files()->upload($file["name"], $file["mime"], fopen($file["tmp_name"], 'r'), [
                        'private' => true
                    ]);
                }
                catch (\Exception $exception) {
                    $error = json_decode($exception->getMessage());

                    $response = new \WP_REST_Response( $request );

                    $message = $error->errors->message[0];

                    if($message === 'Het bestandstype is niet toegestaan.') $message = 'Het bestandstype is niet toegestaan. Upload met voorkeur een .pdf of .docx bestand.';

                    $response->set_data(['status' => 'ERROR', 'message' => $message]);
                    $response->set_status(422);
                    return $response;
                }

                $candidateData['files'][] = [
                    'name' => $field['name'],
                    'file_id' => $file->id
                ];
            }
            else if($field['type'] === 'checkbox') {
                /*
                 * Checkbox
                 */
                $value = $request->get_param('field-' . $field[ 'id' ]);
                if( $value == '' ) {
                    continue;
                }

                $candidateData['custom_fields'][] = [
                    'name' => $field['name'],
                    'value' => ($value === 'true' ? 'Ja' : 'Nee')
                ];
            }
            else {
                /*
                 * Normal field
                 */
                $value = $request->get_param('field-' . $field[ 'id' ]);
                if( $value == '' ) {
                    continue;
                }

                if($field['system_type'] === 'first_name' || $field['system_type'] === 'last_name') {
                    $candidateData[ $field['system_type'] ] = $value;
                }
                else if($field['system_type'] === 'email') {
                    $candidateData['emails'][] = $value;
                }
                else if($field['system_type'] === 'phone') {
                    $candidateData['phones'][] = $value;
                }
                else {
                    $candidateData['custom_fields'][] = [
                        'name' => $field['name'],
                        'value' => $value
                    ];
                }
            }
        }

        $candidateData['gdpr_store_year'] = $request->get_param('gdpr_store_year') === 'true' ? 1 : 0;

        try {
            $client->candidates()->create($candidateData);

            $response = new \WP_REST_Response( $request );
            $response->set_data(['status' => 'SUCCESS']);
            return $response;
        }
        catch (\Exception $exception) {
            $error = json_decode($exception->getMessage());

            $response = new \WP_REST_Response( $request );
            $response->set_data(['status' => 'ERROR', 'message' => 'Er ging helaas wat fout. Probeer het nogmaals, of neem contact met ons op via het contactformulier. Excuses voor het ongemak!']);
            $response->set_status(422);
            return $response;
        }
    }


}
