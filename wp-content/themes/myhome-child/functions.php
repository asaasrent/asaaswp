<?php
/* @var \MyHomeCore\Estates\Estate_Writer $myhome_estate */
global $myhome_estate;
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
    wp_enqueue_style( 'custom-style-rtl', get_stylesheet_directory_uri() . '/custom-style-rtl.css', $dependency_child, My_Home_Theme()->version );

}
function myhome_child_enqueue_script(){
    if (is_page(588) || is_page(3435)):
    wp_enqueue_script( 'map', get_template_directory_uri() . '/assets/js/map.js', array(), My_Home_Theme()->version, true );
    endif;
}
add_action( 'wp_enqueue_scripts', 'myhome_child_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'myhome_child_enqueue_script' );

function myhome_lang_setup() {
	load_child_theme_textdomain( 'myhome', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'myhome_lang_setup' );

add_action("wpcf7_before_send_mail", "wpcf7_do_something_else");

function wpcf7_do_something_else($WPCF7_Submission, $myhome_estate) {
    $submission = WPCF7_Submission::get_instance();
    $posted_data = $submission->get_posted_data();
    $contact_form = WPCF7_ContactForm::get_current();
    $contact_form_id = $contact_form -> id;
    $arguments = array(
        'headers' => array(
            'Accept'=>'application/json',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImJiMDQzYTFjNWEyNDZlZmNlMjc2YmY5MTEwNzIyZTIwMjY3OWM2NjA0ZDFmZTYwYWVjZGZmNmZlMzc3ZDZmNjA5MzY3YjY2MjIyZjcyZGZkIn0.eyJhdWQiOiI1IiwianRpIjoiYmIwNDNhMWM1YTI0NmVmY2UyNzZiZjkxMTA3MjJlMjAyNjc5YzY2MDRkMWZlNjBhZWNkZmY2ZmUzNzdkNmY2MDkzNjdiNjYyMjJmNzJkZmQiLCJpYXQiOjE2MTEwNjg4OTIsIm5iZiI6MTYxMTA2ODg5MiwiZXhwIjoxNjQyNjA0ODkyLCJzdWIiOiIzIiwic2NvcGVzIjpbXX0.jCmFFvWFOigZAbbUUNTLe9Yzqvz_7Vb9DsshysYrJSnoWRIBpzgqUtJ73AU4BaBirHnK87xOGS6Ux9V24ue0WUoj4KtfyJ4TZ5eXy4xRNFOvM0SgeNwHd24zsycpH3VM6H7kHh5C47ISqyfDJgD6DuvL2qPj56W5vKmasj74mdiuVY1cLL8HmT6oFtacGj0qoBRSYLkWLF79I4mkkuAaMmEN5If0bINAs6dAdD9fsFZ-0HDS58_Z_psCUlQZXsG3alJHdufDNSmUl1CGNd00mJJiR50mXc5yIeLdBHNQAOlRu8MoCsQOzJO0S2Mbf-77gqlaTZaXPjLL-gBjJe5b2HubzGJW9Sbr-IHsl00ol3tg2PM3V-iBVmG9M25Is48h09glYCQsoFd673O-UhwjZrYR11DfII_7p94GdTQF8uLcMaaCi9q1SvLSZdGrFHQCx1ZOgbdVG7-vJGPH3boo93eeKdBM_p57LyISS-0s_oH9lxHP6GgyXExVUAf8BkHb_3qI1ebFzPk2AUYy3MhVACld5QFAKotoxE0roZ6o2ebMbkyW019taJvO6PRt2XkJyoiA-RUrhjg4lAcavRAnlZzYIsi0NZXxquHC_ZQqmVHr5k0SLzlBQB52HxP55I35S6CK-opiZV1fRR0i-6WiDhIX3-CWE0kOvf2ttHxuf0k',
            'office-number' => '9665704'
        ),
        'body' => $posted_data
    );
    echo $contact_form_id;

    if ($contact_form_id == 3573) {
        echo 'testtttt';

        if (!empty($myhome_estate->get_data_api()['id'])) {
            $id =  $myhome_estate->get_data_api()['id'];
            $stuff_request = 'https://marketing-api.asaas.sa/api/request_offer/'. $id;
            $stuff_response = wp_remote_get( $stuff_request,$arguments);
            echo $stuff_request;
        }
    }
    else{
        $stuff_request = 'https://marketing-api.asaas.sa/api/save_request';
        $stuff_response = wp_remote_post( $stuff_request,$arguments);

    }

    if(is_wp_error($stuff_response)){
        echo 'حاول مرة أخرى';
    }
    else{
        $body = wp_remote_retrieve_body( $stuff_response );
    }
}
$neighborhood_array = [];

add_filter( 'cf7sg_dynamic_dropdown_custom_options','type_dynamic_options',10,3);
function type_dynamic_options($options, $name , $cf7_key = 3425){
    if($name === 'type'){
        $data = get_types();
    }
    else if ($name === 'city_id' ){
        $data = get_city();
    }
    else if($name === 'request_type' ){
        $data = get_requests_types();
    }
    else{
        $data = [];
    }
    $options = '';
    foreach($data as $label => $value){
        if ($label === 18){
            $options .= '<option selected value="'.$label.'">'.$value.'</option>';
        }
        else{
            $options .= '<option value="'.$label.'">'.$value.'</option>';
        }
    }
    return $options;
}

function get_city() {
    $stuff_request = 'https://marketing-api.asaas.sa/api/get_cities';
    $arguments = array(
        'headers' => array(
            'Accept'=>'application/json',
        )
    );
    $stuff_response = wp_remote_get( $stuff_request,$arguments);

    if(is_wp_error($stuff_response)){
        echo 'حاول مرة أخرى';
    }
    else{
        $body = wp_remote_retrieve_body( $stuff_response );
        $data = json_decode(json_encode($body), true);
        $bodyData= json_decode($data, true);
    }
    return $bodyData['data'];
}
function get_requests_types() {
    $stuff_request = 'https://marketing-api.asaas.sa/api/get_requests_types';
    $arguments = array(
        'headers' => array(
            'Accept'=>'application/json',
        )
    );
    $stuff_response = wp_remote_get( $stuff_request,$arguments);

    if(is_wp_error($stuff_response)){
        echo 'حاول مرة أخرى';
    }
    else{
        $body = wp_remote_retrieve_body( $stuff_response );
        $data = json_decode(json_encode($body), true);
        $bodyData= json_decode($data, true);
    }
    return $bodyData['types'];
}
function get_types() {
    $stuff_request = 'https://marketing-api.asaas.sa/api/getTypes';
    $arguments = array(
        'headers' => array(
            'Accept'=>'application/json',
        )
    );
    $stuff_response = wp_remote_get( $stuff_request,$arguments);

    if(is_wp_error($stuff_response)){
        echo 'حاول مرة أخرى';
    }
    else{
        $body = wp_remote_retrieve_body( $stuff_response );
        $data = json_decode(json_encode($body), true);
        $bodyData= json_decode($data, true);
    }
    return $bodyData['types'];
}
function get_neighborhood($city_id) {
    $stuff_request = 'https://marketing-api.asaas.sa/api/get_neighborhoods?city_id='.$city_id;
    $arguments = array(
        'headers' => array(
            'Accept'=>'application/json',
        )
    );
    $stuff_response = wp_remote_get( $stuff_request,$arguments);
    if(is_wp_error($stuff_response)){
        echo 'حاول مرة أخرى';
    }
    else{
        $body = wp_remote_retrieve_body( $stuff_response );
        $data = json_decode(json_encode($body), true);
        $bodyData= json_decode($data, true);
    }
    return $bodyData['data'];
}
add_action( 'wp_footer', 'my_footer_scripts' );
add_action( 'wp_headers', 'my_header_scripts' );

function my_header_scripts(){
    $phpArray = \MyHomeCore\Components\Listing\Listing_Settings::get_data_from_api(300);
    ?>
    <script type="text/javascript">
        array_offer= <?php echo json_encode($phpArray) ?>;
        let selectedCity;
    </script>
    <?php
}

function my_footer_scripts(){
    if (is_page(588) || is_page(3435)):
    ?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAH8nToB0uXjiezLX0LX6thrjMJSn_7MkI&libraries=places&signed_in=true"></script>
    <?php  endif; ?>
        <script type="text/javascript">
        jQuery( document ).ready(function() {
            get_neighborhoods(18);
            jQuery("[name='city_id']").on('change',function displayTextField() {
                $("[name='neighborhood_id']").empty();
                selectedCity = $(this).children("option:selected").val();
                get_neighborhoods(selectedCity);
            });
            function get_neighborhoods(selectedCity) {
                let array = $.ajax({
                    type: "GET",
                    header: {"Access-Control-Allow-Origin": "*"},
                    url: 'https://marketing-api.asaas.sa/api/get_neighborhoods?city_id=' + selectedCity,
                }).done( function (response) {
                    let neighborhoods_data = response.data;
                    for (let index in neighborhoods_data) {
                        let options_item = '<option value="'+index+'">'+neighborhoods_data[index]+'</option>';
                        jQuery("[name='neighborhood_id']").append(options_item);
                    }
                })
            }

        })

    </script>
    <?php
}
?>

