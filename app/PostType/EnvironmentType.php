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
                        'show_in_menu' => true,
                        'capability_type' => 'post',
                        'public' => true,
                        'has_archive' => false,
                        'slug' => $environmentType['slug'],
                        'rewrite' => ['slug' => $environmentType['slug'], 'with_front' => true],
                        'supports' => ['title']
                    ]
                );
            }
        }
    }

}
