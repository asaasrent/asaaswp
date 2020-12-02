<?php
if (function_exists('essb_advancedopts_settings_group')) {
	essb_advancedopts_settings_group('essb_options');
}

essb_advancedopts_section_open('ao-small-values');

essb5_draw_heading( esc_html__('Position Setup', 'essb'), '6');
essb5_draw_switch_option('activate_automatic_position', esc_html__('Activate Adaptive Position Styles', 'essb'), esc_html__('Adaptive position styles will eliminate dedicated style settings for a position. Instead of this, you will have the most used style for the location applied automatically. In case you do not have a specific need, that may be the time saver for setting the plugin.', 'essb'));


essb5_draw_heading( esc_html__('Mobile Setup', 'essb'), '6');

essb5_draw_switch_option('activate_automatic_mobile_content', esc_html__('Activate Automatic Responsive Design of Content Share Buttons', 'essb'), esc_html__('Automatic responsive design will change the button style of the content share buttons generated by the automatic display methods. It will hide share counters, stretch width, hide network texts and so to ensure that buttons will fit inside content.', 'essb'));

essb_advancedopts_section_close();