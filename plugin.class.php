<?php
/**
* Plugin Main Class
*/
class WCP_Sticky_Cart
{
	
	function __construct()
	{
		add_action( 'admin_menu', array( $this, 'sticky_cart_admin_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_options_page_scripts' ) );
		add_action( 'wp_ajax_wcp_save_sticky_cart_settings', array($this, 'save_sticky_cart_settings'));
		add_action( 'wp_enqueue_scripts', array($this, 'adding_styles' ) );
		add_action( 'plugins_loaded', array($this, 'wcp_load_plugin_textdomain' ) );

		// WooCommerce
		add_action( 'woocommerce_before_shop_loop', array($this, 'before_shop_loop' ) );
		add_action( 'wp_ajax_get_cart_contents', array($this, 'get_cart_contents' ) );
		add_action( 'wp_ajax_nopriv_get_cart_contents', array($this, 'get_cart_contents' ) );
		add_action( 'wp_ajax_remove_cart_item', array($this, 'remove_cart_item' ) );
		add_action( 'wp_ajax_nopriv_remove_cart_item', array($this, 'remove_cart_item' ) );
	}

	function adding_styles(){
		if (is_shop()) {
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'sticky-cart-css', plugins_url( 'css/style.css' , __FILE__ ));
			wp_enqueue_script( 'sticky-cart-js', plugins_url( 'js/script.js' , __FILE__ ), array('jquery') );
			wp_localize_script( 'sticky-cart-js', 'wcpAjax', array( 'url' => admin_url( 'admin-ajax.php' ), 'path' => plugin_dir_url( __FILE__ )));
		}
	}

	function admin_options_page_scripts($slug){
		if ($slug == 'product_page_sticky_cart') {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'sticky-cart-admin-js', plugins_url( 'admin/script.js' , __FILE__ ), array('jquery', 'wp-color-picker') );
			wp_localize_script( 'sticky-cart-admin-js', 'wcpAjax', array( 'url' => admin_url( 'admin-ajax.php' ), 'path' => plugin_dir_url( __FILE__ )));
		}
	}

	function wcp_load_plugin_textdomain(){
		load_plugin_textdomain( 'wcp-sticky-cart', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	function save_sticky_cart_settings(){
		if (isset($_REQUEST)) {
			update_option( 'wcp_sticky_cart', $_REQUEST );
		}

		die(0);
	}

	function sticky_cart_admin_options(){
		add_submenu_page( 'edit.php?post_type=product', 'WooCommerce Sticky Cart', 'Sticky Cart', 'manage_options', 'sticky_cart', array($this, 'render_menu_page') );
	}

	function render_menu_page(){
		$settings = get_option( 'wcp_sticky_cart' );
		if (is_array($settings)) {
			extract($settings);
		}
		?>
			<h2><?php _e( 'Sticky Cart Settings', 'wcp-sticky-cart' ); ?></h2>
			<table class="form-table sticky-settings">
				<tr>
					<th><?php _e( 'Position', 'wcp-sticky-cart' ); ?></th>
					<td><select class="position widefat">
						<option value="right" <?php if(isset($position)) { selected( $position, 'right' );} ?>><?php _e( 'Right', 'wcp-sticky-cart' ); ?></option>
						<option value="left" <?php if(isset($position)) { selected( $position, 'left' );} ?>><?php _e( 'Left', 'wcp-sticky-cart' ); ?></option>
					</select></td>
					<td><p class="description"><?php _e( 'Select on which side you want to show sticky cart (Default: Right)', 'wcp-sticky-cart' ); ?></p></td>
				</tr>
				<tr>
					<th><?php _e( 'Top', 'wcp-sticky-cart' ); ?></th>
					<td><input type="number" class="top widefat" value="<?php if(isset($top)) echo $top; ?>"></td>
					<td><p class="description"><?php _e( 'Position of button from top (Default: 100)', 'wcp-sticky-cart' ); ?></p></td>
				</tr>
				<tr>
					<th><?php _e( 'Cart Background Color', 'wcp-sticky-cart' ); ?></th>
					<td><input type="text" class="bgcolor color-picker" value="<?php if(isset($bgcolor)) echo $bgcolor; ?>"></td>
					<td><p class="description"><?php _e( 'Background color of cart', 'wcp-sticky-cart' ); ?></p></td>
				</tr>
				<tr>
					<th><?php _e( 'Cart Border Color', 'wcp-sticky-cart' ); ?></th>
					<td><input type="text" class="bordercolor color-picker" value="<?php if(isset($bordercolor)) echo $bordercolor; ?>"></td>
					<td><p class="description"><?php _e( 'Border color of cart', 'wcp-sticky-cart' ); ?></p></td>
				</tr>
			</table>
			<hr>
			<button class="button-primary save-sticky"><?php _e( 'Save Changes', 'wcp-sticky-cart' ); ?></button>
			<span id="wcp-loader" style="display: none; width: 30px;"><img src="<?php echo plugins_url( 'images/ajax-loader2.gif' , __FILE__ ) ?>" alt="Loading"></span>
			<span id="wcp-saved" style="display: none; color: green;"><?php _e( 'Changes Saved', 'wcp-sticky-cart' ); ?>!</span>
		<?php
	}

	function before_shop_loop(){
		$settings = get_option( 'wcp_sticky_cart' );
		if (is_array($settings)) {
			extract($settings);
		}		
		?>	
			<span id="preview-overlay"></span>
			<div class="sticky-cart-wrapper wcp-<?php if(isset($position)) { echo $position; } else {echo 'right'; } ?>" style="top: <?php if(isset($top)) echo $top; ?>px;">
				<div class="sc-icon" style="background-color: <?php if(isset($bgcolor)) echo $bgcolor; ?>; border-color: <?php if(isset($bordercolor)) echo $bordercolor; ?>;">
					<img src="<?php echo plugins_url( 'images/cart.png' , __FILE__ ); ?>" alt="Cart Icon">
					<span class="dashicons dashicons-no-alt"></span>
				</div>
				<div class="sc-cart-contents" style="background-color: <?php if(isset($bgcolor)) echo $bgcolor; ?>; border-color: <?php if(isset($bordercolor)) echo $bordercolor; ?>;">
					
				</div>
			</div>
		<?php
	}

	function get_cart_contents(){

		if ( ! defined( 'WOOCOMMERCE_CART' ) ) {
			define( 'WOOCOMMERCE_CART', true );
		}		
		if ( WC()->cart->get_cart_contents_count() == 0 ) {
		    echo '<p>Your cart is currently empty.</p>';
		} else {
			$path = WC()->plugin_path() . '/templates/cart/cart.php';
			echo load_template( $path );
		}
		echo '<p style="text-align: center;"><a href="'.WC()->cart->get_cart_url().'" class="button alt wc-forward">'.__( 'Go to Cart', 'wcp-sticky-cart' ).'</a></p>';

		die(0);	
	}

	function remove_cart_item(){
		extract($_REQUEST);

		WC()->cart->remove_cart_item( $key );		

		if ( WC()->cart->get_cart_contents_count() == 0 ) {
		    echo '<p>Your cart is currently empty.</p>';
		} else {
			$path = WC()->plugin_path() . '/templates/cart/cart.php';
			echo load_template( $path );
		}

		echo '<p style="text-align: center;"><a href="'.WC()->cart->get_cart_url().'" class="button alt wc-forward">'.__( 'Go to Cart', 'wcp-sticky-cart' ).'</a></p>';
		
		die(0);
	}

}

?>