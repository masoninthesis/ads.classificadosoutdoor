<?php
if (have_posts()) :
    while (have_posts()): the_post();
        global $post;
        ?>
        <!--Start Post-->
        <div class="post">
            <?php if ((function_exists('has_post_thumbnail')) && (has_post_thumbnail())) { ?>
                <?php cc_get_thumbnail(128, 108, '', $img_meta); ?>                    
            <?php } else { ?>
                <?php cc_get_image(128, 108, '', $img_meta); ?> 
                <?php
            }
            ?>
            <div class="post_content">
                <h1 class="post_title">
                    <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                </h1>
                <?php the_excerpt(); ?>
                <div class="clear"></div>
                <ul class="post_meta">
                    <li class="estimate"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . ' Hace'; ?></li>
                    <li class="cate"><?php the_category(', '); ?></li>
                    <li class="author"><a href="#"><?php the_author_posts_link(); ?></a></li>
                </ul>
            </div>
        </div>
        <!--End Post-->

        <?php
    endwhile;
endif;
?>