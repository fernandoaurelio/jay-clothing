<?php 

/**
 * Override loop template and show quantities next to add to cart buttons
 */
function the_product_price($product){
global $product;
$precoNormal =  $product ->regular_price;
$precoPromocao = $product ->sale_price;

	if(!empty($precoNormal)){
		echo '<span class="normal">R$ ';
	}else{
		echo '<span class="new">';
	}
	
	echo $precoNormal;
	echo '</span>';
	if(!empty($precoPromocao)){ echo '<span class="new" style="margin-left:30px;">R$ '. $precoPromocao .'</span>';}
}
add_shortcode( 'preco', 'the_product_price' );