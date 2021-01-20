<?php

namespace MyHomeCore\Components\Listing;


use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Attribute_Value;
use MyHomeCore\Attributes\Attribute_Values;
use MyHomeCore\Attributes\Price_Attribute_Options_Page;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Components\Listing\Form\Field;
use MyHomeCore\Components\Listing\Form\Fields;
use MyHomeCore\Components\Listing\Search_Forms\Search_Form;
use MyHomeCore\Estates\Estate_Factory;
use MyHomeCore\Terms\Term;
use MyHomeCore\Users\Users_Factory;

/**
 * Class Listing_Settings
 * @package MyHomeCore\Listing
 */
class Listing_Settings {

	/**
	 * @var array
	 */
	private $settings = array(
		'string' => array(
			'search_form_position' => 'top',
			'label'                => '',
			'listing_default_view' => 'colTwo',
			'load_more_button'     => '',
			'listing_sort_by'      => '',
			'listing_type'         => 'load_more'
		),
		'number' => array(
			'search_form_advanced_number' => 3,
			'estates_per_page'            => 12,
			'lazy_loading_limit'          => 1,
			'current_page'                => 1
		),
		'bool'   => array(
			'lazy_loading'                   => true,
			'show_advanced'                  => true,
			'show_clear'                     => true,
			'show_sort_by'                   => true,
			'show_view_types'                => true,
			'show_results_number'            => true,
			'featured'                       => false,
			'show_sort_by_newest'            => true,
			'show_sort_by_popular'           => true,
			'show_sort_by_price_high_to_low' => true,
			'show_sort_by_price_low_to_high' => true,
			'show_sort_by_alphabetically'    => false,
			'hide_save_search'               => false,
            'api'                            => true
		)
	);

	/**
	 * @var array
	 */
	private $args = array();

	/**
	 * Listing_Settings constructor.
	 *
	 * @param array $args
	 */
	public function __construct( $args ) {
		$this->args = $args;

		if ( isset( $_GET['sortBy'] ) && ! empty( $_GET['sortBy'] ) ) {
			$this->args['listing_sort_by'] = sanitize_text_field( $_GET['sortBy'] );
		}
	}

