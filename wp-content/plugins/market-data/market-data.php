<?php
/**
Plugin Name: Market Data
description: property Data
Add GitHub project information using shortcode
Version: 1.0
Author: Asaas Company
License: GPLv2 or later
Text Domain: Asaas
 */

defined( 'ABSPATH' ) or die('Unauthorized Access');

add_action('admin_init','callback_function_name');
function callback_function_name(){
    $data = get_offer_func();
    get_offer_title($data);
}

function get_offer_func(){
    $url = 'https://marketing-api.asaas.sa/api/get_offers';
    $arguments = array(
        'headers' => array(
            'office-number' => '9665704',
        ),
    );
    $response = wp_remote_get($url, $arguments);
    $offerArray = [];
    if(is_wp_error($response)){
        echo 'Error !!! ';
    }
    else{
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );
        if(!empty($data)) {
            $offerArray = $data->data;
        }
    }
    return $offerArray;
}
add_shortcode( 'offer', 'get_offer_func' );


function display_html($attributes)
{

    $title = $attributes['title'];


    return '<blockquote>'
        . "<div>{$attributes['title']}</div>"
        . "<div>- {$attributes['title']}</div>"
        . '</blockquote>';
}

function get_offer_title($offerArray){
    $title_array = [];
    if(!empty($offerArray->data)) {
        foreach ($offerArray->data as $key=>$val)
        {
           array_push( $title_array,$val->title) ;
        }
    }
    return $title_array;
}
add_shortcode( 'offer_title', 'get_offer_title' );

function get_offer_price($offerArray){
    $price_array = [];
    if(!empty($offerArray->data)) {
        foreach ($offerArray->data as $key=>$val)
        {
           array_push( $price_array,$val->price) ;
        }
    }
    return $price_array;
}
add_shortcode( 'offer_price', 'get_offer_price' );

function get_requests_types(){
    $url = 'https://marketing-api.asaas.sa/api/get_requests_types';
    $arguments = array(
        'headers' => array(
            'office-number' => '9665704',
        ),
    );
    $allposts = 'asma  ';
    $response = wp_remote_get($url, $arguments);
    $requests_types_Array = [];
    if(is_wp_error($response)){
        echo 'Error !!! ';
    }
    else{
        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body );
        if(!empty($data)) {
            $requests_types_Array = $data->types;
        }

        foreach ( $data->types as $post ) {

            // Use print_r($post); to get the details of the post and all available fields
            // Format the date.

            // Show a linked title and post date.
            $allposts .= '<p>' . esc_html( $post ) . '</p>  '. '<br />';
        }

    }
    return $allposts;
}
add_shortcode( 'requests_types', 'get_requests_types' );

//add_action( 'plugins_loaded', array( $awsm_jobs_rest_api, 'run' ) );


