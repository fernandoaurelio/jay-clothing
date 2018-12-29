<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class(); ?>>
	<div class="box-produtos">
		<div class="card-produto">
			<div class="card-categoria">
				<p>
					<?php 
					$categorias = get_the_terms( $post_id, 'product_cat' );

					foreach ($categorias as $categoria) {
						
					}
					echo $categoria->name;
					?>
				</p>
			</div>
			<div class="card-imagem" style="background-image: url(<?= get_the_post_thumbnail_url(); ?>);">
			</div>
			<div class="card-descricao">
				<div class="descricao-titulo">
					<h5><?= get_the_title();  ?></h5>
				</div>
				<div class="produto-preco">
					<?php echo do_shortcode('[preco]'); ?>
				</div>
			</div>
			<div class="card-botoes">
				<a href="<?= get_the_permalink($post_id);  ?>" class="carrinho">Comprar</a>								
			</div>
		</div>
		<!-- fim card -->						
	</div>
</li>
