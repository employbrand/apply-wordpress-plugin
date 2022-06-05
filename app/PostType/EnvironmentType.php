<?php

namespace EmploybrandApply\PostType;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class EnvironmentType
{

    public static function register()
    {
        $instance = new self;

        add_action('init', [$instance, 'registerPostType']);
//        add_action('carbon_fields_register_fields', [$instance, 'registerMetaboxes']);
    }


    public function registerPostType()
    {
        $environmentTypes = get_option('employbrand_apply_environment_types');

        if($environmentTypes != false) {

            foreach($environmentTypes as $environmentType) {
                register_post_type('eb_et_' . $environmentType['id'],
                    [
                        'labels' => [
                            'name' => $environmentType['name'],
                            'singular_name' => $environmentType['singular_name'],
                        ],
                        'show_in_menu' => false,
                        'capability_type' => 'post',
                        'public' => true,
                        'has_archive' => true,
                        'slug' => $environmentType['slug'],
                        'rewrite' => ['slug' => $environmentType['slug'], 'with_front' => true],
                        'supports' => ['title']
                    ]
                );
            }
        }
    }


    public function registerMetaBoxes()
    {
        $onlyTsf = __('Dit veld kan alleen in tsf aangepast worden.', 'tsf-integration');

        Container::make('post_meta', __('Vacature instellingen', 'tsf-integration'))
            ->where('post_type', '=', 'vacatures')
            ->add_fields([
                Field::make('text', 'tsf_id', __('TSF ID', 'tsf-integration') . ' (tsf_id)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('textarea', 'korte_inhoud', __('Korte inhoud', 'tsf-integration') . ' (korte_inhoud)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'tsf_url', __('TSF url', 'tsf-integration') . ' (tsf_url')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'publicatie_startdatum', __('Publicatie startdatum', 'tsf-integration') . ' (publicatie_startdatum)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'publicatie_einddatum', __('Publicatie einddatum', 'tsf-integration') . ' (publicatie_einddatum)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('rich_text', 'content', __('Content', 'tsf-integration') . ' (content)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'locatie_naam', __('Locatie naam', 'tsf-integration') . ' (locatie_naam)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'locatie_adres', __('Locatie adres', 'tsf-integration') . ' (locatie_adres)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'locatie_postcode', __('Locatie postcode', 'tsf-integration') . ' (locatie_postcode)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'locatie_city', __('Locatie city', 'tsf-integration') . ' (locatie_city)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'locatie_afkorting', __('Locatie afkorting', 'tsf-integration') . ' (locatie_afkorting)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'schaal_min', __('Loonschaal minimaal', 'tsf-integration') . ' (schaal_min)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'schaal_max', __('Loonschaal maximaal', 'tsf-integration') . ' (schaal_max)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'salaris_min', __('Salaris minimaal', 'tsf-integration') . ' (salaris_min)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'salaris_max', __('Salaris maximaal', 'tsf-integration') . ' (salaris_max)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'uren_per_week_min', __('Uren per week minimaal', 'tsf-integration') . ' (uren_per_week_min)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'uren_per_week_max', __('Uren per week maximaal', 'tsf-integration') . ' (uren_per_week_max)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'uren_per_week', __('Uren per week', 'tsf-integration') . ' (uren_per_week)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'fte_min', __('FTE minimaal', 'tsf-integration') . ' (fte_min)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),

                Field::make('text', 'fte_max', __('FTE maximaal', 'tsf-integration') . ' (fte_max)')
                    ->set_attribute('readOnly', 'true')
                    ->set_help_text($onlyTsf),
            ]);
    }

}
