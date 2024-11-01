<?php

function tp_ytw_get_data($url){

    $response = wp_remote_get( esc_url_raw( $url ) );

    /* Will result in $api_response being an array of data,
    parsed from the JSON response of the API listed above */

    $api_response = json_decode( wp_remote_retrieve_body( $response ), true );

    return $api_response;
}

function tp_ytw_format_numbers($number){

    if ($number < 10000) {
        // Anything less than a million
        // $number = number_format($number);
    } else if ($number < 1000000) {
        // Anything less than a 1000
        $number = number_format($number / 1000, 3) . 'K';
    } else if ($number < 1000000000) {
        // Anything less than a billion
        $number = number_format($number / 1000000, 1, '.', '') . 'M';
    } else {
        // At least a billion
        $number = number_format($number / 1000000000, 1, '.', '') . 'B';
    }

    return $number;
}
