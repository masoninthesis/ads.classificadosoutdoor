<?php
@session_start();
/**
 * Template Name: Template Ad New
 */
### Prevent Caching
nocache_headers();
if ( !is_user_logged_in() )
	wp_redirect( site_url( '/wp-login.php?action=login' ) );

function cc_submit_form_process() {
	global $post, $posted;

	$fields = array(
		'cc_title',
		'cc_description',
		'cc_tags',
		'cc_category',
		'feature_h',
		'feature_c',
		//Payment require
		'f_period',
		'f_cycle',
		'installment',
		's_price',
		's_period',
		's_cycle',
		'billing',
	);
	//Fecth form values
	foreach ( $fields as $field ) {
		if ( isset( $_POST[$field] ) ) {
			$posted[$field] = ($_POST[$field]);
		}
	}
	//set category session
	if ( $_POST['cc_category'] ) {
		$_SESSION['category'] = $_POST['cc_category'];
	}
	if ( $_SESSION['category'] ) {
		$category = $_SESSION['category'];
	}
	$posted['cc_category'] = $category;
	$cc_custom_meta = cc_get_custom_field();
	foreach ( $cc_custom_meta as $key => $meta ) {
		$field = $meta['htmlvar_name'];
		if ( isset( $_POST[$field] ) ) {
			$posted[$field] = stripcslashes( trim( $_POST[$field] ) );
		}
	}

	$errors = new WP_Error();

	$submit_form_results = array(
		'errors' => $errors,
		'posted' => $posted
	);
	return $submit_form_results;
}

global $post, $posted, $user_ID;
$posted = array();
$errors = new WP_Error();
if ( !is_user_logged_in() )
	$step = 0;
else
	$step = 1;

