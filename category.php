<?php
/**
 * The template for displaying Category pages.
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
                    <h1 class="page_title"><?php printf(CAT_ARC.__(' %s', THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
                        <?php
                $category_description = category_description();
                if (!empty($category_description))
                    echo '' . $category_description . '';
                /* Run the loop for the category page to output the posts.
                 * If you want to overload this in a child theme then include a file
                 * called loop-category.php and that will be used instead.
                 */
                ?>
                <?php get_template_part('loop-blog', 'category'); ?>
                <div class="clear"></div>
                <?php cc_pagination(); ?>
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