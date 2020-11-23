<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package monfri
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function monfri_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'monfri_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function monfri_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'monfri_pingback_header' );
add_filter('wpcf7_autop_or_not', '__return_false');

pll_register_string('Описание', 'desc');
pll_register_string('Количество', 'kol');
pll_register_string('Акция', 'sale');
pll_register_string('Топ продаж', 'top');
pll_register_string('Популярное', 'popular');
pll_register_string('Новинка', 'new');
pll_register_string('Смотреть коллекцию', 'collection');
pll_register_string('Телефон 1', 'phone-1');
pll_register_string('Телефон 2', 'phone-2');
pll_register_string('Время работы', 'time-work');
pll_register_string('Адрес', 'address');
pll_register_string('E-mail 1', 'email-1');
pll_register_string('E-mail 2', 'email-2');
pll_register_string('E-mail 3', 'email-3');


add_theme_support( 'post-thumbnails' );
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'products', 585, 825, array( 'center', 'top' ) );
	add_image_size( 'min', 210, 300, array( 'center', 'top' ) );
	add_image_size( 'blog', 255, 155, array( 'center', 'top' ) );
	add_image_size( 'minicart', 240, 240, array( 'center', 'top' ) );
	add_image_size( 'cart', 300, 390, array( 'center', 'top' ) );
	add_image_size( 'popular', 675, 750, array( 'center', 'top' ) );
	add_image_size( 'app', 486, 686, array( 'center', 'top' ) );
	add_image_size( 'product-big', 600, 700, array( 'center', 'top' ) );
}

add_filter( 'excerpt_length', function(){
	return 23;
} );
add_filter('excerpt_more', function($more) {
	return '...';
});

remove_filter( 'the_excerpt', 'wpautop' );

add_theme_support( 'post-formats', array(
	'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
));

function woocommerce_archive_gallery() {

global $product;
global $post;
$post_ids = $product->get_id();

$attachment_ids = $product->get_gallery_image_ids();
echo get_the_post_thumbnail( $post->ID, 'products', array('loading'=>'lazy', 'class'=>'', 'alt' => esc_html ( get_the_title() )) );

}

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 ); 
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_archive_gallery', 8 );


// Образка изображения в карточке товара
function bigSingleImg()
{
	return 'product-big';
}
add_filter('woocommerce_gallery_image_size', 'bigSingleImg');

// Образка изображения в карточке товара в галерее
function minSingleImg()
{
	return 'min';
}
add_filter('woocommerce_gallery_thumbnail_size', 'minSingleImg');


/*if( 'Disable srcset/sizes' ){

	add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );

	add_filter( 'wp_calculate_image_sizes', '__return_false',  99 );

	remove_filter( 'the_content', 'wp_make_content_images_responsive' );

	add_filter( 'wp_img_tag_add_srcset_and_sizes_attr', '__return_false' );
}

add_filter( 'wp_get_attachment_image_attributes', 'unset_attach_srcset_attr', 99 );


function unset_attach_srcset_attr( $attr ){

	foreach( array('sizes','srcset') as $key ){
		if( isset($attr[ $key ]) )
			unset($attr[ $key ]);
	}

	return $attr;
}*/

function abChangeProductsTitle() {
	echo '<div class="title">' . get_the_title() . '</div>';
}

remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'abChangeProductsTitle', 10 );


remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');

register_nav_menus( array(
	'main-menu' => esc_html__( 'Главное меню', 'monfri' ),
	'footer-menu' => esc_html__( 'Футер меню', 'monfri' ),
	'footer-menu-two' => esc_html__( 'Футер меню 1', 'monfri' ),
	'footer-menu-tree' => esc_html__( 'Футер меню 2', 'monfri' ),
	'copy-menu' => esc_html__( 'Копирайт меню', 'monfri' ),
) );

 
function bbloomer_update_qty() { 
	if ( is_product()) { 
		?> 
		<script type="text/javascript"> 
		 jQuery('input.qty').replaceWith('<select name="quantity">' +
		 '<option value="1">1</option>' +
		 '<option value="2">2</option>' +
		 '<option value="3">3</option>' +
		 '<option value="4">4</option>' +
		 '<option value="5">5</option>' +
		 '</select>'); 
		</script> 
		<?php 
	} 
}
add_action( 'wp_footer', 'bbloomer_update_qty' );

add_filter( 'avatar_defaults', 'add_default_avatar_option' );
function add_default_avatar_option( $avatars ){
	$url = get_stylesheet_directory_uri() . '/static/img/avatar.jpg';
	$avatars[ $url ] = 'Аватар сайта';
	return $avatars;
}

add_action( 'woocommerce_widget_shopping_cart_buttons', function(){

	remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );
	remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );

	add_action( 'woocommerce_widget_shopping_cart_buttons', 'custom_widget_shopping_cart_button_view_cart', 10 );
	add_action( 'woocommerce_widget_shopping_cart_buttons', 'custom_widget_shopping_cart_proceed_to_checkout', 20 );
}, 1 );


function custom_widget_shopping_cart_button_view_cart() {
	$original_link = wc_get_cart_url();
	$my_lang = pll_current_language(); if ( $my_lang == 'ru' ){
		 $custom_link = home_url( '/cart/' );
	 }elseif($my_lang == 'eng'){
		 $custom_link = home_url( '/eng/cart-en/' );
	 }
	echo '<a href="' . esc_url( $custom_link ) . '" class="btn btn-all btn-sea-pink">';
	if ( $my_lang == 'ru' ){echo "Оформить заказ";}elseif($my_lang == 'eng'){echo "Checkout";}
	echo '</a>';

}


