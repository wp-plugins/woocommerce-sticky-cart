jQuery(document).ready(function($) {
	$('.color-picker').wpColorPicker();

	$('.save-sticky').click(function(event) {
		event.preventDefault();

		jQuery('#wcp-saved').hide();
		jQuery('#wcp-loader').show();

		var position = $('.sticky-settings .position').val();
		var top = $('.sticky-settings .top').val();
		// var shop_page = true;
		// var single_page = true;
		// var checkout_page = false;
		// if ($('.sticky-settings .shop_page').is(":checked")){ shop_page = true; } else { shop_page = false; }
		// if ($('.sticky-settings .single_page').is(":checked")){ single_page = true; } else { single_page = false; }
		// if ($('.sticky-settings .checkout_page').is(":checked")){ checkout_page = true; } else { checkout_page = false; }
		var bgcolor = $('.sticky-settings .bgcolor').val();
		var bordercolor = $('.sticky-settings .bordercolor').val();

		var data = {
			action: 'wcp_save_sticky_cart_settings',
			position: position,
			top: top,
			// shop_page: shop_page,
			// single_page: single_page,
			// checkout_page: checkout_page,
			bgcolor: bgcolor,
			bordercolor, bordercolor
		}

		$.post(wcpAjax.url, data, function(resp) {
            jQuery('#wcp-loader').hide();
            jQuery('#wcp-saved').show();			
		});
	});
});