jQuery(document).ready(function(){
	jQuery('.item-menu').on('hover', function(){		
		jQuery('.item-menu .menu-account').toggleClass('esconder');
	});

	jQuery(".carrinho").on("click", function(){
		if (jQuery(this).hasClass("added")) {
			jQuery(this).css("background-color","black");
		} else {
			jQuery(this).css("background-color","green");
		}
	});	
});