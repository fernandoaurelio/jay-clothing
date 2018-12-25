jQuery(document).ready(function(){
	jQuery('.item-menu').on('hover', function(){		
		jQuery('.item-menu .menu-account').toggleClass('esconder');
	});	

	jQuery(".tamanho, .cor").on("click", function(){
		jQuery(this).toggleClass("selecionado");
	});
});