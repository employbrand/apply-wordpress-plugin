<?php

namespace EmploybrandApply\PostType;

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use EmploybrandApply\EmploybrandApplyClient;


class Vacancy
{

    public static function register()
    {
        $instance = new self;

        add_action('init', [$instance, 'registerPostType']);
    }


    public function registerPostType()
    {
        $slug = get_option('employbrand_apply_vacancy_slug') ?? 'vacatures';

        register_post_type('eb_vacancies',
            [
                'labels' => [
                    'name' => __('Vacatures', 'employbrand-apply'),
                    'singular_name' => __('Vacature', 'employbrand-apply'),
                ],
                'show_in_menu' => false,
                'capability_type' => 'post',
                'public' => true,
                'has_archive' => true,
                'slug' => $slug,
                'rewrite' => ['slug' => $slug, 'with_front' => true],
                'supports' => [
                    'title'
                ]
            ]
        );
    }

}