function custom_widget_shopping_cart_proceed_to_checkout() {
	echo '<form class="clear-cart" action="" method="post"><button type="submit" class="btn btn-all btn-iron" name="clear-cart">';
	$my_lang = pll_current_language(); if ( $my_lang == 'ru' ){echo "Очистить все";}elseif($my_lang == 'eng'){echo "Clear all";}
	echo '</button></form>';
}


remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );

add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

add_filter( 'woocommerce_product_variation_title_include_attributes', '__return_false' );
add_filter( 'woocommerce_is_attribute_in_product_name', '__return_false' );

add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
  
function custom_override_checkout_fields( $fields ) {
unset($fields['billing']['billing_company']);
unset($fields['billing']['billing_address_2']);
unset($fields['billing']['billing_postcode']);
unset($fields['order']['order_comments']);
unset($fields['billing']['billing_email']);
unset($fields['account']['account_username']);
unset($fields['account']['account_password']);
unset($fields['account']['account_password-2']);
	return $fields;
}
add_action('template_redirect', 'redirection_function');

function redirection_function(){
	global $woocommerce;

	if( is_checkout() && $woocommerce->cart->cart_contents_count === 0 && !isset($_GET['key']) ) {
		wp_redirect( home_url() );
		exit;
	}
}
add_action('wp_enqueue_scripts', 'override_woo_frontend_scripts');
function override_woo_frontend_scripts() {
	wp_deregister_script('wc-checkout');
	wp_enqueue_script('wc-checkout', get_template_directory_uri() . '/static/js/checkout.js', array('jquery', 'woocommerce', 'wc-country-select', 'wc-address-i18n'), null, true);
}


add_action( 'init', 'move_upsells_after_related' );
function move_upsells_after_related( ) {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 25 );
}

add_filter( 'woocommerce_product_tabs', 'devise_woo_rename_reviews_tab', 98);
function devise_woo_rename_reviews_tab($tabs) {

	$my_lang = pll_current_language(); if ( $my_lang == 'ru' ){
		$tabs['additional_information']['title'] = 'Параметры модели';
	 }elseif($my_lang == 'eng'){
		 $tabs['additional_information']['title'] = 'Model Parameters';
	 }


return $tabs;
}

add_filter('woocommerce_catalog_orderby', 'wc_customize_product_sorting');

function wc_customize_product_sorting($sorting_options){
	$sorting_options = array(
		'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
		'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
		//'menu_order' => __( 'Sorting', 'woocommerce' ),
		'popularity' => __( 'Sort by popularity', 'woocommerce' ),
		'rating'     => __( 'Sort by average rating', 'woocommerce' ),
		//'date'       => __( 'Sort by newness', 'woocommerce' ),
	);

	return $sorting_options;
}

add_filter('woocommerce_single_product_carousel_options', 'ud_update_woo_flexslider_options');
function ud_update_woo_flexslider_options($options) {
	if( wp_is_mobile() ){
		$options['controlNav'] = true;
		return $options;
	}else{
	  

	  $options['controlNav'] = "thumbnails";

	  return $options;
  }
  }

add_action('init', 'woocommerce_clear_cart_url');
function woocommerce_clear_cart_url() {
	global $woocommerce;
	if( isset($_REQUEST['clear-cart']) ) {
		$woocommerce->cart->empty_cart();
	}
}

if (
  !empty($shop_page_id)
  && strstr( $permalinks['product_base'], '/' . $shop_page->post_name )
  && get_option( 'page_on_front' ) !== $shop_page_id
 ) {
   $prepend = $before 
	 . '<a href="' . get_permalink( $shop_page ) . '">' 
	 . $shop_page->post_title . '</a> ' 
	 . $after . $delimiter;
 }


add_filter('woocommerce_update_order_review_fragments', 'websites_depot_order_fragments_split_shipping', 10, 1);

function websites_depot_order_fragments_split_shipping($order_fragments) {

	ob_start();
	websites_depot_woocommerce_order_review_shipping_split();
	$websites_depot_woocommerce_order_review_shipping_split = ob_get_clean();

	$order_fragments['.woocommerce-shipping-totals'] = $websites_depot_woocommerce_order_review_shipping_split;

	return $order_fragments;

}


function websites_depot_woocommerce_order_review_shipping_split( $deprecated = false ) {
	wc_get_template( 'checkout/shipping-order-review.php', array( 'checkout' => WC()->checkout() ) );
}


add_action('shipping_new', 'websites_depot_move_new_shipping_table', 5);

function websites_depot_move_new_shipping_table() {
	echo '<div class="checkout-row__blocks woocommerce-shipping-totals shipping">cxbcvn</div>';
}

add_filter( 'woocommerce_default_address_fields', 'custom_override_default_locale_fields' );
function custom_override_default_locale_fields( $fields ) {
	$fields['first_name']['priority'] = 10;
	$fields['last_name']['priority'] = 20;
	//$fields['billing']['billing_phone']['priority'] = 30;
	//$fields['billing_phone']['priority'] = 30;
	$fields['state']['priority'] = 150;
	$fields['address_1']['priority'] = 170;
	$fields['city']['priority'] = 160;
	return $fields;
}
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );


function custom_override_default_address_fields( $address_fields ) {
	 $address_fields['address_1']['required'] = false;
	 $address_fields['state']['required'] = false;
	 $address_fields['city']['required'] = false;
	 $address_fields['last_name']['required'] = false;
	 return $address_fields;
}
add_filter( 'woocommerce_default_address_fields' , 'bbloomer_rename_state_province', 9999 );
 
function bbloomer_rename_state_province( $fields ) {
	$fields['state']['label'] = 'Область';
	$fields['address_1']['label'] = 'Адрес/Номер отделения';
	$fields['address_1']['placeholder'] = '';
	return $fields;
}