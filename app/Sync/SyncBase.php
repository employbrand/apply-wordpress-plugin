<?php

namespace EmploybrandApply\Sync;


use EmploybrandApply\EmploybrandApplyClient;


class SyncBase
{

    protected EmploybrandApplyClient $employbrandApplyClient;


    public function __construct()
    {
        if( get_option('employbrand_apply_authenticated') == true ) {

            $this->employbrandApplyClient = new EmploybrandApplyClient(carbon_get_theme_option('employbrand_apply_company_id'), carbon_get_theme_option('employbrand_apply_api_key'));
        }
    }


    protected function getCustomFields($obj) {
        $metaData = [];

        /*
         * Normal fields
         */
        foreach ( $obj->availableCustomFields as $availableCustomField ) {

            $availableCustomField = (object) $availableCustomField;

            if( !in_array($availableCustomField->type, ['text', 'image', 'number', 'dropdown', 'repeater']) )
                continue;

            $key = '_' . $availableCustomField->field_key;
            $value = $obj->customFields[ 'field-' . $availableCustomField->id ] ?? null;

            if( $availableCustomField->type === 'image' ) {
                $value = ($value[ 'url' ] ?? null);
            }

            $metaData[ $key ] = $value;
        }

        /*
         * Functions
         */
        foreach ( $obj->availableCustomFields as $availableCustomField ) {

            $availableCustomField = (object) $availableCustomField;

            if( !in_array($availableCustomField->type, ['function']) )
                continue;

            $key = '_' . $availableCustomField->field_key;
            $value = null;

            if($availableCustomField->function_type === 'min-max') {
                $min = $metaData['_' . $availableCustomField->min_field];
                $max = $metaData['_' . $availableCustomField->max_field];

                $value = ($min === $max ? $min : $min . ' - ' . $max);
            }

            $metaData[ $key ] = $value;
        }

        return $metaData;
    }

}