$value = cc_submit_form_process();
$errors = $value['errors'];
$posted = $value['posted'];
$savedmsg = '';
if ( $errors && sizeof( $errors ) > 0 && $errors->get_error_code() ) {
	$step = 4;
} else {
	$check_submit = get_option( 'cc_check_submit' );
	if ( isset( $_REQUEST['cc_check_submit'] ) && $_REQUEST['cc_check_submit'] != $check_submit ) {
		if ( isset( $_POST['cc_submit'] ) && $_POST['cc_submit'] ) {

			//Approval needed
			if ( isset( $_REQUEST['pay_method'] ) && $_POST['total_price'] > 0 ) {
				$post_status = 'pending';
			} elseif ( !isset( $pay_method ) && $_POST['total_price'] == 0 ) {
				$status = strtolower( cc_get_option( 'cc_freead' ) );
				if ( $status == 'pending' ):
					$post_status = 'pending';
				endif;
				if ( $status == 'publish' ):
					$post_status = 'publish';
				endif;
				if ( !$status ) {
					$post_status = 'pending';
				}
			}


			## Create Post
			$posted = unserialize( base64_decode( $_POST['posted'] ) );
			$category = $posted['cc_category'];
			$tags = explode( ',', $posted['cc_tags'] );
			$cc_post_title = $wpdb->escape( unserialize( base64_decode( $posted['cc_title'] ) ) );
			$cc_my_post = array(
				'post_content' => $wpdb->escape( unserialize( base64_decode( $posted['cc_description'] ) ) ),
				'post_title' => $cc_post_title,
				'post_status' => $post_status,
				'post_author' => $user_ID,
				'post_type' => POST_TYPE,
				'tags_input' => $tags
			);
			$post_id = wp_insert_post( $cc_my_post );


			/**
			 * Admin email for get notified on user ad submit.
			 */
			$ad_url = get_permalink( $post_id, true );
			$ad_title = get_the_title( $post_id );
			$to = get_option( 'admin_email' );
			$subject = __( 'New Ad Submit', THEME_SLUG );
			$message = __( 'Dear Admin,', THEME_SLUG ) . "\r\n\r\n";
			$message .= __( 'New ad submited on your site.', THEME_SLUG ) . "\r\n\r\n";
			$message .= __( 'Ad Title: ' . $ad_title, THEME_SLUG ) . "\r\n\r\n";
			$message .= __( 'Ad url: ' . $ad_url, THEME_SLUG ) . "\r\n\r\n";
			$headers = 'From: ' . __( 'Admin', THEME_SLUG ) . ' <' . get_option( 'admin_email' ) . '>' . "\r\n";			
			wp_mail( $to, $subject, $message, $headers );
			
			// Check either post created or not
			if ( $post_id == 0 || is_wp_error( $post_id ) )
				wp_die( __( 'Error: Unable to create entry.', '' ) );
			wp_set_object_terms( $post_id, intval( $category ), $taxonomy = CUSTOM_CAT_TYPE );
			wp_set_object_terms( $post_id, $tags, $taxonomy = CUSTOM_TAG_TYPE );
			if ( $post_id > 0 ) {
				$savedmsg = SUBMITTED_MSG;
			}
			//unset session variables
			if ( isset( $_SESSION['category'] ) )
				unset( $_SESSION['category'] );
			//destroy the session data
			session_destroy();
			//Save custom field value
			$custom_meta = cc_get_custom_field();
			if ( $custom_meta ) {
				foreach ( $custom_meta as $meta ):
					if ( $posted[$meta['htmlvar_name']] == 'cc_street' ) {
						$street = $_POST['cc_street'];
					}
					if ( $posted[$meta['htmlvar_name']] == 'cc_city' ) {
						$city = $_POST['cc_city'];
					}
					if ( $posted[$meta['htmlvar_name']] == 'cc_zipcode' ) {
						$zipcode = $_POST['cc_zipcode'];
					}
					if ( $posted[$meta['htmlvar_name']] == 'cc_state' ) {
						$state = $_POST['cc_state'];
					}
					if ( $posted[$meta['htmlvar_name']] == 'cc_country' ) {
						$country = $_POST['cc_country'];
					}
					if ( $posted[$meta['htmlvar_name']] == 'cc_address' ) {
						if ( $_POST['cc_address'] == '' ) {
							$address = $street . ' ' . $city . ' ' . $zipcode . ' ' . $state . ' ' . $country;
							echo $address;
						} else {
							$address = $_POST['cc_address'];
						}
						add_post_meta( $post_id, 'cc_address', $address, true );
					} else {
						add_post_meta( $post_id, $meta['htmlvar_name'], $posted[$meta['htmlvar_name']], true );
					}
				endforeach;
			}
			//Save add type value           
			update_post_meta( $post_id, 'cc_add_type', 'free' );

			//Insert activation details in activation table            
			global $wpdb, $expiry_tbl_name;
			$validity = $_POST['package_validity'];
			$validity_per = $_POST['package_validity_per'];
			$pkg_type = $_POST['package_type'];
			if ( $pkg_type == '' ) {
				$pkg_type = 'pkg_free';
			}
			/*			 * $current_date = date("Y-m-d H:i:s");
			  $insert_array = array(
			  'pid' => $post_id,
			  'listing_title' => $cc_post_title,
			  'validity' => $validity,
			  'validity_per' => $validity_per,
			  'listing_date' => $current_date,
			  'package_type' => $pkg_type
			  );
			 * 
			 */
			//$wpdb->insert($expiry_tbl_name, $insert_array);
			//set expiry duration
			cc_set_expiry( $post_id, $pkg_type );
			//Add meta data
			$featured_home = '';
			$featured_cate = '';
			if ( $_POST['feature_h'] ) {
				$featured_home = 'on';
			}
			if ( $_POST['feature_c'] ) {
				$featured_cate = 'on';
			}
			$listing_meta = array(
				'cc_f_checkbox1' => esc_attr( $featured_home ),
				'cc_f_checkbox2' => esc_attr( $featured_cate )
			);
			if ( $listing_meta ) {
				foreach ( $listing_meta as $key => $meta ):
					add_post_meta( $post_id, $key, $meta, true );
				endforeach;
			}
			if ( isset( $_POST['cc_submit'] ) && isset( $_REQUEST['pay_method'] ) && $_REQUEST['pay_method'] != '' && $_POST['total_price'] > 0 ) {

				if ( file_exists( LIBRARYPATH . "getways/paypal/paypal.php" ) ):
					include_once(LIBRARYPATH . "getways/paypal/paypal.php");
					$savedmsg = '<h3>' . PROCESSING . '</h3>';
					$savedmsg .= '<img src="' . TEMPLATEURL . '/images/loading.gif"/>';
				endif;
			}
			//Validate to prevent duplicate submission
			update_option( 'cc_check_submit', $_REQUEST['cc_check_submit'] );
		}
	}
}
get_header();
?>
<!--Start Cotent Wrapper-->
<div class="content_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_17 alpha">
                <!--Start Cotent-->
                <div class="content">
                    <div id="add_post">
						<?php
						if ( is_user_logged_in() ) {
							wp_enqueue_script( 'cc_ajax_upload', LIBRARYURL . '/js/ajaxupload.js', array( 'jquery' ) );
							wp_enqueue_script( 'cc_tiny_mce', LIBRARYURL . '/js/tiny_mce/tiny_mce.js', array( 'jquery' ) );
							wp_enqueue_script( 'cc_tiny_mce_init', LIBRARYURL . '/js/tiny_mce_init.js', array( 'jquery' ) );
							if ( file_exists( LIBRARYPATH . 'js/image-upload.php' ) ) {
								include_once(LIBRARYPATH . 'js/image-upload.php');
							}
							?>                     

							<?php
							if ( !isset( $_POST['step1'] ) && !isset( $_POST['step2'] ) && !isset( $_POST['step3'] ) || isset( $_POST['step'] ) ) {
								echo '<h1>' . AD_FIELD . '</h1>';
								if ( file_exists( VIEWPATH . 'forms/step.php' ) ) {
									include_once(VIEWPATH . 'forms/step.php');
								}
							}

							if ( isset( $_POST['step1'] ) ) {
								echo '<h1>' . AD_FIELD . '</h1>';
								if ( file_exists( VIEWPATH . 'forms/step1.php' ) ) {
									include_once(VIEWPATH . 'forms/step1.php');
								}
							}

							if ( isset( $_POST['step2'] ) ) {
								if ( file_exists( VIEWPATH . 'forms/preview.php' ) ) {
									include_once(VIEWPATH . 'forms/preview.php');
								}
								if ( file_exists( VIEWPATH . 'forms/step2_package.php' ) ) {
									include_once(VIEWPATH . 'forms/step2_package.php');
								}
							}
							if ( $savedmsg ) {
								echo $savedmsg;
							}
						} else {
							wp_redirect( site_url( '/wp-login.php?action=login' ) );
						}
						?>
                    </div>

                </div>
                <!--End Cotent-->
            </div>
            <div class="grid_7 omega">
				<?php get_sidebar( 'ad' ); ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--End Cotent Wrapper-->
<?php get_footer(); ?>