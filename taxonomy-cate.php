<?php
/**
 * 
 */
?>
<?php get_header(); ?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("a.switcher").bind("click", function(e) {
			e.preventDefault();

			var theid = $(this).attr("id");
			var theproducts = $("ul#products");
			var classNames = $(this).attr('class').split(' ');
			var postmeta = $(".post_meta");
			var text = $(".contents p");
			var gridthumb = "images/products/grid-default-thumb.png";
			var listthumb = "images/products/list-default-thumb.png";

			if ($(this).hasClass("active")) {
				// if currently clicked button has the active class
				// then we do nothing!
				return false;
			} else {
				// otherwise we are clicking on the inactive button
				// and in the process of switching views!

				if (theid == "gridview") {
					$(this).addClass("active");
					$("#listview").removeClass("active");

					$("#listview").children("img").attr("src", "images/list-view.png");

					var theimg = $(this).children("img");
					theimg.attr("src", "images/grid-view-active.png");

					// remove the list class and change to grid
					theproducts.removeClass("list");
					theproducts.addClass("grid");
					postmeta.hide();
					text.hide();
					// update all thumbnails to larger size
					//$("img.thumb").attr("src",gridthumb);
				}

				else if (theid == "listview") {
					$(this).addClass("active");
					$("#gridview").removeClass("active");

					$("#gridview").children("img").attr("src", "images/grid-view.png");

					var theimg = $(this).children("img");
					theimg.attr("src", "images/list-view-active.png");
					postmeta.show();
					text.show();
					// remove the grid view and change to list
					theproducts.removeClass("grid")
					theproducts.addClass("list");
					// update all thumbnails to smaller size
					//$("img.thumb").attr("src",listthumb);
				}
			}

		});
	});
	//Crousel
	function mycarousel_initCallback(carousel)
	{
		// Disable autoscrolling if the user clicks the prev or next button.
		carousel.buttonNext.bind('click', function() {
			carousel.startAuto(0);
		});

		carousel.buttonPrev.bind('click', function() {
			carousel.startAuto(0);
		});

		// Pause autoscrolling if the user moves with the cursor over the clip.
		carousel.clip.hover(function() {
			carousel.stopAuto();
		}, function() {
			carousel.startAuto();
		});
	}
	;
	jQuery(document).ready(function() {
		jQuery('#cc_carousel').jcarousel({
			wrap: 'circular',
			scroll: 1,
			auto: 1,
			initCallback: mycarousel_initCallback
		});
	});
