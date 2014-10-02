<?php 
/**
 * The template used to display Tag Archive pages
 *
 * @package WordPress
 * 
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
                        <h1><?php printf(__(TAG_ARC . ' %s', THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
                        <?php get_template_part('loop', 'blog'); ?>
                        <?php /* Display navigation to next/previous pages when applicable */ ?>
                        <?php global $wp_query;
                        if ($wp_query->max_num_pages > 1) : ?>
                            <?php cc_pagination(); ?>
                        <?php endif; ?>
                    <?php endif; ?>
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