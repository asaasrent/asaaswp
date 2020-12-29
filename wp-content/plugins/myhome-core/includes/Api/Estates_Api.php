<?php

namespace MyHomeCore\Api;

use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Estates\Estates;
use MyHomeCore\Terms\Term;


/**
 * Class Estates_Api
 * @package MyHomeCore\Api
 */
class Estates_Api {

	/**
	 * Estates_Api constructor.
	 */

	public function __construct() {
		/*
		 * Set endpoints
		 */
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
            $data = json_decode(json_encode($body), true);
            $bodyData= json_decode($data->data, true);
            if(!empty($data)) {
                $offerArray = $bodyData;
            }
        }
        self::get($response);
		add_action(
			'rest_api_init',   self::get($offerArray)
		);

//		add_action(
//			'rest_api_init', function () {
//			register_rest_route(
//				'myhome/v1', '/suggestions-attributes', array(
//					'methods'  => 'GET',
//					'callback' => array( $this, 'suggestions_attributes' ),
//                    'permission_callback' => '__return_true',
//                )
//			);
//		}
//		);
	}
	public function suggestions_attributes( $request ) {
		$params = $request->get_params();

		if ( empty( $params['slug'] ) || empty( $params['query'] ) ) {
			return;
		}

		$suggestions = array();
		$wp_terms    = get_terms(
			array(
				'taxonomy'   => $params['slug'],
				'name__like' => $params['query'],
				'number'     => apply_filters( 'mh_suggestions_limit', 0 )
			)
		);

		foreach ( $wp_terms as $wp_term ) {
			$term = new Term( $wp_term );
			if ( $term->is_excluded_from_search() ) {
				continue;
			}

			/* @var $wp_term \WP_Term */
			$suggestions[] = array(
				'name'           => $wp_term->name,
				'slug'           => $wp_term->slug,
				'link'           => get_term_link( $wp_term, $params['slug'] ),
				'attribute_slug' => $params['slug']
			);
		}

		return $suggestions;
	}

	public static function get( $request ) {
		if ( is_object( $request ) && method_exists( $request, 'get_params' ) ) {
			$params = $request->get_params();
		} else {
			$params = $request;
		}
		$databody = $params['body'];

        $params = json_decode($databody, true);
    //    var_dump(empty( $params['data'] ) ? 'true' : 'false' );
        $filters         = empty( $params['data'] ) ? [] : $params['data'];
        $current_page = array_keys($filters)[0];
        $total = array_keys($filters)[11];

        $estates_factory = new Estate_Factory();
        $mydata=   $estates_factory->get_results($filters);
//        print_r($filters[$total]);exit();


//		foreach ( $filters['data'] as $filter ) {
//
//            $attribute = Attribute_Factory::get_by_slug( $filter['id'] );
//			if ( empty( $attribute ) ) {
//				continue;
//			}

//			$values = new Attribute_Values();
////			foreach ( $filter as $value ) {
//				$values->add( new Attribute_Value( $filter['title'], $filter['title'], '', $filter['value'] ) );
////			}
//			$estates_factory->add_filter( $attribute->get_estate_filter( $values, $filter['compare'] ) );

		//}


//		if ( isset( $params['currency'] ) && $params['currency'] != \MyHomeCore\My_Home_Core()->currency ) {
//			setcookie( 'myhome_currency', $params['currency'], time() + 3600, "/" );
//			\MyHomeCore\My_Home_Core()->currency = $params['currency'];
//		}

        //$params['current_page']
		if ( isset( $filters[$test[0]] ) ) {
                $estates_factory->set_page( $filters[$test[0]] );
		}

//		if ( isset( $params['sortBy'] ) ) {
//			$estates_factory->set_sort_by( $params['sortBy'] );
//		}
//

//		if ( isset( $params['limit'] ) ) {
//			$estates_factory->set_limit( $params['limit'] );
//		}

//		if ( ! empty( $params['mh_lang'] ) && function_exists( 'icl_object_id' ) ) {
//			\MyHomeCore\My_Home_Core()->current_language = $params['mh_lang'];
//			global $sitepress;
//			$sitepress->switch_lang( $params['mh_lang'] );
//		}

		if ( isset( $params['id'] ) ) {
			$estates_factory->set_user_id( $params['id'] );
		}

//		if ( isset( $params['users'] ) ) {
//			$estates_factory->set_users( $params['users'] );
//		}
//
//		if ( isset( $params['estates__in'] ) ) {
//			$estates_factory->set_estates__in( $params['estates__in'] );
//		}

//		if ( isset( $params['featured'] ) ) {
//			$estates_factory->set_featured_only();
//		}
//
//		if ( isset( $params['published_after'] ) ) {
//			$estates_factory->set_published_after( $params['published_after'] );
//		}

//		if ( isset( $params['map_lat'] ) && $params['map_lng'] == 'true' ) {
//			$estates_factory->set_limit( Estate_Factory::NO_LIMIT );
//
//			return array(
//				'results'       => $estates_factory->get_results()->get_data( Estates::MARKER_DATA ),
//				'found_results' => $estates_factory->get_found_number()
//			);
//		}

		return array(
			'results'       => $filters,
			'found_results' => $filters[$total]
		);
	}

}