</script>
<!--Start Cotent Wrapper-->
<div class="content_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <div id="slider_wrapper">                      
				<?php
				$wp_query->query_vars[CUSTOM_CAT_TYPE];
				foreach ( $wp_query as $query ) {
					$current_cat_arr[] = $query->slug;
				}
				$current_cat_val = array_filter( $current_cat_arr );
				$current_id = current( $current_cat_val );
				$limit = get_option( 'posts_per_page' );
				$taxonomy = CUSTOM_CAT_TYPE;
				query_posts( array(
					'post_type' => POST_TYPE,
					'showposts' => 500,
					$taxonomy => $current_id,
					'meta_query' => array(
						array(
							'key' => 'cc_f_checkbox2',
							'value' => 'on',
							'compare' => 'NOT NULL',
						)
					)
				) );
				if ( have_posts() ) :
					?>
					<ul id="cc_carousel" class="jcarousel-skin-tango">                       
						<?php
						while ( have_posts() ) {
							the_post();
							$postimg = get_post_meta( $post->ID, 'cc_image1', true );
							?>
							<li class="crousel_thumb" data-title="<?php
							the_title();
							printf( ".\n\n &#013; &#xA; Price:" );
							if ( get_post_meta( $post->ID, 'cc_price', true ) !== '' ) {
								?>
								<?php
								if ( cc_get_option( 'cc_currency' ) != '' ) {
									echo cc_get_option( 'cc_currency' );
								} else {
									echo get_option( 'currency_symbol' );
								}
								echo get_post_meta( $post->ID, 'cc_price', true );
								?>
								<?php } ?> "> 
									<?php if ( (function_exists( 'has_post_thumbnail' )) && (has_post_thumbnail()) ) { ?>
										<?php cc_get_thumbnail( 68, 68 ); ?>
									<?php } else { ?>
										<?php cc_get_image( 68, 68, '', $postimg ); ?>
										<?php
									}
									?>
							</li> 
							<?php
						}
						?>
					</ul> 
				<?php
				endif;
				wp_reset_query();
				?>                   
            </div> 
            <div class="clear"></div>
            <div class="grid_17 alpha">
                <!--Start Cotent-->
                <div class="content">          
                    <!--Start featured_item-->
                    <div class="featured_item">
                        <div id="wrap">
                            <header>
                                <span class="list-style-buttons">
                                    <a href="#" id="gridview" class="switcher"><?php echo GIRD_VIEW; ?></a>
                                    <a href="#" id="listview" class="switcher active"><?php echo LIST_VIEW; ?></a>
                                </span>
                                <ul id="tabs">
                                    <li><a href="#" title="recent"><?php echo RECENTLY_LISTED; ?></a></li>
                                    <li><a href="#" title="featured"><?php echo FEATURED_ADS; ?></a></li>
                                    <li><a href="#" title="popular"><?php echo POPULAR_ADS; ?></a></li>                                         
                                </ul>
                                <div class="clear"></div>
                            </header>
                            <div class="clear"></div>
                            <div id="content">                                                                       
                                <div id="recent">
									<?php
									if ( have_posts() ) :
										?> 
										<ul id="products" class="list clearfix">
											<?php
											while ( have_posts() ): the_post();
												$postimg = get_post_meta( $post->ID, 'cc_image1', true );
												?>
												<!-- row 1 -->
												<li class="thumbnail">
													<section class="thumbs">
														<?php if ( (function_exists( 'has_post_thumbnail' )) && (has_post_thumbnail()) ) { ?>
															<?php cc_get_thumbnail( 78, 78 ); ?>                    
														<?php } else { ?>
															<?php cc_get_image( 78, 78, '', $postimg ); ?> 
															<?php
														}
														$taxonomies = get_the_term_list( $post->ID, CUSTOM_CAT_TYPE, '', ',', '' );
														?>
														<section class="thumb_item">
																<?php if ( get_post_meta( $post->ID, 'cc_price', true ) !== '' ) { ?>
																<span class="price"><?php
																	if ( cc_get_option( 'cc_currency' ) != '' ) {
																		echo cc_get_option( 'cc_currency' );
																	} else {
																		echo get_option( 'currency_symbol' );
																	}
																	echo get_post_meta( $post->ID, 'cc_price', true );
																	?></span>
		<?php } ?>
															<a class="view" href="<?php the_permalink(); ?>"><?php echo VIEW_IT; ?></a>
														</section>
													</section>
													<section class="contents">
														<h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
																<?php
																$max_length = 50;
																$title = get_the_title( $post->ID );
																echo substr( $title, 0, $max_length );
																if ( strlen( $title ) > $max_length )
																	echo "...";
																?></a></h6>
		<?php the_excerpt(); ?>     
														<div class="clear"></div>
														<ul class="post_meta">
															<li class="estimate"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . HACE; ?></li>
															<li class="cate"><?php echo $taxonomies; ?></li>
															<li class="author"><?php echo BY; ?>&nbsp;<?php the_author_posts_link(); ?></li>
														</ul>

													</section>
												</li>
												<!-- row 1 -->   
												<?php
											endwhile;
											?>
										</ul>   
										<?php
										cc_pagination();
									endif;
									?> 
                                </div>                                                            
                                <div id="featured">
									<?php
									$wp_query->query_vars[CUSTOM_CAT_TYPE];

									foreach ( $wp_query as $query ) {
										$current_cat_arr[] = $query->slug;
									}
									$current_cat_val = array_filter( $current_cat_arr );
									$current_id = current( $current_cat_val );
									$limit = get_option( 'posts_per_page' );
									$taxonomy = CUSTOM_CAT_TYPE;
									query_posts( array(
										'post_type' => POST_TYPE,
										'showposts' => $limit,
										$taxonomy => $current_id,
										'meta_query' => array(
											array(
												'key' => 'cc_add_type',
												'value' => 'pro',
												'compare' => 'NOT NULL',
											)
										)
									) );
									if ( have_posts() ) :
										?>
										<ul id="products" class="list clearfix">
											<?php
											while ( have_posts() ): the_post();
												$postimg = get_post_meta( $post->ID, 'cc_image1', true );
												?>
												<!-- row 1 -->
												<li class="thumbnail">
													<section class="thumbs">
														<?php if ( (function_exists( 'has_post_thumbnail' )) && (has_post_thumbnail()) ) { ?>
															<?php cc_get_thumbnail( 78, 78 ); ?>                    
														<?php } else { ?>
															<?php cc_get_image( 78, 78, '', $postimg ); ?> 
															<?php
														}
														$taxonomies = get_the_term_list( $post->ID, CUSTOM_CAT_TYPE, '', ',', '' );
														?>
														<section class="thumb_item">
																<?php if ( get_post_meta( $post->ID, 'cc_price', true ) !== '' ) { ?>
																<span class="price"><?php
																	echo get_option( 'currency_symbol' );
																	echo get_post_meta( $post->ID, 'cc_price', true );
																	?></span>
		<?php } ?>
															<a class="view" href="<?php the_permalink(); ?>"><?php echo VIEW_IT; ?></a>
														</section>
													</section>
													<section class="contents">
														<h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
																<?php
																$max_length = 50;
																$title = get_the_title( $post->ID );
																echo substr( $title, 0, $max_length );
																if ( strlen( $title ) > $max_length )
																	echo "...";
																?>
															</a></h6>
		<?php the_excerpt(); ?>
														<ul class="post_meta">
															<li class="estimate"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . HACE; ?></li>
															<li class="cate"><?php echo $taxonomies; ?></li>
															<li class="author"><?php echo BY; ?>&nbsp;<?php the_author_posts_link(); ?></li>
														</ul>

													</section>
												</li>
												<!-- row 1 -->   
												<?php
											endwhile;
											?>                                     
										</ul>  
										<?php
										cc_pagination();
									endif;
									wp_reset_query();
									?>  
                                </div>                                
                                <div id="popular">
									<?php
									query_posts( array(
										'post_type' => POST_TYPE,
										'showposts' => $limit,
										$taxonomy => $current_id,
										'orderby' => 'comment_count'
									) );
									if ( have_posts() ) :
										?>
										<ul id="products" class="list clearfix">
											<?php
											while ( have_posts() ): the_post();
												$postimg = get_post_meta( $post->ID, 'cc_image1', true );
												?>
												<!-- row 1 -->
												<li class="thumbnail">
													<section class="thumbs">
														<?php if ( (function_exists( 'has_post_thumbnail' )) && (has_post_thumbnail()) ) { ?>
															<?php cc_get_thumbnail( 78, 78 ); ?>                    
														<?php } else { ?>
															<?php cc_get_image( 78, 78, '', $postimg ); ?> 
															<?php
														}
														$taxonomies = get_the_term_list( $post->ID, CUSTOM_CAT_TYPE, '', ',', '' );
														?>
														<section class="thumb_item">
																<?php if ( get_post_meta( $post->ID, 'cc_price', true ) !== '' ) { ?>
																<span class="price"><?php
																	echo get_option( 'currency_symbol' );
																	echo get_post_meta( $post->ID, 'cc_price', true );
																	?></span>
		<?php } ?>
															<a class="view" href="<?php the_permalink(); ?>"><?php echo VIEW_IT; ?></a>
														</section>
													</section>
													<section class="contents">
														<h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
																<?php
																$max_length = 50;
																$title = get_the_title( $post->ID );
																echo substr( $title, 0, $max_length );
																if ( strlen( $title ) > $max_length )
																	echo "...";
																?>
															</a></h6>
		<?php the_excerpt(); ?>
														<div class="clear"></div>
														<ul class="post_meta">
															<li class="estimate"><?php echo human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ) . ' ' . HACE; ?></li>
															<li class="cate"><?php echo $taxonomies; ?></li>
															<li class="author"><?php echo BY; ?>&nbsp;<?php the_author_posts_link(); ?></li>
														</ul>

													</section>
												</li>
												<!-- row 1 -->   
												<?php
											endwhile;
											?>                                     
										</ul>  
										<?php
										cc_pagination();
									endif;
									wp_reset_query();
									?>   
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                    </div>
                    <!--End featured_item-->               
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