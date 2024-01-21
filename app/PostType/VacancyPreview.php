<?php

namespace EmploybrandApply\PostType;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use EmploybrandApply\EmploybrandApplyClient;
use EmploybrandApply\Sync\SyncVacancies;


class VacancyPreview
{

    public static function register()
    {
        $instance = new self;

        add_action('init', [$instance, 'registerPostType']);
    }


    public function registerPostType()
    {
        register_post_type('eb_vacancy_previews',
            [
                'labels' => [
                    'name' => __('Vacature voorvertoningen', 'employbrand-apply'),
                    'singular_name' => __('Vacature voorvertoning', 'employbrand-apply'),
                ],
                'show_in_menu' => true,
                'capability_type' => 'post',
                'public' => true,
                'has_archive' => false,
                'slug' => 'vacature-preview',
                'rewrite' => ['slug' => 'vacature-voorvertoningen', 'with_front' => true],
                'supports' => [
                    'title'
                ]
            ]
        );
    }

}
