jQuery(document).ready(function($) {
	$('.sticky-cart-wrapper .sc-icon img').click(function() {
		$('.sticky-cart-wrapper .sc-cart-contents').html('');
		$('.sticky-cart-wrapper .sc-cart-contents').css('background-image', 'url('+wcpAjax.path+'/images/ajax-loader.gif)');
		var getCart = {
			action: 'get_cart_contents'
		}
		$.post(wcpAjax.url, getCart, function(resp) {
			$('.sticky-cart-wrapper .sc-cart-contents').html(resp);
			$('.sticky-cart-wrapper .sc-cart-contents .cart-collaterals').remove();
			$('.sticky-cart-wrapper .sc-cart-contents td.actions').remove();
			$('.sticky-cart-wrapper .sc-cart-contents').css('background-image', 'none');
			$('.sticky-cart-wrapper .sc-cart-contents .quantity input').attr('disabled', 'disabled');
			remove_item();
		});
		$('.sc-icon img').hide();
		$('.sc-icon span').show();
		if ($('.sticky-cart-wrapper').hasClass('wcp-right')) { $('.sticky-cart-wrapper').animate({right: 0}, 500); };
		if ($('.sticky-cart-wrapper').hasClass('wcp-left')) { $('.sticky-cart-wrapper').animate({left: 0}, 500); };
		$('#preview-overlay').show();
	});
	$('.sc-icon span, #preview-overlay').click(function() {
		$('.sc-icon span').hide();
		$('.sc-icon img').show();
		$('#preview-overlay').hide();
		if ($('.sticky-cart-wrapper').hasClass('wcp-left')) { $('.sticky-cart-wrapper').animate({left: -450}, 500); };
		if ($('.sticky-cart-wrapper').hasClass('wcp-right')) { $('.sticky-cart-wrapper').animate({right: -450}, 500); };
	});
	
	function remove_item(){
	    $('.sc-cart-contents .product-remove a.remove').on('click', function(event) {
	        event.preventDefault();
			$('.sticky-cart-wrapper .sc-cart-contents').html('');
			$('.sticky-cart-wrapper .sc-cart-contents').css('background-image', 'url('+wcpAjax.path+'/images/ajax-loader.gif)');
	        var link = jQuery(this).attr('href');
	        key = link.substring(link.indexOf("&"),link.indexOf("=")+1);
	        jQuery.post(wcpAjax.url, {action: 'remove_cart_item', key: key}, function(resp) {
				$('.sticky-cart-wrapper .sc-cart-contents').html(resp);
				$('.sticky-cart-wrapper .sc-cart-contents .cart-collaterals').remove();
				$('.sticky-cart-wrapper .sc-cart-contents td.actions').remove();
				$('.sticky-cart-wrapper .sc-cart-contents').css('background-image', 'none');
				remove_item();
	        });
	    });
	}
});