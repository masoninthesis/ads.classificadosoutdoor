<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
              <?php
            /* Queue the first post, that way we know
             * what date we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop
             * properly with a call to rewind_posts().
             */
            if (have_posts())
                the_post();
            ?>
            <h1>
                <?php if (is_day()) : ?>
                    <?php printf(__(DLY_ARC . ' %s', THEME_SLUG), get_the_date()); ?>
                <?php elseif (is_month()) : ?>
                    <?php printf(__(MTHL_ARC . ' %s', THEME_SLUG), get_the_date('F Y')); ?>
                <?php elseif (is_year()) : ?>
                    <?php printf(__(YRL_ARC . ' %s', THEME_SLUG), get_the_date('Y')); ?>
                <?php else : ?>                  
                    <?php _e( BLG_ARC, THEME_SLUG); ?>                   
                <?php endif; ?>
            </h1>
            <?php
            /* Since we called the_post() above, we need to
             * rewind the loop back to the beginning that way
             * we can run the loop properly, in full.
             */
            rewind_posts();
            /* Run the loop for the archives page to output the posts.
             * If you want to overload this in a child theme then include a file
             * called loop-archives.php and that will be used instead.
             */

                get_template_part('loop', 'blog');          
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
