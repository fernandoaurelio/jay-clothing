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
				'product_cat' => 'homem'
			);
			
			$query = new WP_Query( $args );

			if($query->have_posts()){
				while($query->have_posts()){
					$query->the_post();
					?>
			<div class="box-produtos">
				<div class="card-produto">
					<div class="card-desconto">
						<p>Outlet</p>
					</div>
					<div class="card-imagem" style="background-image: url('<?= woocommerce_get_product_thumbnail($post_id); ?>');"></div>
					<div class="card-descricao">
						<div class="descricao-titulo">
							<h5><?= get_the_title();  ?></h5>
						</div>
						<div class="produto-preco">
							<p><?= $product->get_price();  ?></p>
						</div>
					</div>
					<div class="card-botoes">
						<a href="#" class="carrinho"><span><i class="fas fa-cart-plus"></i> </span>Adicione ao Carrinho</a>
						<a href="<?= get_the_permalink($post_id);  ?>" class="detalhes">Mais Detalhes</a>
					</div>
				</div>
				<!-- fim card -->
					<?php
				}
			}
			
			 ?>
			</div>

		</div>	
	</div>
</div>
<?php

get_footer();

