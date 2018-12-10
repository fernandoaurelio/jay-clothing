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
		<div class="titulo-produto">
			<h1>Aqui tem titulo</h1>
		</div>
		<div class="card-produto-container">
			<div class="card-produto">
				<div class="card-image" style="background-image: url('<?= bloginfo('url');  ?>/wp-content/uploads/2018/12/roupa_feminina2.jpg');">
					<div class="card-whishlist">
						<ul class="social">
							<li><i class="far fa-heart"></i></li>
							<li><i class="fas fa-share"></i></li>
						</ul>
					</div>					
				</div>
				<div class="card-descricao">
					<div class="descricao-texto">
						<h4>Camiseta Feminina #2</h4>
						<p>Uma linda camisa, para o teste a seguir</p>
					</div>
					<div class="descricao-shop">
						<ul>
							<li><a href="#">Leia Mais</a></li>
							<li class="shop"><a href="#"><span><i class="fas fa-shopping-cart"></i></span>Comprar</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- OUtro Card -->
			<div class="card-produto">
				<div class="card-image" style="background-image: url('<?= bloginfo('url');  ?>/wp-content/uploads/2018/12/roupa_feminina2.jpg');">
					<div class="card-whishlist">
						<ul class="social">
							<li><i class="far fa-heart"></i></li>
							<li><i class="fas fa-share"></i></li>
						</ul>
					</div>					
				</div>
				<div class="card-descricao">
					<div class="descricao-texto">
						<h4>Camiseta Feminina #2</h4>
						<p>Uma linda camisa, para o teste a seguir</p>
					</div>
					<div class="descricao-shop">
						<ul>
							<li><a href="#">Leia Mais</a></li>
							<li class="shop"><a href="#"><span><i class="fas fa-shopping-cart"></i></span>Comprar</a></li>
						</ul>
					</div>
				</div>
			</div>
						<!-- OUtro Card -->
			<div class="card-produto">
				<div class="card-image" style="background-image: url('<?= bloginfo('url');  ?>/wp-content/uploads/2018/12/roupa_feminina2.jpg');">
					<div class="card-whishlist">
						<ul class="social">
							<li><i class="far fa-heart"></i></li>
							<li><i class="fas fa-share"></i></li>
						</ul>
					</div>					
				</div>
				<div class="card-descricao">
					<div class="descricao-texto">
						<h4>Camiseta Feminina #2</h4>
						<p>Uma linda camisa, para o teste a seguir</p>
					</div>
					<div class="descricao-shop">
						<ul>
							<li><a href="#">Leia Mais</a></li>
							<li class="shop"><a href="#"><span><i class="fas fa-shopping-cart"></i></span>Comprar</a></li>
						</ul>
					</div>
				</div>
			</div>
			<!-- FIM -->
		</div>
	</div>
</div>
<?php

get_footer();

