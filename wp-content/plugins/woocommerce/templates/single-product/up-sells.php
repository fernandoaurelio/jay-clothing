<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) : ?>

	<section class="up-sells upsells products">

		<h2>Talvez você curta</h2>

		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $upsells as $upsell ) : ?>

				<div class="box-produtos">
				<div class="card-produto">
					<div class="card-categoria">
						<p>
							<?php 				

							echo $upsell->term_id;
							?>
						</p>
					</div>
					<div class="card-imagem" style="background-image: url(<?= get_the_post_thumbnail_url(); ?>);">
						<div class="desconto">
							<p>20%</p>
						</div>
					</div>
					<div class="card-descricao">
						<div class="descricao-titulo">
							<h5><?= get_the_title();  ?></h5>
						</div>
						<div class="produto-preco">
							<p><?= $upsell->get_price();  ?></p>
						</div>
					</div>
					<div class="card-botoes">
						<a href="<?= get_the_permalink($post_id);  ?>" class="carrinho">Comprar</a>								
					</div>
				</div>
				<!-- fim card -->						
			</div>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>

<?php endif;

wp_reset_postdata();
