<?php

namespace EmploybrandApply\Http\V1\Controllers;

use Carbon_Fields\Container;
use Carbon_Fields\Field;


class BaseController
{

    protected function invalid( $request, $error = 'Invalid data.', $code = 422 ) {

        $response = new \WP_REST_Response( $request );
        $response->set_data(['error' => 'Invalid data.']);
        $response->set_status( 422 );
        return $response;
    }


}
