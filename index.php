<?php
/**
 * Plugin Name: WooCommerce Sticky Cart
 * Plugin URI: http://webcodingplace.com/woocommerce-sticky-cart
 * Description: Sticky Cart button with AJAX based Cart contents
 * Version: 1.0
 * Author: Rameez
 * Author URI: http://webcodingplace.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wcp-sticky-cart
 */

/*

  Copyright (C) 2015  Rameez  rameez.iqbal@live.com
*/
require_once('plugin.class.php');

if( class_exists('WCP_Sticky_Cart')){
	
	$just_initialize = new WCP_Sticky_Cart;
}
?>