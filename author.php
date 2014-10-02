<?php
/**
 * The template for displaying Author Archive pages.
 *
 * 
 */
if (!isset($_REQUEST['rtype']) && $_REQUEST['rtype'] == '') {
    //This sets the $curauth variable
    if (isset($_GET['author_name'])) :
        $curauth = get_user_by('login', $author_name);
    else :
        $curauth = get_userdata(intval($author));
    endif;
    ?>
    <?php get_header(); ?>
    <!--Start Cotent Wrapper-->
    <div class="content_wrapper">
        <div class="container_24">
            <div class="grid_24">
                <div class="grid_17 alpha">
                    <!--Start Cotent-->
                    <div class="content">
                        <?php if (have_posts()) : the_post(); ?>
                            <h1><?php printf(__(ATHR_ARC . ' %s', THEME_SLUG), "<a class='url fn n' href='" . get_author_posts_url(get_the_author_meta('ID')) . "' title='" . esc_attr(get_the_author()) . "' rel='me'>" . get_the_author() . "</a>"); ?></h1>
                            <?php
                            // If a user has filled out their description, show a bio on their entries.
                            if (get_the_author_meta('description')) :
                                ?>
                                <div id="author-info">
                                    <div class="author-inner">
                                        <div id="author-avatar"> <?php echo get_avatar(get_the_author_meta('user_email'), apply_filters('cc_avatar_size', 50)); ?> </div>
                                        <!-- #author-avatar -->
                                        <div id="author-description">
                                            <h2><?php printf(__(ABT . ' %s', THEME_SLUG), get_the_author()); ?></h2>
                                            <?php the_author_meta('description'); ?>
                                        </div>
                                        <!-- #author-description	-->
                                    </div>
                                </div>
                                <!-- #entry-author-info -->
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php
                        $limit = get_option('posts_per_page');
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        query_posts(array(
                            'post_type' => POST_TYPE,
                            'showposts' => $limit,
                            'paged' => $paged,
                            'author' => $curauth->ID
                        ));
                        if (have_posts()) :
                            ?>
                            <ul id="products" class="list clearfix">
                                <?php
                                while (have_posts()): the_post();
                                    $postimg = get_post_meta($post->ID, 'cc_image1', true);
                                    ?>
                                    <!-- row 1 -->
                                    <li class="thumbnail">
                                        <section class="thumbs">
                                            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                                <?php cc_get_thumbnail(78, 78); ?>                    
                                            <?php } else { ?>
                                                <?php cc_get_image(78, 78, '', $postimg); ?> 
                                                <?php
                                            }
                                            $taxonomies = get_the_term_list($post->ID, CUSTOM_CAT_TYPE, '', ',', '');
                                            ?>
                                            <section class="thumb_item">
                                                <?php if (get_post_meta($post->ID, 'cc_price', true) !== '') { ?>
                                                    <span class="price"><?php
                                                    if (cc_get_option('cc_currency') != '') {
                                                        echo cc_get_option('cc_currency');
                                                    } else {
                                                        echo get_option('currency_symbol');
                                                    }
                                                    echo get_post_meta($post->ID, 'cc_price', true);
                                                    ?></span>
                                                    <?php } ?>
                                                <a class="view" href="<?php the_permalink(); ?>"><?php echo VIEW_IT; ?></a>
                                            </section>
                                        </section>
                                        <section class="contents">
                                            <h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h6>
                                            <?php the_excerpt(); ?>
                                            <div class="clear"></div>
                                            <ul class="post_meta">
                                                <li class="estimate"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' hace'; ?></li>
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
                        endif;
                        wp_reset_query();
                        cc_pagination();
                        ?>
                        <?php
                        $limit = get_option('posts_per_page');
                        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        query_posts(array(
                            'post_type' => 'post',
                            'showposts' => $limit,
                            'paged' => $paged,
                            'author' => $curauth->ID
                        ));
                        if (have_posts()) :
                            ?>
                            <ul id="products" class="list clearfix">
                                <?php
                                while (have_posts()): the_post();
                                    $postimg = get_post_meta($post->ID, 'cc_image1', true);
                                    ?>
                                    <!-- row 1 -->
                                    <li class="thumbnail">
                                        <section class="thumbs">
                                            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                                                <?php cc_get_thumbnail(78, 78); ?>                    
                                            <?php } else { ?>
                                                <?php cc_get_image(78, 78, '', $postimg); ?> 
                                                <?php
                                            }
                                            $taxonomies = get_the_term_list($post->ID, CUSTOM_CAT_TYPE, '', ',', '');
                                            ?>
                                            <section class="thumb_item">
                                                <?php if (get_post_meta($post->ID, 'cc_price', true) !== '') { ?>
                                                    <span class="price"><?php
                                                    if (cc_get_option('cc_currency') != '') {
                                                        echo cc_get_option('cc_currency');
                                                    } else {
                                                        echo get_option('currency_symbol');
                                                    }
                                                    echo get_post_meta($post->ID, 'cc_price', true);
                                                    ?></span>
                                                    <?php } ?>
                                                <a class="view" href="<?php the_permalink(); ?>"><?php echo VIEW_IT; ?></a>
                                            </section>
                                        </section>
                                        <section class="contents">
                                            <h6 class="title"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h6>
                                            <?php the_excerpt(); ?>
                                            <div class="clear"></div>
                                            <ul class="post_meta">
                                                <li class="estimate"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' hace'; ?></li>
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
                        endif;
                        wp_reset_query();
                        cc_pagination();
                        ?>
                        <div class="clear"></div>
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
    <?php get_footer();
}
?>