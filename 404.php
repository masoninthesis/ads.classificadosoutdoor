<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
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
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <?php echo NTFND_TXT; ?>
                        </h1>
                    </header>
                    <p>
                    <?php echo NTFND_TXT_DES; ?>
                    </p>
                    <?php get_search_form(); ?>
                        <?php the_widget('WP_Widget_Recent_Posts', array('number' => 10), array('widget_id' => '404')); ?>
                    <div class="widget">
                        <h2 class="widgettitle">
                        <?php echo MST_USD_CAT; ?>
                        </h2>
                        <ul>
                        <?php wp_list_categories(array('orderby' => 'count', 'order' => 'DESC', 'show_count' => 1, 'title_li' => '', 'number' => 10)); ?>
                        </ul>
                    </div>
                    <?php
                    /* translators: %1$s: smilie */
                    $archive_content = '<p>' . sprintf(__(TRY_LK_ARCH . ' %1$s', THEME_SLUG), convert_smilies(':)')) . '</p>';
                    the_widget('WP_Widget_Archives', array('count' => 0, 'dropdown' => 1), array('after_title' => '</h2>' . $archive_content));
                    ?>
                    <?php the_widget('WP_Widget_Tag_Cloud'); ?>
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