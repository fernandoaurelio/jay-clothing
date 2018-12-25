<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ivole_Email' ) ) :

/**
 * Reminder email for product reviews
 */
class Ivole_Email {

	public $id;
	public $to;
	public $heading;
	public $subject;
	public $template_html;
	public $template_items_html;
	public $from;
	public $from_name;
	public $bcc;
	public $replyto;
	public $language;
	public $footer;
	public $find = array();
	public $replace = array();
	public static $default_body = "Hi {customer_first_name},\n\nThank you for shopping with us!\n\nWe would love if you could help us and other customers by reviewing products that you recently purchased in order #{order_id}. It only takes a minute and it would really help others. Click the button below and leave your review!\n\nBest wishes,\nJohn Doe\nCEO of Sky Shop";
	public static $default_body_coupon = "Hi {customer_first_name},\n\nThank you for reviewing your order!\n\nAs a token of appreciation, we’d like to offer you a discount coupon for your next purchases in our shop. Please apply the following coupon code during checkout to receive {discount_amount} discount.\n\n<strong>{coupon_code}</strong>\n\nBest wishes,\nJohn Doe\nCEO of Sky Shop";
	/**
	 * Constructor.
	 */
	public function __construct( $order_id = 0 ) {
		$this->id               = 'ivole_reminder';
		$this->heading          = strval( get_option( 'ivole_email_heading', __( 'How did we do?', 'ivole' ) ) );
		$this->subject          = strval( get_option( 'ivole_email_subject', '[{site_title}] ' . __( 'Review Your Experience with Us', 'ivole' ) ) );
		$this->form_header      = strval( get_option( 'ivole_form_header', __( 'How did we do?', 'ivole' ) ) );
		$this->form_body        = strval( get_option( 'ivole_form_body', __( 'Please review your experience with products and services that you purchased at {site_title}.', 'ivole' ) ) );
		$this->template_html    = Ivole_Email::plugin_path() . '/templates/email.php';
		$this->template_items_html    = Ivole_Email::plugin_path() . '/templates/email_items.php';
		$this->language					= get_option( 'ivole_language', 'EN' );
		$this->from_name				= get_option( 'ivole_email_from_name', Ivole_Email::get_blogname() );
		$this->footer						= get_option( 'ivole_email_footer', '' );

		$this->find['site-title'] = '{site_title}';
		$this->replace['site-title'] = Ivole_Email::get_blogname();

		//qTranslate integration
		if( function_exists( 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) ) {
			$this->heading = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->heading );
			$this->subject = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->subject );
			$this->form_header = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->form_header );
			$this->form_body = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->form_body );
			$this->from_name = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->from_name );
			$this->footer = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $this->footer );
			if( 'QQ' === $this->language ) {
				global $q_config;
				$this->language = strtoupper( $q_config['language'] );
			}
		}

		//WPML integration
		if ( has_filter( 'wpml_translate_single_string' ) && defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE ) {
			$wpml_current_language = apply_filters( 'wpml_current_language', NULL );
			if ( $order_id ) {
				$wpml_current_language = get_post_meta( $order_id, 'wpml_language', true );
			}
			$this->heading = apply_filters( 'wpml_translate_single_string', $this->heading, 'ivole', 'ivole_email_heading', $wpml_current_language );
			$this->subject = apply_filters( 'wpml_translate_single_string', $this->subject, 'ivole', 'ivole_email_subject', $wpml_current_language );
			$this->form_header = apply_filters( 'wpml_translate_single_string', $this->form_header, 'ivole', 'ivole_form_header', $wpml_current_language );
			$this->form_body = apply_filters( 'wpml_translate_single_string', $this->form_body, 'ivole', 'ivole_form_body', $wpml_current_language );
			$this->from_name = apply_filters( 'wpml_translate_single_string', $this->from_name, 'ivole', 'ivole_email_from_name', $wpml_current_language );
			$this->footer = apply_filters( 'wpml_translate_single_string', $this->footer, 'ivole', 'ivole_email_footer', $wpml_current_language );
 			if ( 'WPML' === $this->language ) {
				$this->language = strtoupper( $wpml_current_language );
			}
		}
	}

	/**
	 * Trigger version 2.
	 */
	public function trigger2( $order_id, $to = null ) {
		$this->find['customer-first-name']  = '{customer_first_name}';
		$this->find['customer-name'] = '{customer_name}';
		$this->find['order-id'] = '{order_id}';
		$this->find['order-date'] = '{order_date}';
		$this->find['list-products'] = '{list_products}';
		$api_url = '';

		$this->from = get_option( 'ivole_email_from', '' );

		// check if Reply-To address needs to be added to email
		$this->replyto = get_option( 'ivole_email_replyto', get_option( 'admin_email' ) );
		if( filter_var( $this->replyto, FILTER_VALIDATE_EMAIL ) ) {
			$this->replyto = $this->replyto;
		} else {
			$this->replyto = get_option( 'admin_email' );
		}

		$comment_required = get_option( 'ivole_form_comment_required', 'no' );
		if( 'no' === $comment_required ) {
			$comment_required = 0;
		} else {
			$comment_required = 1;
		}

		$shop_rating = get_option( 'ivole_form_shop_rating', 'no' );
		if( 'yes' === $shop_rating ) {
			$shop_rating = true;
		} else {
			$shop_rating = false;
		}

		$allowMedia = 'yes' === get_option( 'ivole_form_attach_media', 'no' ) ? true : false;

		if ( $order_id ) {
			//check if Limit Number of Reviews option is used
			if( 'yes' === get_option( 'ivole_limit_reminders', 'yes' ) ) {
				//check how many reminders have already been sent for this order (if any)
				$reviews = get_post_meta( $order_id, '_ivole_review_reminder', true );
				if( $reviews >= 1 ) {
					//if more than one, then we cannot send email
					return 3;
				}
			}
			//check if registered customers option is used
			$registered_customers = false;
			if( 'yes' === get_option( 'ivole_registered_customers', 'no' ) ) {
				$registered_customers = true;
			}
			$order = new WC_Order( $order_id );

      //check customer roles
      $for_role = get_option( 'ivole_enable_for_role', 'all' );
      $enabled_roles = get_option( 'ivole_enabled_roles', array() );

			// check if taxes should be included in list_products variable
			$tax_displ = get_option( 'woocommerce_tax_display_cart' );
			$incl_tax = false;
			if ( 'excl' === $tax_displ ) {
				$incl_tax = false;
			} else {
				$incl_tax = true;
			}

			// check if we are dealing with old WooCommerce version
			$customer_first_name = '';
			$customer_last_name = '';
			$order_date = '';
			$order_currency = '';
			$order_items = array();
			$user = NULL;
			if( method_exists( $order, 'get_billing_email' ) ) {
				// Woocommerce version 3.0 or later
				$user = $order->get_user();
				if( $registered_customers ) {
					if( $user ) {
						$this->to = $user->user_email;
					} else {
						$this->to = $order->get_billing_email();
					}
				} else {
					$this->to = $order->get_billing_email();
				}
				$this->replace['customer-first-name'] = $order->get_billing_first_name();
				$customer_first_name = $order->get_billing_first_name();
				$customer_last_name = $order->get_billing_last_name();
				$this->replace['customer-name'] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
				//$this->replace['order-id'] = $order_id;
				$this->replace['order-id'] = $order->get_order_number();
				$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $order->get_date_created() ) );
				$order_date = date_i18n( 'd.m.Y', strtotime( $order->get_date_created() ) );
				$order_currency = $order->get_currency();

				$price_args = array( 'currency' => $order_currency );
				$list_products = '';
				foreach ( $order->get_items() as $order_item ) {
					$list_products .= $order_item->get_name() . ' / ' . wc_price( $order->get_line_subtotal( $order_item, $incl_tax ), $price_args ) . '<br/>';
				}
				$this->replace['list-products'] = $list_products;
			} else {
				// Woocommerce before version 3.0
				$user_id = get_post_meta( $order_id, '_customer_user', true );
				if( $user_id ) {
					$user = get_user_by( 'id', $user_id );
				}
				if( $registered_customers ) {
					if( $user ) {
						$this->to = $user->user_email;
					} else {
						$this->to = get_post_meta( $order_id, '_billing_email', true );
					}
				} else {
					$this->to = get_post_meta( $order_id, '_billing_email', true );
				}
				$this->replace['customer-first-name'] = get_post_meta( $order_id, '_billing_first_name', true );
				$customer_first_name = get_post_meta( $order_id, '_billing_first_name', true );
				$customer_last_name = get_post_meta( $order_id, '_billing_last_name', true );
				$this->replace['customer-name'] = get_post_meta( $order_id, '_billing_first_name', true ) . ' ' . get_post_meta( $order_id, '_billing_last_name', true );
				//$this->replace['order-id'] = $order_id;
				$this->replace['order-id'] = $order->get_order_number();
				$this->replace['order-date']   = date_i18n( wc_date_format(), strtotime( $order->order_date ) );
				$order_date = date_i18n( 'd.m.Y', strtotime( $order->order_date ) );
				$order_currency = $order->get_order_currency();

				$price_args = array( 'currency' => $order_currency );
				$list_products = '';
				foreach ( $order->get_items() as $order_item ) {
					$list_products .= $order_item['name'] . ' / ' . wc_price( $order_item['line_total'], $price_args ) . '<br/>';
				}
				$this->replace['list-products'] = $list_products;
			}
			//check customer roles if there is a restriction to which roles reminders should be sent
			if( 'roles' === $for_role ) {
				if( isset( $user ) && !empty( $user ) ) {
					$roles = $user->roles;
					$intersection = array_intersect( $enabled_roles, $roles );
					if( count( $intersection ) < 1 ){
							//customer has no allowed roles
							return 5;
					}
				}
			}
			// check if BCC address needs to be added to email
			$bcc_address = get_option( 'ivole_email_bcc', '' );
			if( filter_var( $bcc_address, FILTER_VALIDATE_EMAIL ) ) {
				$this->bcc = $bcc_address;
			} else {
				$this->bcc = '';
			}

			$message = $this->get_content();
			$message = $this->replace_variables( $message );

			//save secret key for callback to DB
			$secret_key = bin2hex(openssl_random_pseudo_bytes(16));
			update_post_meta( $order_id, 'ivole_secret_key', $secret_key );

			$data = array(
				'token' => '164592f60fbf658711d47b2f55a1bbba',
				'shop' => array( "name" => Ivole_Email::get_blogname(),
			 		'domain' => Ivole_Email::get_blogurl(),
				 	'country' => apply_filters( 'woocommerce_get_base_location', get_option( 'woocommerce_default_country' ) ) ),
				'email' => array( 'to' => $this->to,
					'from' => $this->from,
					'fromText' => $this->from_name,
					'bcc' => $this->bcc,
					'replyTo' => $this->replyto,
			 		'subject' => $this->replace_variables( $this->subject ),
					'header' => $this->replace_variables( $this->heading ),
					'body' => $message,
				 	'footer' => $this->footer ),
				'customer' => array( 'firstname' => $customer_first_name,
					'lastname' => $customer_last_name ),
				'order' => array( 'id' => strval( $order_id ),
			 		'date' => $order_date,
					'currency' => $order_currency,
				 	'items' => Ivole_Email::get_order_items2( $order ) ),
				'callback' => array( //'url' => get_option( 'home' ) . '/wp-json/ivole/v1/review',
					'url' => get_rest_url( null, '/ivole/v1/review' ),
					'key' => $secret_key ),
				'form' => array('header' => $this->replace_variables( $this->form_header ),
					'description' => $this->replace_variables( $this->form_body ),
				 	'commentRequired' => $comment_required,
				 	'allowMedia' => $allowMedia,
				 	'shopRating' => $shop_rating ),
				'colors' => array(
					'form' => array(
						'bg' => get_option( 'ivole_form_color_bg', '#0f9d58' ),
						'text' => get_option( 'ivole_form_color_text', '#ffffff' )
					),
					'email' => array(
						'bg' => get_option( 'ivole_email_color_bg', '#0f9d58' ),
						'text' => get_option( 'ivole_email_color_text', '#ffffff' )
					)
				),
				'language' => $this->language
			);
			//check that array of items is not empty
			if( 1 > count( $data['order']['items'] ) ) {
				return 4;
			}
			$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/review-reminder';
		} else {
			// no order number means this is a test and we should provide some dummy information
			$this->replace['customer-first-name'] = __( 'Jane', 'ivole' );
			$this->replace['customer-name'] = __( 'Jane Doe', 'ivole' );
			$this->replace['order-id'] = 12345;
			$this->replace['order-date'] = date_i18n( wc_date_format(), time() );
			$this->replace['list-products'] = sprintf(
				'%s / %s<br/>%s / %s<br/>',
				__( 'Item 1 Test', IVOLE_TEXT_DOMAIN ),
				'$15',
				__( 'Item 2 Test', IVOLE_TEXT_DOMAIN ),
				'$150'
			);

			$message = $this->get_content();
			$message = $this->replace_variables( $message );

			$data = array(
				'token' => '164592f60fbf658711d47b2f55a1bbba',
				'shop' => array( "name" => Ivole_Email::get_blogname(),
			 	'domain' => Ivole_Email::get_blogurl() ),
				'email' => array( 'to' => $to,
					'from' => $this->from,
					'fromText' => $this->from_name,
					'replyTo' => $this->replyto,
			 		'subject' => $this->replace_variables( $this->subject ),
					'header' => $this->replace_variables( $this->heading ),
					'body' => $message,
					'footer' => $this->footer ),
				'form' => array( 'header' => $this->replace_variables( $this->form_header ),
					'description' => $this->replace_variables( $this->form_body ),
					'commentRequired' => $comment_required,
					'allowMedia' => $allowMedia,
				 	'shopRating' => $shop_rating ),
				'colors' => array(
					'form' => array(
						'bg' => get_option( 'ivole_form_color_bg', '#0f9d58' ),
						'text' => get_option( 'ivole_form_color_text', '#ffffff' )
					),
					'email' => array(
						'bg' => get_option( 'ivole_email_color_bg', '#0f9d58' ),
						'text' => get_option( 'ivole_email_color_text', '#ffffff' )
					)
				),
				'language' => $this->language
			);
			$api_url = 'https://z4jhozi8lc.execute-api.us-east-1.amazonaws.com/v1/test-email';
		}
		$license = get_option( 'ivole_license_key', '' );
		if( strlen( $license ) > 0 ) {
			$data['licenseKey'] = $license;
		}
		$data_string = json_encode( $data );
		//error_log( $data_string );
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $api_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen( $data_string ) )
		);
		$result = curl_exec( $ch );
		if( false === $result ) {
			return array( 2, curl_error( $ch ) );
		}
		//error_log( $result );
		$result = json_decode( $result );
		if( isset( $result->status ) && $result->status === 'OK' ) {
			//update count of review reminders sent in order meta
			if( $order_id ) {
				$count = get_post_meta( $order_id, '_ivole_review_reminder', true );
				$new_count = 0;
				if( '' === $count ) {
					$new_count = 1;
				} else {
					$count = intval( $count );
					$new_count = $count + 1;
				}
				update_post_meta( $order_id, '_ivole_review_reminder', $new_count );
			}
			return 0;
		} else {
			//error_log( print_r( $result, true) );
			return 1;
		}
	}

	/**
	 * Get content
	 *
	 * @access public
	 * @return string
	 */
	public function get_content() {
		ob_start();
		//$email_heading = $this->heading;
		$def_body = Ivole_Email::$default_body;
		$lang = $this->language;
		include( $this->template_html );
		return ob_get_clean();
	}

	public static function plugin_path() {
    return untrailingslashit( plugin_dir_path( __FILE__ ) );
  }

	public function get_from_address() {
		$from_address = apply_filters( 'woocommerce_email_from_address', get_option( 'woocommerce_email_from_address' ), $this );
		return sanitize_email( $from_address );
	}

	public function get_from_name() {
		$from_name = apply_filters( 'woocommerce_email_from_name', get_option( 'woocommerce_email_from_name' ), $this );
		return wp_specialchars_decode( esc_html( $from_name ), ENT_QUOTES );
	}

	public function get_content_type() {
		return 'text/html';
	}

	public function replace_variables( $input ) {
		return str_replace( $this->find, $this->replace, __( $input ) );
	}

	public static function get_blogname() {
		return wp_specialchars_decode( get_option( 'ivole_shop_name', get_option( 'blogname' ) ), ENT_QUOTES );
	}

	public static function get_blogurl() {
		$temp = get_option( 'home' );
		$disallowed = array('http://', 'https://');
		foreach($disallowed as $d) {
      if(strpos($temp, $d) === 0) {
         return str_replace($d, '', $temp);
      }
   }
   return $temp;
	}

	public static function get_blogdomain() {
		$temp = get_option( 'home' );
		$temp = parse_url( $temp, PHP_URL_HOST );
		//error_log( print_r( $temp, true ) );
		if( !$temp ) {
			//error_log( 'AA' );
			$temp = '';
		}
		return $temp;
	}

	public static function get_order_items2( $order ) {
		$items_return = array();
		$enabled_for = get_option( 'ivole_enable_for', 'all' );
		$enabled_categories = get_option( 'ivole_enabled_categories', array() );
		$items = $order->get_items();
		// check if taxes should be included in line items prices
		$tax_display = get_option( 'woocommerce_tax_display_cart' );
		$inc_tax = false;
		if ( 'excl' == $tax_display ) {
			$inc_tax = false;
		} else {
			$inc_tax = true;
		}
		//error_log( 'items' );
		//error_log( print_r( $items, true) );
		foreach ( $items as $item_id => $item ) {
			// check if an item needs to be skipped because none of categories it belongs to has been enabled for reminders
			if( $enabled_for === 'categories' ) {
				$skip = true;
				$categories = get_the_terms( $item['product_id'], 'product_cat' );
				foreach ( $categories as $category_id => $category ) {
					if( in_array( $category->term_id, $enabled_categories ) ) {
						$skip = false;
						break;
					}
				}
				if( $skip ) {
					continue;
				}
			}
			if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
				//create WC_Product to use its function for getting name of the product
				$prod_temp = new WC_Product( $item['product_id'] );
				$image = wp_get_attachment_image_url( $prod_temp->get_image_id(), 'full', false );
				if( !$image ) {
					$image = '';
				}
				$q_name = $prod_temp->get_title();

				//qTranslate integration
				$ivole_language = get_option( 'ivole_language' );
				if( function_exists( 'qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage' ) && $ivole_language === 'QQ' ) {
					$q_name = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage( $q_name );
				}

				//WPML integration
				if ( has_filter( 'translate_object_id' ) && $ivole_language === 'WPML' ) {
					$wpml_current_language = get_post_meta( $order->get_id(), 'wpml_language', true );
					$translated_product_id = apply_filters( 'translate_object_id', $item['product_id'], 'product', true, $wpml_current_language );
					$q_name = get_the_title( $translated_product_id );
				}

				$q_name = strip_tags( $q_name );

				//check if we have several variations of the same product in our order
				//review requests should be sent only once per each product
				$same_product_exists = false;
				for($i = 0; $i < sizeof( $items_return ); $i++ ) {
					if( isset( $items_return[$i]['id'] ) && $item['product_id'] === $items_return[$i]['id'] ) {
						$same_product_exists = true;
						$items_return[$i]['price'] += $order->get_line_subtotal( $item, $inc_tax );
					}
				}
				if( !$same_product_exists ) {
					$items_return[] = array( 'id' => $item['product_id'], 'name' => $q_name, 'price' => $order->get_line_subtotal( $item, $inc_tax ),
				  'image' => $image );
				}
			}
		}
		//error_log( 'items_return' );
		//error_log( print_r( $items_return, true) );
		return $items_return;
	}
}

endif;