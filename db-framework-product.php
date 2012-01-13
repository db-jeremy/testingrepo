<?php
/*
Plugin Name: db-framework-product
Plugin URI: http://path/to/page
Description: Facilitates 'product' listings and characteristics for Verticals
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
 * TODO: 
 * Enables the excerpt field.
 */
 /*add_action(‘init’, ‘dc_custom_init’);

    function dc_custom_init() {
        add_post_type_support( ‘db_product’, ‘excerpt’ );
    }
*/

function DB_Framework_Products_Init() {
	$product = new DB_FrameWork_Products();
	$product->init();
}

add_action( 'load-post.php', 'DB_Framework_Products_Init' );
add_filter( 'the_content', 'DB_Framework_Products_Filter' );


function DB_Framework_Products_Filter( $content ) {
	$product = new DB_FrameWork_Products();
	return $product->formatting( $content );
}


/*
* DB Product Class
*/
class DB_FrameWork_Products {
	//Setup
	function init() {
		add_action( 'add_meta_boxes', array( &$this, 'meta_fields' ) );
		//On save hook, pass data to process method at default priority with 2 arguments
		add_action( 'save_post', array( &$this, 'process' ), 10, 2 );
	}
	function meta_fields() {
	/*
		$meta_array = array (
			// unique ID, title, callback function, post-type, context (location), priority, filter-type
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
			array( 'db-framework-products-aff-url', esc_html__( 'Affiliate URL', 'enter url here' ), array( &$this, 'meta_display' ), 'db_product', 'side', 'default', 'url' ),
		);
		*/
		add_meta_box(
				'db-framework-products-aff-url',				// Unique ID
				esc_html__( 'Affiliate URL', 'enter url here' ),		// Title
				array( &$this, 'meta_display' ),				// Callback function
				'db_product',										// Admin page (or post type)
				'side',										// Context
				'default'									// Priority
		);
	}
	function meta_display ( $object, $box ) {
		wp_nonce_field( basename( __FILE__ ), 'db_framework_products_aff_url_nonce' );
		echo <<<html
			<p>
				<label for="db-framework-products-aff-url">
html;
		echo _e( "Enter affiliate URL for product.", 'example' );
		echo <<<html
		</label>
				<br />
				<input class="widefat" type="text" name="db-framework-products-aff-url" id="db-framework-products-aff-url" value="
html;
		echo esc_attr( get_post_meta( $object->ID, 'db_framework_products_aff_url', true ) );
		echo <<<html
" size="30" />
			</p>
html;
	}
	function process ( $post_id, $post ) {
		//Ensure has purpose
		$this->nonce_check();
		$post_type = $this->check_post_type( $post );
		$this->perm_check( $post_type, $post_id );
		//TODO: perform any filtering specific to meta-type
		//Example code:
		//$this->filter_meta( $post, $field_type );
		/*
		$new_meta_value = ( isset( $_POST['smashing-post-class'] ) ? sanitize_html_class( $_POST['smashing-post-class'] ) : '' );
		*/
		$new_meta_value = ( isset( $_POST['db-framework-products-aff-url'] ) ? $_POST['db-framework-products-aff-url'] : '' );
		$meta_key = 'db_framework_products_aff_url';
		$meta_value = get_post_meta( $post_id, $meta_key, true );
		$this->meta_check( $meta_key, $meta_value, $new_meta_value, $post_id );
	}
	function meta_check( $meta_key, $meta_value, $new_meta_value, $post_id ) {
		/* If a new meta value was added and there was no previous value, add it. */
		if ( $new_meta_value && '' == $meta_value )
			$this->add_key( $post_id, $meta_key, $new_meta_value );

		/* If the new meta value does not match the old value, update it. */
		elseif ( $new_meta_value && $new_meta_value != $meta_value )
			$this->update_key( $post_id, $meta_key, $new_meta_value );

		/* If there is no new meta value but an old value exists, delete it. */
		elseif ( '' == $new_meta_value && $meta_value )
			$this->rm_key( $post_id, $meta_key, $meta_value );
	}
	function perm_check( $post_type, $post_id ) {
		//make die on failure
		if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;
	}
	function check_post_type( $post ) {
		return get_post_type_object( $post->post_type );
	}
	function nonce_check () {
		//make die on failure
		if ( !isset( $_POST['db_framework_products_aff_url_nonce'] ) || !wp_verify_nonce( $_POST['db_framework_products_aff_url_nonce'], basename( __FILE__ ) ) )
			return $post_id;
	}
	//Edits data for meta field if present, Adds if new
	function update_key ( $post_id, $meta_key, $new_meta_value ) {
		update_post_meta( $post_id, $meta_key, $new_meta_value );
	}
	//Adds data for meta field-- stacks if present
	function add_key ( $post_id, $meta_key, $new_meta_value ) {
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	}
	//Removes data for meta field
	function rm_key ( $post_id, $meta_key, $meta_value ) {
		delete_post_meta( $post_id, $meta_key, $meta_value );
	}
	//Gets data from meta field
	function get_key ( $post_id, $meta_key ) {
		return get_post_meta( $post_id, $meta_key, true );
	//implement later
	}
	function formatting ( $content ) {
	global $wp_query;
	return $content.($this->get_key($wp_query->post->ID, 'db_framework_products_aff_url'));
	}
}

add_action( 'init', 'create_post_type' );
function create_post_type() {
	register_post_type( 'db_product',
		array(
			'labels' => array(
				'name' => __( 'Products' ),
				'singular_name' => __( 'Product' )
			),
		'public' => true,
		'has_archive' => true,
		'supports' => array(
				'title',
				'editor',
				'custom-fields',
				'excerpt',
				'thumbnail',
				'page-attributes'
				)
		)
	);
}

?>