	/**
	 * @return array
	 */
	public function get_config() {
		$hash      = md5( json_encode( $this->args ) );
		$cache_key = 'myhome_cache_listing_' . $hash;

		if ( ! empty( \MyHomeCore\My_Home_Core()->current_language ) ) {
			$cache_key .= \MyHomeCore\My_Home_Core()->current_language;
		}

		if ( ! is_null( \MyHomeCore\My_Home_Core()->currency ) ) {
			$cache_key .= \MyHomeCore\My_Home_Core()->currency;
		}

		if ( ! empty( $_GET ) || \MyHomeCore\My_Home_Core()->development_mode || false === ( $config = get_transient( $cache_key ) ) ) {
			$config = array(
				'fields'       => Fields::get( $this->args )->get_data(),
				'api_endpoint' => get_rest_url() . 'myhome/v1/estates',
			);

			foreach ( $this->settings['string'] as $param_key => $param ) {
				$config[ $param_key ] = isset( $this->args[ $param_key ] ) ? $this->args[ $param_key ] : $param;
			}

			foreach ( $this->settings['number'] as $param_key => $param ) {
				$config[ $param_key ] = isset( $this->args[ $param_key ] ) ? intval( $this->args[ $param_key ] ) : $param;
			}

			foreach ( $this->settings['bool'] as $param_key => $param ) {
				$config[ $param_key ] = isset( $this->args[ $param_key ] ) ? filter_var( $this->args[ $param_key ], FILTER_VALIDATE_BOOLEAN ) : $param;
			}

			if ( isset( $this->args['show_initial_results'] ) ) {
				$display_initial_results = filter_var( $this->args['show_initial_results'], FILTER_VALIDATE_BOOLEAN );
			} else {
				$display_initial_results = 1;
			}

			$config['initial_results']  = $display_initial_results;
			$config['results']          = $display_initial_results ? $this->get_estates() : array();
			$config['dependencies']     = $this->get_dependencies();
			$config['default_values']   = $this->get_values( $this->args );
			$config['current_values']   = $this->get_values( $_GET );
			$config['homepage']         = is_front_page();
			$config['currencies']       = Price_Attribute_Options_Page::get_currencies();
			$config['current_currency'] = \MyHomeCore\My_Home_Core()->currency;
			$config['lang']             = \MyHomeCore\My_Home_Core()->current_language;
			$config['show_gallery']     = ! empty( \MyHomeCore\My_Home_Core()->settings->props['mh-listing-show_gallery'] );

			if ( isset( $_GET['property_ids'] ) && ! empty( $_GET['property_ids'] ) ) {
				$config['estates__in'] = explode( ',', $_GET['property_ids'] );
			}

			if ( isset( $_GET['current_page'] ) ) {
				$config['current_page'] = intval( $_GET['current_page'] );
			}

			if ( isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_show_date'] ) ) {
				$config['show_date'] = filter_var( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_show_date'], FILTER_VALIDATE_BOOLEAN );
			} else {
				$config['show_date'] = true;
			}

			if ( isset( $this->args['agents'] ) && ! empty( $this->args['agents'] ) ) {
				$config['users'] = $this->args['agents'];
			} elseif ( ! empty( $this->args['agent_id'] ) && $this->args['agent_id'] !== 'any' ) {
				$config['user_id'] = intval( $this->args['agent_id'] );
			}

			if ( empty( $_GET ) && ! \MyHomeCore\My_Home_Core()->development_mode ) {
				set_transient( $cache_key, $config );
			}
		}

		$config['key'] = 'MyHomeListing' . time();
		wp_localize_script( 'myhome-min', $config['key'], $config );

		return $config;
	}

	/**
	 * @return array
	 */
    public static function get_data_from_api($result_num = null){
        $url = 'https://marketing-api.asaas.sa/api/get_offers';
        $arguments = array(
            'headers' => array(
                'office-number' => '9665704',
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImJiMDQzYTFjNWEyNDZlZmNlMjc2YmY5MTEwNzIyZTIwMjY3OWM2NjA0ZDFmZTYwYWVjZGZmNmZlMzc3ZDZmNjA5MzY3YjY2MjIyZjcyZGZkIn0.eyJhdWQiOiI1IiwianRpIjoiYmIwNDNhMWM1YTI0NmVmY2UyNzZiZjkxMTA3MjJlMjAyNjc5YzY2MDRkMWZlNjBhZWNkZmY2ZmUzNzdkNmY2MDkzNjdiNjYyMjJmNzJkZmQiLCJpYXQiOjE2MTEwNjg4OTIsIm5iZiI6MTYxMTA2ODg5MiwiZXhwIjoxNjQyNjA0ODkyLCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.jCmFFvWFOigZAbbUUNTLe9Yzqvz_7Vb9DsshysYrJSnoWRIBpzgqUtJ73AU4BaBirHnK87xOGS6Ux9V24ue0WUoj4KtfyJ4TZ5eXy4xRNFOvM0SgeNwHd24zsycpH3VM6H7kHh5C47ISqyfDJgD6DuvL2qPj56W5vKmasj74mdiuVY1cLL8HmT6oFtacGj0qoBRSYLkWLF79I4mkkuAaMmEN5If0bINAs6dAdD9fsFZ-0HDS58_Z_psCUlQZXsG3alJHdufDNSmUl1CGNd00mJJiR50mXc5yIeLdBHNQAOlRu8MoCsQOzJO0S2Mbf-77gqlaTZaXPjLL-gBjJe5b2HubzGJW9Sbr-IHsl00ol3tg2PM3V-iBVmG9M25Is48h09glYCQsoFd673O-UhwjZrYR11DfII_7p94GdTQF8uLcMaaCi9q1SvLSZdGrFHQCx1ZOgbdVG7-vJGPH3boo93eeKdBM_p57LyISS-0s_oH9lxHP6GgyXExVUAf8BkHb_3qI1ebFzPk2AUYy3MhVACld5QFAKotoxE0roZ6o2ebMbkyW019taJvO6PRt2XkJyoiA-RUrhjg4lAcavRAnlZzYIsi0NZXxquHC_ZQqmVHr5k0SLzlBQB52HxP55I35S6CK-opiZV1fRR0i-6WiDhIX3-CWE0kOvf2ttHxuf0k'
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
            $bodyData= json_decode($data, true);
            if(!empty($data)) {
                $offerArray = $bodyData['data'];
            }
        }
        $data_array = [];
        $custom_offer_array = [];
        if(is_numeric($result_num)){
            $custom_offer_array = array_slice($offerArray['data'], 0, $result_num);
        }
        else{
            if (!empty($offerArray['data'])){
                $custom_offer_array = array_slice($offerArray['data'], 0, 10);
            }
        }
//        self::get_pagination($offerArray);
        return  $custom_offer_array;
    }
    public static function get_offer_from_api($custom_offer_array){

        foreach ($custom_offer_array as $row){
            $image_url = $row['image_url'];
            $image_type_check = @exif_imagetype($image_url);//Get image type + check if exists
            if (!$image_type_check)  {
                $image_url =  wp_get_attachment_url(get_option('default_image_for_bronken_link'));
            }
            $gallery = [];$gallery_item=[];
            foreach ($row['images'] as $key => $value):
                $gallery_item = [
                    'image' => $value['image_url'],
                    'alt' => 'صورة من الألبوم'
                ];
                array_push($gallery, $gallery_item);
            endforeach;
            $data_array[] = [
                'id' => $row['id'],
                'name' => $row['title'],
                'slug'=>$row['board'],
                'has_price'=>1,
                'link' => site_url() . '/?post_type=estate&p=' . $row['id'],
                'excerpt' => '',
                'image_srcset' => '',
                'image' => $image_url,
                'attributes' => [
                    [

                        'name' => '&gt; نوع العقار',
                        'slug' => '&gt; property-type',
                        'has_archive' => '&gt; 1',
                        'values' => '&gt'
                    ]
                ],
                'address' => $row['city_name'],
                'days_ago' => $row['created_at'],
                'is_featured' => $row['is_featured'],
                'offer_type' => [ ],
                'status' => $row['status'],
                'payment_status' => $row['status'],
                'attribute_classes' => $row['board'],
                'gallery' => $gallery,
                'date' => $row['created_at'],
                'price' => [
                    [
                        'price' => $row['price'],
                        'is_range' => 1,
                    ]
                ]
            ];
        }
        return  $data_array;
    }
    public static function get_pagination($offerArray){
        $args = array(
            'paged'          => 1,
            'items_per_page' => self::ITEMS_PER_PAGE,
        );
        $args2 = array(
            'base'               =>$offerArray['data'], // http://example.com/all_posts.php%_% : %_% is replaced by format (below).
            'format'             => '%#%', // ?page=%#% : %#% is replaced by the page number.
            'total'              => $offerArray['total'],
            'current'            => $offerArray['current_page'],
            'aria_current'       => 'page',
            'show_all'           => false,
            'prev_next'          => true,
            'prev_text'          => __( '&laquo; Previous' ),
            'next_text'          => __( 'Next &raquo;' ),
            'end_size'           => 1,
            'mid_size'           => 2,
            'type'               => 'plain',
            'add_args'           => array(), // Array of query args to add.
            'add_fragment'       => '',
            'before_page_number' => '',
            'after_page_number'  => '',
        );
        paginate_links($args);

        return  paginate_links($args);
    }
    public static function get_offer_post_from_api($post){
        $data_array = [];
        $image_url = '';
        foreach ($post as $row) {
            $image_url = ''. $row['image_url'];

            $data_array[] = (object) [
                'ID' => $row['id'],
                'post_author' => $row['map_lat'],
                'post_title' => $row['title'],
                'post_name' => $row['title'],
                'post_image' => $row['image_url'],
                'post_link' => get_site_url($row['id'],'/'.$row['title'],null),
                'post_content' => $row['description'],
                'menu_order' => $row['board'],
                'filter' => 'row',
                'post_type' => 'estate',
                'post_parent' => $row['price'],
                'post_status' => $row['status'],
                'ping_status' => 'closed',
                'payment_status' => $row['status'],
                'post_content_filtered' => $row['price'],
                'pinged' =>  $row['offer_type_ar'],
                'post_password' => $row['city_name'],
                'to_ping' =>  $row['neighborhood_name'],
                'guid' => $image_url,
                'post_mime_type' => '',
                'post_date' => $row['created_at'],
                'post_modified' => $row['updated_at'],
                'post_modified_gmt' => $row['updated_at'],
                'post_date_gmt' => $row['created_at'],
                'post_excerpt' => $row['type'],
                'comment_count' => $row['map_lat'],
                'comment_status' => $row['map_lng'],
                'map_lat' => $row['map_lat'],
                'map_lng' => $row['map_lng']
            ];
        }

//        json_encode($data_array);
      //  var_export( $data_array['post_mime_type'] . ' lihuy juncccccccccccc ');

        return  $data_array;
    }
    public  static function offer_type($type){
        $url = 'https://marketing-api.asaas.sa/api/get_offers_types';
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
            $bodyData= json_decode($data, true);
            if(!empty($data)) {
                $offerArray = $bodyData['types'];
            }
        }
        $returned_type = '';
        foreach ($offerArray as   $index => $key ){
            if ($index === $type){
                $returned_type = $key;
            }
        }
        return  $returned_type;
    }

    /**
     * @return array
     */
    private function get_estates($api = true) {

		$estates_factory = new Estate_Factory( [], true );
		$args            = array_merge( $this->args, $_GET );

		if ( ! empty( $this->args['agent_id'] ) && $this->args['agent_id'] !== 'any' ) {
			$estates_factory->set_user_id( $this->args['agent_id'] );
		}

		if ( isset( $this->args['agents'] ) && ! empty( $this->args['agents'] ) ) {
			$estates_factory->set_users( $this->args['agents'] );
		}

		$limit = ! empty( $this->args['estates_per_page'] ) ? intval( $this->args['estates_per_page'] ) : $this->settings['estates_per_page'];

		if ( ! empty( $args['current_page'] ) ) {
			$current_page = intval( $args['current_page'] );
			if ( empty( $args['listing_type'] ) || $args['listing_type'] == 'load_more' ) {
				$limit = $limit * $current_page;
			} elseif ( ! empty( $args['listing_type'] ) && $args['listing_type'] == 'pagination' ) {
				$estates_factory->set_page( $current_page );
			}
		}

		if ( isset( $_GET['property_ids'] ) && ! empty( $_GET['property_ids'] ) ) {
			$estates_factory->set_estates__in( explode( ',', $_GET['property_ids'] ) );
		}

		$estates_factory->set_limit( $limit );

		if ( ! empty( $this->args['featured'] ) && $this->args['featured'] == 'true' ) {
			$estates_factory->set_featured_only();
		}

		if ( ! empty( $this->args['listing_sort_by'] ) ) {
			$estates_factory->set_sort_by( $this->args['listing_sort_by'] );
		}

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$form_control = $attribute->get_search_form_control();
			if ( $form_control == Field::TEXT_RANGE || $form_control == Field::SELECT_RANGE ) {
				$keys = array( $attribute->get_slug() . '_from', $attribute->get_slug() . '_to' );
			} else {
				$keys = array( $attribute->get_slug() );
			}

			foreach ( $keys as $key ) {
				if ( empty( $args[ $key ] ) || $args[ $key ] == 'any' ) {
					continue;
				}

				if ( is_array( $args[ $key ] ) ) {
					$values = new Attribute_Values();
					foreach ( $args[ $key ] as $arg ) {
						$values->add( new Attribute_Value( $arg, $arg, '', $arg ) );
					}
				} else {
					$values = new Attribute_Values(
						array( new Attribute_Value( $args[ $key ], $args[ $key ], '', $args[ $key ] ) )
					);
				}

				$compare = '=';
				if ( strpos( $key, '_from' ) !== false ) {
					$compare = '>=';
				} elseif ( strpos( $key, '_to' ) !== false ) {
					$compare = '<=';
				}

				$estates_factory->add_filter( $attribute->get_estate_filter( $values, $compare ) );
			}
		}
		$original_array = $this->get_data_from_api();
		return array(
			'estates'      => $this->get_offer_from_api($original_array),
			'totalResults' => $estates_factory->get_found_number()
		);
	}

