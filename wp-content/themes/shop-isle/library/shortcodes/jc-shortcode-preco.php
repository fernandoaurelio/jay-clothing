<?php 

/**
 * Override loop template and show quantities next to add to cart buttons
 */
function the_product_price($product){
	//get the sale price of the product whether it be simple, grouped or variable
	$sale_price = '<span class="new">R$ '.get_post_meta( get_the_ID(), '_price', true).'</span>';
	//get the regular price of the product, but of a simple product
	$regular_price = get_post_meta( get_the_ID(), '_regular_price', true);
	//oh, the product is variable to $sale_price is empty? Lets get a variation price
	if ($regular_price == ""){
		#Step 1: Get product varations
		$available_variations = $product->get_available_variations();
		if($available_variations){
			#Step 2: Get product variation id
			$variation_id=$available_variations[0]['variation_id']; // Getting the variable id of just the 1st product. You can loop $available_variations to get info about each variation.
			#Step 3: Create the variable product object
			$variable_product1= new WC_Product_Variation( $variation_id );
			#Step 4: You have the data. Have fun :)
			$regular_price = $variable_product1->regular_price;
		}
	}
	if(!empty($regular_price)){
		echo '<span class="old">R$ ';
	}else{
		echo '<span class="new">';
	}
	
	echo $regular_price;
	echo '</span>';
	if(!empty($sale_price)){ echo '<span class="new">'. $sale_price .'</span>';}
}
add_shortcode( 'preco', 'the_product_price' );