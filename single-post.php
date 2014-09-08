<?php
/**
 * Single post page for displaying detailed about
 * the selected post. 
 */
get_header();
?>
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
                            ?>
                            <!--Start Post-->
                            <div class="post">         
                                <div class="post_content">
                                    <h1 class="post_title">
                                        <?php the_title(); ?>
                                    </h1>
                                    <?php the_content(); ?>
                                    <ul class="post_meta">
                                        <li class="estimate"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . AGO; ?></li>
                                        <li class="cate"><?php the_category(', '); ?></li>
                                        <li class="author"><a href="#"><?php comments_popup_link('0 Comments.', '1 Comment.', '% Comments.'); ?></a></li>
                                    </ul>
                                </div>
                            </div>
                            <!--End Post-->
                            <?php
                        endwhile;
                    else:
                        ?>
                        <div class="post">
                            <p><?php echo NO_POST_FOUND; ?></p>
                        </div>
                    <?php
                    endif;
                    ?>
                    <nav id="nav-single"> <span class="nav-previous">
                            <?php previous_post_link('%link', __('<span class="meta-nav">&larr;</span> Previous Post ', THEME_SLUG)); ?>
                        </span> <span class="nav-next">
                            <?php next_post_link('%link', __('Next Post <span class="meta-nav">&rarr;</span>', THEME_SLUG)); ?>
                        </span> </nav>
                    <?php comments_template(); ?>       
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