<?php get_header(); ?>
<!--Start Cotent Wrapper-->
<div class="content_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_17 alpha">
                <!--Start Cotent-->
                <div class="content">
                    <?php
                    if (have_posts()) :
                        while (have_posts()): the_post();
                            $taxonomies = get_the_term_list($post->ID, CUSTOM_CAT_TYPE, '', ',', '');
                            ?>
                            <!--Start Post-->
                            <div class="post classi">           
                                <div class="post_content">
                                    <h1 class="post_title"><?php the_title(); ?></h1>
                                    <?php if (get_post_meta($post->ID, 'cc_price', true) !== '') { ?><span class="price_meta">
                                        <span class="price_left"></span><span class="price_center"><?php
                                    if (cc_get_option('cc_currency') != '') {
                                        echo cc_get_option('cc_currency');
                                    } else {
                                        echo get_option('currency_symbol');
                                    }
                                    echo get_post_meta($post->ID, 'cc_price', true);
                                    ?></span><span class="price_right"></span></span> <?php } ?>
                                    <ul class="post_meta">
                                        <li class="estimate"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . hace; ?></li>
                                        <li class="cate"><?php printf(__('In&nbsp;', THEME_SLUG) . '%s', $taxonomies); ?></li>
                                        <li class="author"><?php echo BY; ?>&nbsp;<?php the_author_posts_link(); ?></li>
                                    </ul>  

                                    <div class="meta_boxes">
                                        <div class="meta_slider">
                                            <div class="flexslider">
                                                <ul class="slides">
                                                    <?php
                                                    $cc_custom_meta = cc_get_custom_field();
                                                    foreach ($cc_custom_meta as $field) {
                                                        if ($field['type'] == 'image_uploader' && get_post_meta($post->ID, $field['htmlvar_name'], true) != '') {
                                                            ?>
                                                            <li> <img src="<?php echo get_post_meta($post->ID, $field['htmlvar_name'], true); ?>" /> </li>                                                  
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="meta_table">
                                            <table class="ar_desc">
                                                <?php
                                                $free_ad = get_post_meta($post->ID, 'cc_add_type', true);
                                                foreach ($cc_custom_meta as $field) {
                                                    if (($free_ad == "pro") || ($free_ad == "free" && $field['show_free'] == true)) {
                                                        if ($field['type'] != 'image_uploader' && $field['type'] != 'cc_map_input' && $field['type'] != 'cc_map' && $field['htmlvar_name'] != 'cc_price' && get_post_meta($post->ID, $field['htmlvar_name'], true)) {
                                                            ?>
                                                            <tr>
                                                                <td class="label"><?php echo $field['field_title']; ?></td>
                                                                <td><?php echo get_post_meta($post->ID, $field['htmlvar_name'], true); ?></td>
                                                            </tr>                              
                                                            <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                    <?php
                                    //for set the values to showing post views
                                    cc_setPostViews(get_the_ID());
                                    ?>
                                    <span class="views"><?php echo cc_getPostViews(get_the_ID()); ?></span>
                                </div>
                            </div>
                            <!--End Post-->   
                            <div class="clear"></div>
                             <nav id="nav-single"> <span class="nav-previous">
                            <?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span>Previous Ad', THEME_SLUG)); ?>
                        </span> <span class="nav-next">
                            <?php next_post_link('%link', __('Next Ad<span class="meta-nav">&rarr;</span>', THEME_SLUG)); ?>
                        </span> </nav>
                            <div class="post_content">
                                <?php the_content(); ?>
                            </div>
                            <!--Start Comment box-->
                            <?php comments_template(); ?>
                            <!--End Comment box-->
                            <?php
                        endwhile;
                    endif;
                    ?>
                    <div class="clear"></div>                   
                </div>
                <!--End Cotent-->
            </div>
            <div class="grid_7 omega">
                <?php get_sidebar('ad'); ?>          
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--End Cotent Wrapper-->
<?php get_footer(); ?>