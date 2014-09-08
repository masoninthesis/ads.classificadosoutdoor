<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title>
			<?php
			/*
			 * Print the <title> tag based on what is being viewed.
			 */
			global $page, $paged;

			wp_title( '|', true, 'right' );

			// Add the blog name.
			bloginfo( 'name' );

			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && ( is_home() || is_front_page() ) )
				echo " | $site_description";

			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				echo ' | ' . sprintf( __( PAGE . ' %s', THEME_SLUG ), max( $paged, $page ) );
			?>
        </title>
		<?php
		if ( is_home() ) {
			if ( cc_get_option( 'cc_keyword' ) != '' ) {
				?>
				<meta name="keywords" content="<?php echo cc_get_option( 'cc_keyword' ); ?>" />
				<?php
			}
			?>
			<?php if ( cc_get_option( 'cc_description' ) != '' ) { ?>
				<meta name="description" content="<?php echo cc_get_option( 'cc_description' ); ?>" />
				<?php
			}
			?>
			<?php if ( cc_get_option( 'cc_author' ) != '' ) { ?>
				<meta name="author" content="<?php echo cc_get_option( 'cc_author' ); ?>" />
				<?php
			}
		}
		?>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
        <!--[if IE]>
        <script src="<?php echo TEMPLATEURL; ?>/js/html5shiv.js"></script>
        <![endif]-->
		<?php
		wp_head();
		?>
    </head>
    <body <?php body_class() ?>>
        <div class="wrapper">
            <div class="container_24">
                <div class="grid_24">
                    <div class="top_header">
                        <ul id="user_acces">
							<?php if ( !is_user_logged_in() ): ?>
								<li class="login"><a href="<?php echo site_url( 'wp-login.php?action=login' ); ?>"><?php echo LOGIN_HDR;?></a></li>
							<?php endif; ?>
							<?php if ( is_user_logged_in() ): ?>
								<li class="dashboard"><a href="<?php echo site_url( CC_DASHBOARD ); ?>"><?php echo MY_DASHBOARD_HDR;?></a></li>
								<li class="logout"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php echo LOGOUT_HDR;?></a></li>
							<?php endif; ?>
                        </ul>
                    </div>  
                    <div class="clear"></div>
                    <div class="header">
                        <div class="grid_16 alpha">
                            <div class="logo"> <a href="<?php echo home_url(); ?>"><img src="<?php if ( cc_get_option( 'cc_logo' ) != '' ) { ?><?php echo cc_get_option( 'cc_logo' ); ?><?php } else { ?><?php echo get_template_directory_uri(); ?>/images/logo.png<?php } ?>" alt="<?php bloginfo( 'name' ); ?>" /></a></div>
                        </div>
                        <div class="grid_8 omega">                            
                            <div class="post_btn">
                                <a class="post_add_btn" href="<?php echo get_bloginfo( 'url' ) . '/' . CC_ADNEW; ?>"><span class="btn_left"></span><span class="btn_center"><?php
										if ( cc_get_option( 'cc_adnew' ) != '' ) {
											echo cc_get_option( 'cc_adnew' );
										} else {
											echo AD_POST;
										}
										?></span><span class="btn_right"></span></a>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <!--Start Menu Wrapper -->
                        <div class="menu_wrapper">
							<?php cc_nav(); ?> 
                            <div class="clear"></div>
                        </div>
                        <!--End Menu Wrapper-->
                        <div class="clear"></div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <!--Start Search Wrapper-->
            <div class="search_wrapper">
                <div class="container_24">
                    <div class="grid_24">
						<?php if ( file_exists( TEMPLATEPATH . '/multiple_search.php' ) ) require_once(TEMPLATEPATH . '/multiple_search.php'); ?>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!--End Search Wrapper-->
            <div class="clear"></div>
			<?php if ( cc_get_option( 'inkthemes_header_ad' ) != '' ) { ?>
				<div class="container_24">
					<div class="grid_24">
						<div class="header-ad">
							<a href="<?php echo (cc_get_option( 'inkthemes_header_ad_url' )) ? cc_get_option( 'inkthemes_header_ad_url' ) : ''; ?>">
								<img src="<?php echo (cc_get_option( 'inkthemes_header_ad' )) ? cc_get_option( 'inkthemes_header_ad' ) : ''; ?>" alt="ad" title=""/>
							</a>
						</div>
					</div>
				</div>
			<?php } ?>