<?php
/**
 * The header for our theme.
 *
 * @package shop-isle
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php } ?>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

	<nav class="navbar-top">
		<div class="faixa-topo">
			<div class="pesquisa">
			</div>
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="Veja os Itens do Carrinho">
				<div class="carrinho-compras">
					<span class="carrinho-icon">
						<i class="fas fa-shopping-cart"></i>
					</span>
					<?php if ( function_exists( 'WC' ) ) : ?>
						<div class="navbar-cart-inner">											
							<span class="cart-item-number"><?php echo esc_html( trim( WC()->cart->get_cart_contents_count() ) ); ?></span>	
							<?php apply_filters( 'shop_isle_cart_icon', '' ); ?>
						</div>
					<?php endif; ?>
				</div>
			</a>
			<div class="menu-conta">
				<span class="item-menu">
					<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">Minha Conta</a>
					<span class="menu-account esconder">						
						<ul>
							<?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
								<li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
									<a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
								</li>
							<?php endforeach; ?>
						</ul>
					</span>
				</span>
			</div>
		</div>
	</nav>
	<header class="header <?php echo esc_attr( $header_class ); ?>">
	<section class="menu">
		<div class="menu-principal">
			<?php  
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav navbar-nav navbar-right',
				)
			);
			?>
		</div>
	</section>

	</header>
