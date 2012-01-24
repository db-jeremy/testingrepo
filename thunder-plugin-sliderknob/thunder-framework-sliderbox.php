<?php
/*
Plugin Name: thunder-framework-sliderbox
Plugin URI: http://path/to/page
Description: Produces content box with slider UI element invokable with a shortcode
Version: 0.1a
Author: Jeremy Hough/Digital Brands
Author URI: http://path/to/page
License: GPL2
*/

/*  Copyright 2012 Digital Brands  (email : email.goes@here)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
/**
 * Slider Knob
 * 
 * Provides a content box which contains content which is panned left and right through a viewport by sliding a knob horizontally
 * 
 * &nbsp;
 *
 * Usage:
 *
 * &nbsp; [tdr_framework_sliderknob notched="{true|false}"][slider]Content pane for first entry[/slider][slider]Content pane for second entry[/slider][/tdr_framework_sliderknob]
 * @author Jeremy Hough <jeremy@digitalbrands.com>
 * @copyright 2012 Digital Brands Inc.
 * @license GPL2
 * @package  thunder-plugin-sliderknob
 * @version 0.0.1 alpha
 */
/**
 * Registers all necessary scripts used for sliderknob so WordPress knows about them. Triggered on WordPress's "wp_enqueue_scripts" event.
 * @package  thunder-plugin-sliderknob
 */
function sliderbox_scripts() {
   // register your script location, dependencies and version
   wp_register_script( 'sliderbox',
       plugins_url( 'js/sliderbox.js', __FILE__ ),
       array('jquery'),
       '1.0' );
   wp_register_script( 'sliderbox_notched',
       plugins_url( 'js/sliderbox-notched.js', __FILE__ ),
       array('jquery'),
       '1.0' );
   wp_register_script( 'ui_widget', plugins_url( 'js/jquery.ui.widget.js', __FILE__ ),
	   array('jquery', 'jquery-ui-core'),
	   '1.0' );
   wp_register_script( 'ui_mouse', plugins_url( 'js/jquery.ui.mouse.js', __FILE__ ),
	   array('jquery', 'jquery-ui-core'),
	   '1.0' );
   wp_register_script( 'ui_slider', plugins_url( 'js/jquery.ui.slider.js', __FILE__ ),
	   array('jquery', 'jquery-ui-core'),
	   '1.0' );
   wp_register_script( 'scrollto', plugins_url( 'js/jquery.scrollTo-min.js', __FILE__ ),
	   array('jquery', 'jquery-ui-core'),
	   '1.0' );
	   // enqueue the scripts
   wp_enqueue_script('ui_widget');
   wp_enqueue_script('ui_mouse');
   wp_enqueue_script('scrollto');
   wp_enqueue_script('ui_slider');
}
add_action('wp_enqueue_scripts', 'sliderbox_scripts');

/**
 * Conditionally loads all necessary scripts used for sliderknob based on the type of sliderknob used (notched vs unnotched) and if there are any sliders present or not. Triggered on WordPress's "wp_footer" event.
 * @var boolean $add_slider Global variable stating whether to load slider scripts
 * @var array $slider_type Global variable array listing which types of sliders are present on the page, if any
 * @package  thunder-plugin-sliderknob
 */
function sliderloader() {
	global $add_slider;
	global $slider_type;
	if ( ! $add_slider ) {
		return;
	}
	if	( in_array( "notched", $slider_type ) ) {
		wp_print_scripts('sliderbox_notched');
	}
	if ( in_array( "normal", $slider_type ) ) {
		wp_print_scripts('sliderbox');
	}
}
add_action('wp_footer', 'sliderloader');

/**
 * Defines the behavior of the "tdr_framework_sliderknob" shortcode. Triggered by regular WordPress add_shortcode() function.
 * @param array $atts Array of shortcode attributes
 * @param string $content The content of the shortcode
 * @var boolean $add_slider Global variable stating whether to load slider scripts
 * @var array $slider_type Global variable array listing which types of sliders are present on the page, if any
 * @var int $GLOBALS['slider_count'] A counter for the number of sliders on the page
 * @var array $GLOBALS['sliders'] An array containing the content of individual slider content-panes
 * @var array $panes Contains the content panes for each sliderknob instance
 * @package  thunder-plugin-sliderknob
 * @return string Contains all content of the sliderknob instance, including individual content-panes and the sliderknob itself
 */
function sliderknob_func( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'notched' => 'false',
		'bar' => 'something else',
	), $atts ) );
	if($notched == 'true') {
		global $add_slider;
		global $slider_type;
		$add_slider = true;
		$slider_type[] = "notched";
		$flag = "";
	}
	else {
		global $add_slider;
		global $slider_type;
		$add_slider = true;
		$slider_type[] = "normal";
		$flag = "un";
	}
	if( !isset( $GLOBALS['sliders'] ) ) {
		$GLOBALS['sliders'] = array();
		$GLOBALS['sliders'][0] = array();
	}
	else {
		$GLOBALS['sliders'][(count($GLOBALS['sliders']))] = array();
	}
	$i = (count($GLOBALS['sliders']))-1;
	do_shortcode( $content );
	//TODO: have proper id for each instance of slider box
	if( is_array( $GLOBALS['sliders'] ) ){
		// Foreach slider
		foreach( $GLOBALS['sliders'][$i] as $slider ){
			// The HTML for the pane
			$panes[] = "<td class='slider-pane' id='slider-".($i+1)."-pane" . $slider['number'] . "'>" . $slider['content'] . '</td>';
		}
		$slider_ui_widget = "<div class='tdr-ui-slider-".$flag."notched' id='tdr-ui-slider-".($i+1)."'></div>";
		// The final HTML including panes and ui slider
		// Panes
		$return = "<div class='sliderbox-container-".$flag."notched' id='sliderbox-container-".($i+1)."'><table id='slidertable'><tr>";
		$return .= implode( "\n", $panes );
		$return .= "</tr></table></div>";
		$return .= $slider_ui_widget;
	}

	return '<div class=\'sliderbox\' id=\'sliderbox-'.($i+1).'\'>' . $return . '</div>';
}
add_shortcode( 'tdr_framework_sliderknob', 'sliderknob_func' );
/**
 * Defines the behavior of the "slider" shortcode. Triggered by regular WordPress add_shortcode() function.
 * @param array $atts Array of shortcode attributes
 * @param string $content The content of the shortcode
 * @var int x Holds the slider count
 * @var int $GLOBALS['slider_count'] A counter for the number of sliders on the page
 * @var array $GLOBALS['sliders'] An array containing the content of individual slider content-panes. Values are pushed to the array each time this function is triggered.
 * @package  thunder-plugin-sliderknob
 */
function slider_func( $atts, $content = null ) {
	// Find the which sliderknob instance this is
	$sub = count($GLOBALS['sliders'])-1;
	// Get the current slider count for current sliderknob subarray
	$x = count($GLOBALS['sliders'][$sub])-1;
	
	// Add this slider to the 'sliders' array
	$GLOBALS['sliders'][$sub][$x] = array(
		'number' => $x+2,
		'content' =>  $content
		);
}
add_shortcode( 'slider', 'slider_func' );
wp_register_style( 'tdr_sliderbox_style', plugins_url('css/style.css', __FILE__ ) );
wp_register_style( 'ui_style', plugins_url('css/jquery-ui.css', __FILE__ ) );
wp_enqueue_style( 'tdr_sliderbox_style' );
wp_enqueue_style( 'ui_style' );

?>