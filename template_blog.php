<?php
/**
 * Template Name: Template Blog
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
                    $limit = get_option('posts_per_page');
                    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    query_posts("post_type=post&showposts=$limit&paged=$paged");
                    $wp_query->is_archive = true;
                    $wp_query->is_home = false;
                    ?>
                    <?php get_template_part('loop', 'blog'); ?>
                    <?php cc_pagination(); ?>
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
<?php get_footer(); ?>