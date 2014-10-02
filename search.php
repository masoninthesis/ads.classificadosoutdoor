<?php
/**
 * Search from category 
 */
?>
<?php get_header(); ?>
<!--Start Cotent Wrapper-->
<div class="content_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_17 alpha">
                <!--Start Cotent-->
                <div class="content">
                    <?php if (have_posts()) : ?>
                        <h1><?php printf(U_SRC_FR, '' . get_search_query() . ''); ?></h1>
                        <ul id="products" class="list clearfix">
                            <?php
                            if (have_posts()) :
                                while (have_posts()): the_post();
                                    ?>
                                    <!-- row 1 -->
                                    <li class="thumbnail">
                                        <section class="thumbs">
                                            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                                <?php cc_get_thumbnail(78, 78); ?>                    
                                            <?php } else { ?>
                                                <?php cc_get_image(78, 78); ?> 
                                                <?php
                                            }
                                            $taxonomies = get_the_term_list($post->ID, CUSTOM_CAT_TYPE, '', ',', '');
                                            ?>
                                            <section class="thumb_item">
                                                <span class="price"><?php
                                            if (cc_get_option('cc_currency') != '') {
                                                echo cc_get_option('cc_currency');
                                            } else {
                                                echo get_option('currency_symbol');
                                            }
                                            if (get_post_meta($post->ID, 'cc_price', true))
                                                echo get_post_meta($post->ID, 'cc_price', true);
                                            ?></span>
                                                <a class="view" href="<?php the_permalink(); ?>"><?php echo VIEW_IT; ?></a>
                                            </section>
                                        </section>
                                        <section class="contents">
                                            <h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h6>
                                            <?php the_excerpt(); ?>
                                            <ul class="post_meta">
                                                <li class="estimate"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . AGO; ?></li>
                                                <li class="cate"><?php echo $taxonomies; ?></li>
                                                <li class="author"><?php echo BY; ?>&nbsp;<?php the_author_posts_link(); ?></li>
                                            </ul>

                                        </section>
                                    </li>
                                    <!-- row 1 -->   
                                    <?php
                                endwhile;
                            endif;
                            wp_reset_query();
                            ?>
                        </ul> 
                        <?php
                    endif;
                    ?>
                </div>
                <!--End Cotent-->
            </div>
            <div class="grid_7 omega">
                <?php get_sidebar(); ?>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--End Cotent Wrapper-->
<?php get_footer(); ?>