<?php

function myhome_child_enqueue_styles() {
	$options           = get_option( 'myhome_redux' );
	$dependency_parent = array();
	$dependency_child  = array( 'myhome-style' );
	if ( ! is_rtl() ) {
		$parent_style = '/style.min.css';
	} else {
		$parent_style = '/style-rtl.min.css';
	}
	if ( ! isset( $options['mh-performance_css'] ) || empty( $options['mh-performance_css'] ) ) {
		$dependency_parent[] = 'normalize';
		$dependency_child[]  = 'normalize';
		if ( ! is_rtl() ) {
			$parent_style = '/style.css';
		} else {
			$parent_style = '/style-rtl.css';
		}
	}

	wp_enqueue_style( 'myhome-style', get_template_directory_uri() . $parent_style, $dependency_parent, My_Home_Theme()->version );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', $dependency_child, My_Home_Theme()->version );
    wp_enqueue_style( 'custom-style-rtl', get_stylesheet_directory_uri() . '/custom-style-rtl.css', $dependency_child, My_Home_Theme()->version );

}

add_action( 'wp_enqueue_scripts', 'myhome_child_enqueue_styles' );

function myhome_lang_setup() {
	load_child_theme_textdomain( 'myhome', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'myhome_lang_setup' );

add_action("wpcf7_before_send_mail", "wpcf7_do_something_else");

function wpcf7_do_something_else($WPCF7_Submission) {
    $submission = WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();

    $stuff_request = 'https://marketing-api.asaas.sa/api/save_request';
    $arguments = array(
        'headers' => array(
            'Accept'=>'application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE4OWQwOTQxNjYwMDE5NjU5NDhlNzY2OGI0MzQ5MWI4ZmEyMzkwZWE4NDBiMTNlMjE2NGNiMTQ0ZjAzZWRhODAzMThmMjYwODhhODZjY2JmIn0.eyJhdWQiOiIzIiwianRpIjoiYTg5ZDA5NDE2NjAwMTk2NTk0OGU3NjY4YjQzNDkxYjhmYTIzOTBlYTg0MGIxM2UyMTY0Y2IxNDRmMDNlZGE4MDMxOGYyNjA4OGE4NmNjYmYiLCJpYXQiOjE2MTAwMTE3MDcsIm5iZiI6MTYxMDAxMTcwNywiZXhwIjoxNjQxNTQ3NzA3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.mNiL0JfU1GPyBw46vo3MtsbnjLSiS2AumIzLhBImUZdJ88FRFFaGB-IanVvPrApWizBR-zb4SbiUW7TFGN90UUj62XHOF1VbQxB4c-qKtwsycwFutVYKVz4ix41HlfMNrJEtgcd-Mv_h8ayYJiWri9vexfjQPqw_diLGF-tfNGg2ik1_BOI25bvRvXWNaTKbhWBdsKy4OJVCuvwzbH7qsMLbaaOoCnETGgWXfwNZljh5D5EsRBAyhNnvtAxhiziMIM7x1JI5Wz9cjBMpNdRKg_9yPg2GDwlKGNk1U-EF31u3pEqanuy0DKo1mjWEPlKhyMVUrCD0ViGTD0L_c2rGILMzbhO4QR7BZDr_N4hEO9b4q5VswCGBwHYcC8QB7whUcehFezfjKOh1y-xxA8S0AjXCeJVDIhznVdqgIwafqHtBTLOrVgpCODr0UQHRK1PouInlW1ssEGbLRL-FkGgFhDNV1z6HLuQwOJESQtifJ7RKpQCKRS8kQv3B7468Ec_9UMJ9i1VlYbTQb8wsvPnWu8GKb-WkbxYED3qAMMT4FKRb8l3jMpJ90r0h6vlwzE54Ngf5qYzUiQFwnt7f2XyN-HTCut332e7k_oAFD3cHiy61uL_lwGA2pxeioe-mhCOn9iPmq33D2Nj3HXJelUa8uNxpOW0T9MWbRDK0wx_zmDQ'
        ),
        'body' => $posted_data
    );
    $stuff_response = wp_remote_post( $stuff_request,$arguments);

    if(is_wp_error($stuff_response)){
        echo 'Error !!!!!!!!!!! ';
    }
    else{
        $body = wp_remote_retrieve_body( $stuff_response );
    }

}



//
?>