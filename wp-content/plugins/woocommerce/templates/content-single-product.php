<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
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

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>

	<div class="container-produto">
		<div class="container-bloco-imagem">
			<div class="bloco-galeria">
				<?php 
				global $product;
				$attachment_ids = $product->get_gallery_attachment_ids();
				echo "<ul class='galeria'>";
				foreach( $attachment_ids as $attachment_id ) {			        
					echo "<li class='foto-galeria'><img src='".wp_get_attachment_url( $attachment_id )."' alt='Galeria de imagens do ".get_the_title( $post->ID )."'></li>";
				} 
				echo "</ul>"
				?>
			</div>
			<div class="bloco-imagem">
				<?php 
				if($product->get_sale_price() != " "){

					echo "<span class='desconto'><p>Promoção</p></span>";
				}
				?>
				<img src="<?= get_the_post_thumbnail_url($post->ID);  ?>" alt="<?= get_the_title( $post->ID );  ?>" class="foto-principal">
			</div>
		</div>
		<div class="container-bloco-info">
			<div class="bloco-info">
				<h5><?= get_the_title($post->ID);  ?></h5>
				<section class="preco">					
					<p class="preco">
						<?php echo do_shortcode('[preco]'); ?>
					</p>
				</section>
				<h5>Tamanhos</h5>
				<?php 
				$tamanhos = get_field('tamanhos', $post->ID); 

				foreach ($tamanhos as $tamanho) {
					echo "<label class='tamanho' for='check-tamanho".$tamanho."'>".$tamanho."</label>";
					echo "<input type='checkbox' name='check-tamanho' id='check-tamanho".$tamanho."' value='".$tamanho."'>";
				}
				?>
				<section class="quantidade">					
					<h5>Quantidade</h5>
					<?php 
					echo do_shortcode('[quantidade-produto]');
					?>
				</section>
				<section class="cores">					
					<h5>Cores</h5>
					<?php 
					$cores = get_field('cores', $post->ID); 

					foreach ($cores as $cor) {
						echo "<label class='cor' for='check-cor".$cor."'>".$cor."</label>";
						echo "<input type='checkbox' name='check-cor' id='check-cor".$cor."' value='".$cor."'>";
					}
					?>
				</section>
				<section class="detalhes">					
					<h5>Detalhes da Peça</h5>
					<?= the_content(); ?>
				</section>
			</div>
		</div>

		<div class="bloco-adicionais">			
			<h5>Votação</h5>
			<?php 
			echo do_shortcode('[cusrev_reviews]');
			?>
			<h5>Não fique com dúvidas</h5>
			<?php 
			echo do_shortcode('[wpforms id="150" title="false" description="false"]');
			?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
