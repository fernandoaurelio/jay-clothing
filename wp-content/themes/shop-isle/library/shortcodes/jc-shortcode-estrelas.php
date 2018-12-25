<?php 
add_filter( 'woocommerce_product_get_rating_html', 'loop_product_get_rating_html', 20, 3 );
function loop_product_get_rating_html( $html, $rating, $count ){

        global $product;
        $rating_cnt = array_sum($product->get_rating_counts());
        $count_html = ' <div class="count-rating">' . $rating_cnt .'</div>';

        $html       = '<div class="container-rating"><div class="star-rating">';
        $html      .= wc_get_star_rating_html( $rating, $count );
        $html      .= '</div>' . $count_html . '</div>';

    return $html;
}
add_shortcode( 'estrelas', 'loop_product_get_rating_html' );