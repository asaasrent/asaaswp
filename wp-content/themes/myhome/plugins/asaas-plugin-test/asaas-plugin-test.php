<?php

/*
Plugin Name: Asaas plugin
Plugin URI:
Description: Example Plugin for youtube
Author: Asmaa
Author URI: http://youtube.com/microtechtutorials
Version: 0.1
 */
add_action('admin_menu','addMenu');

function addMenu(){
    add_menu_page('Asaas plugin', 'Asaas plugin', 4, 'Asaas plugin',
        'AsaaspluginMenu');
}