	/**
	 * @return array
	 */
	private function get_dependencies() {
		$dependencies = array();
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$dependencies[ $attribute->get_slug() ] = $attribute->get_property_type_dependency();
		}

		return $dependencies;
	}

	/**
	 * @param array $args
	 *
	 * @return array
	 */
	private function get_values( $args ) {
		$default_values = array();
		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$form_control = $attribute->get_search_form_control();
			if ( $form_control == Field::TEXT_RANGE || $form_control == Field::SELECT_RANGE ) {
				$keys = array( $attribute->get_slug() . '_from', $attribute->get_slug() . '_to' );
			} else {
				$keys = array( $attribute->get_slug() );
			}

			foreach ( $keys as $key ) {
				if ( empty( $args[ $key ] ) || $args[ $key ] == 'any' ) {
					continue;
				}

				if ( $attribute instanceof Text_Attribute ) {
					$filters = array();
					if ( is_array( $args[ $key ] ) ) {
						$values = $args[ $key ];
					} else {
						$values = array( $args[ $key ] );
					}

					foreach ( $values as $value ) {
						$term = Term::get_by_slug( $value, $attribute );

						if ( $term == false ) {
							$term = Term::get_by_name( $value, $attribute );
							if ( $term == false ) {
								continue;
							}
						}

						$filters[] = array(
							'name'  => $term->get_name(),
							'value' => $term->get_slug()
						);
					}


					$default_values[ $key ] = array(
						'id'     => $attribute->get_ID(),
						'values' => $filters
					);
				} else {
					$default_values[ $key ] = array(
						'id'     => $attribute->get_ID(),
						'values' => array(
							array(
								'name'  => $args[ $key ],
								'value' => $args[ $key ]
							)
						)
					);
				}
			}
		}

		return $default_values;
	}

	/**
	 * @return array
	 */
	public static function get_vc_settings() {
		$agents      = Users_Factory::get_agents( - 1, false, array(), false, false, false );
		$agents_list = array( esc_html__( 'Any', 'myhome-core' ) => 'any' );

		foreach ( $agents as $agent ) {
			$agents_list[ $agent->get_name() ] = $agent->get_ID();
		}

		$fields = array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Search form position', 'myhome-core' ),
				'param_name'  => 'search_form_position',
				'value'       => array(
					esc_html__( 'Top', 'myhome-core' )    => 'top',
					esc_html__( 'Right', 'myhome-core' )  => 'right',
					esc_html__( 'Left', 'myhome-core' )   => 'left',
					esc_html__( 'Hidden', 'myhome-core' ) => 'hide',
				),
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' )
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Search form', 'myhome-core' ),
				'param_name'  => 'search_form',
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top', 'right', 'left' )
				),
				'value'       => Search_Form::get_vc_search_form_list(),
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'save_always' => true
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Label', 'myhome-core' ),
				'param_name'  => 'label',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'search_form_position',
					'value'   => array( 'top' )
				)
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Featured only', 'myhome-core' ),
				'param_name'  => 'featured',
				'save_always' => true,
				'value'       => 'true',
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display number of results (e.g. 40 Found)', 'myhome-core' ),
				'param_name'  => 'show_results_number',
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of filters to show before the "Advanced" button', 'myhome-core' ),
				'param_name'  => 'search_form_advanced_number',
				'value'       => 3,
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Hide "Save this search" (if enabled)', 'myhome-core' ),
				'param_name'  => 'hide_save_search',
				'save_always' => true,
				'value'       => 'true',
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Default view', 'myhome-core' ),
				'description' => esc_html__( 'If you use "left" or "right" search form sidebar, max 2 columns are available', 'myhome-core' ),
				'param_name'  => 'listing_default_view',
				'value'       => array(
					esc_html__( 'Three columns', 'myhome-core' ) => 'colThree',
					esc_html__( 'Two columns', 'myhome-core' )   => 'colTwo',
					esc_html__( 'Row', 'myhome-core' )           => 'row',
				),
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' )
			),
			array(
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Sort by', 'myhome-core' ),
				'param_name'  => 'listing_sort_by',
				'value'       => array(
					esc_html__( 'Newest', 'myhome-core' )                     => Estate_Factory::ORDER_BY_NEWEST,
					esc_html__( 'Price (high to low)', 'myhome-core' )        => Estate_Factory::ORDER_BY_PRICE_HIGH_TO_LOW,
					esc_html__( 'Price (low to high)', 'myhome-core' )        => Estate_Factory::ORDER_BY_PRICE_LOW_TO_HIGH,
					esc_html__( 'Popular', 'myhome-core' )                    => Estate_Factory::ORDER_BY_POPULAR,
					esc_html__( 'Alphabetical order', 'myhome-core' )         => Estate_Factory::ORDER_BY_TITLE_ASC,
					esc_html__( 'Reverse alphabetical order', 'myhome-core' ) => Estate_Factory::ORDER_BY_TITLE_DESC,
				),
				'save_always' => true,
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Estates limit (number)', 'myhome-core' ),
				'param_name'  => 'estates_per_page',
				'value'       => '12',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' )
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Type', 'myhome-core' ),
				'param_name' => 'listing_type',
				'value'      => array(
					esc_html__( 'Progressive loading', 'myhome-core' ) => 'load_more',
					esc_html__( 'Pagination', 'myhome-core' )          => 'pagination'
				),
				'srd'        => 'load_more',
				'group'      => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Lazy loading', 'myhome-core' ),
				'param_name'  => 'lazy_loading',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'listing_type',
					'value'   => array( 'load_more' )
				)
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of times estates will be loaded after clicking “Load More”', 'myhome-core' ),
				'param_name'  => 'lazy_loading_limit',
				'value'       => '3',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'listing_type',
					'value'   => array( 'load_more' )
				)
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( '"Load More" button label', 'myhome-core' ),
				'param_name'  => 'load_more_button',
				'value'       => esc_html__( 'Load more', 'myhome-core' ),
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'dependency'  => array(
					'element' => 'listing_type',
					'value'   => array( 'load_more' )
				)
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display "advanced" button', 'myhome-core' ),
				'param_name'  => 'show_advanced',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display "clear" button', 'myhome-core' ),
				'param_name'  => 'show_clear',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display "sort by"', 'myhome-core' ),
				'param_name'  => 'show_sort_by',
				'value'       => 'true',
				'std'         => 'true',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display "view types"', 'myhome-core' ),
				'param_name'  => 'show_view_types',
				'value'       => 'true',
				'std'         => 'true',
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'save_always' => true,
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display properties at start', 'myhome-core' ),
				'param_name'  => 'show_initial_results',
				'save_always' => true,
				'group'       => esc_html__( 'General', 'myhome-core' ),
				'value'       => 'true',
				'std'         => 'true',
			),
			array(
				'group'       => esc_html__( 'Default values', 'myhome-core' ),
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'User', 'myhome-core' ),
				'param_name'  => 'agent_id',
				'value'       => $agents_list,
				'save_always' => true,
				'default'     => 'any'
			),
			array(
				'group'       => esc_html__( 'Show Sort by', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Newest', 'myhome-core' ),
				'param_name'  => 'show_sort_by_newest',
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			),
			array(
				'group'       => esc_html__( 'Show Sort by', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Price (high to low)', 'myhome-core' ),
				'param_name'  => 'show_sort_by_price_high_to_low',
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			),
			array(
				'group'       => esc_html__( 'Show Sort by', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Price (low to high)', 'myhome-core' ),
				'param_name'  => 'show_sort_by_price_low_to_high',
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			),
			array(
				'group'       => esc_html__( 'Show Sort by', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Popular', 'myhome-core' ),
				'param_name'  => 'show_sort_by_popular',
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			),
			array(
				'group'       => esc_html__( 'Show Sort by', 'myhome-core' ),
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Alphabetical', 'myhome-core' ),
				'param_name'  => 'show_sort_by_alphabetically',
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'false',
			)
		);

		foreach ( Attribute_Factory::get_search() as $attribute ) {
			$fields[] = array(
				'type'        => 'checkbox',
				'heading'     => sprintf( esc_html__( 'Show % s', 'myhome-core' ), $attribute->get_name() ),
				'param_name'  => $attribute->get_slug() . '_show',
				'group'       => esc_html__( 'Show filters', 'myhome-core' ),
				'save_always' => true,
				'value'       => 'true',
				'std'         => 'true',
			);
			$fields   = $attribute->get_vc_control( $fields );
		}

		return $fields;
	}

}
