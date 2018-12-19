<?php
/**
 * The front-page.php
 *
 * @package ShopIsle
 */

get_header();
/* Wrapper start */
?>
<div class="principal-conteudo">
	<div class="banner">
		<?php echo do_shortcode('[smartslider3 slider=2]');?>
	</div>
	<div class="container-produtos">
		<div class="titulo-secao">
			<h2>Lançamentos</h2>
		</div>
		<div class="card-produtos-container">
			<!-- inicio card -->
			<?php 
			$product = wc_get_product( $post_id );
			$args = array(
				'post_type' => 'product',
				//'product_cat' => 'homem'
			);
			
			$query = new WP_Query( $args );

			if($query->have_posts()){
				while($query->have_posts()){
					$query->the_post();
					?>
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
								<div class="desconto">
									<p>20%</p>
								</div>
							</div>
							<div class="card-descricao">
								<div class="descricao-titulo">
									<h5><?= get_the_title();  ?></h5>
								</div>
								<div class="produto-preco">
									<p><?= $product->get_price();  ?></p>
								</div>
							</div>
							<div class="card-botoes">
								<a href="<?= do_shortcode('[add_to_cart_url id="'.$post->ID.'"]');  ?>" data-quantity="1" class="button product_type_simple add_to_cart_button ajax_add_to_cart carrinho" data-product_id="<?= $post->ID;  ?>" data-product_sku="" aria-label="Add “<?php echo get_the_title($post_id); ?>” ao carrinho" rel="nofollow">Adicionar ao carrinho</a>
								<a href="<?= get_the_permalink($post_id);  ?>" class="detalhes">Mais Detalhes</a>
							</div>
						</div>
						<!-- fim card -->						
					</div>
						<?php
					}
				}

				?>

		</div>	
	</div>
</div>
<?php

get_footer();

