<?php
/**
 * Template Name: Template Search 
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
                    $s_for = $_REQUEST['search_for'];
                    $s_cat = $_REQUEST['select_cat'];
                    $s_to = $_REQUEST['search_loc'];
                    $result_query = cc_custom_search($s_for, $s_cat, $s_to);
                    if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
                        $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
                    } else { // If the pn URL variable is not present force it to be value of page number 1
                        $pn = 1;
                    }

                    $itemsPerPage = get_option('posts_per_page');

                    $lastPage = ceil($result_query['query'] / $itemsPerPage);

                    if ($pn < 1) { // If it is less than 1
                        $pn = 1; // force if to be 1
                    } else if ($pn > $lastPage) { // if it is greater than $lastpage
                        $pn = $lastPage; // force it to be $lastpage's value
                    }

                    $centerPages = "";
                    $sub1 = $pn - 1;
                    $sub2 = $pn - 2;
                    $add1 = $pn + 1;
                    $add2 = $pn + 2;
                    if ($pn == 1) {
                        $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                        $centerPages .= '<li> <a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$add1&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $add1 . '</a></li>';
                    } else if ($pn == $lastPage) {
                        $centerPages .= '<li> <a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$sub1&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $sub1 . '</a></li>';
                        $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                    } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                        $centerPages .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$sub2&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $sub2 . '</a></li>';
                        $centerPages .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$sub1&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $sub1 . '</a></li>';
                        $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                        $centerPages .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$add2&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $add1 . '</a></li>';
                        $centerPages .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$add2&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $add2 . '</a></li>';
                    } else if ($pn > 1 && $pn < $lastPage) {
                        $centerPages .= '<li> <a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$sub1&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $sub1 . '</a> </li>';
                        $centerPages .= '<li><a class="current" href="">' . $pn . '</a></li>';
                        $centerPages .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$add1&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '">' . $add1 . '</a></li>';
                    }

                    $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;
                    $paginationDisplay = "<ul class='paginate'>"; // Initialize the pagination output variable
                    if ($lastPage != "1") {
                        //$paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                        if ($pn != 1) {
                            $previous = $pn - 1;
                            $paginationDisplay .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$previous&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '"> Back</a></li>';
                        }
                        $paginationDisplay .= $centerPages;
                        if ($pn != $lastPage) {
                            $nextPage = $pn + 1;
                            $paginationDisplay .= '<li><a href="' . site_url(CC_SEARCH . "/?action=lead&pn=$nextPage&search_for=$s_for&select_cat=$s_cat&search_loc=$s_to") . '"> Next</a></li> ';
                        }
                    }
                    $paginationDisplay .= '</ul>';

                    $classifields = cc_custom_search($s_for, $s_cat, $s_to, $limit);
                    ?>
                    <ul id="products" class="list clearfix">
                        <?php
                        if ($classifields['result']) {
                            foreach ($classifields['result'] as $cfield):
                                           query_posts(array(
                                                'post_type' => POST_TYPE,
                                                'showposts' => $limit,
                                            ));
                                            if (have_posts()) :
                                                $count = 1;
                                                while (have_posts()): the_post();
                                                    $postimg = get_post_meta($cfield->ID, 'cc_image1', true);
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
                                                            $taxonomies = get_the_term_list($cfield->ID, CUSTOM_CAT_TYPE, '', ',', '');
                                                            ?>
                                        <section class="thumb_item">
                                            <?php if (get_post_meta($cfield->ID, 'cc_price', true)) { ?>
                                                <span class="price"><?php
                                                if (cc_get_option('cc_currency') != '') {
                                                    echo cc_get_option('cc_currency');
                                                } else {
                                                    echo get_option('currency_symbol');
                                                }
                                                echo get_post_meta($cfield->ID, 'cc_price', true);
                                                ?></span>
                                            <?php } ?>
                                            <a class="view" href="<?php echo get_permalink($cfield->ID); ?>">View it!</a>
                                        </section>
										<?php  endwhile;
                                            endif;
                                            wp_reset_query(); ?>
                                    </section>
                                    <section class="contents">
                                        <h6 class="title"><a href="<?php echo get_permalink($cfield->ID); ?>" rel="bookmark" ><?php echo get_the_title($cfield->ID); ?></a></h6>
                                        <?php
                                        $excerpt = preg_replace("/<img[^>]+\>/i", "", $cfield->post_content);
										$excerpt = preg_replace('#<table[^>]*?>#', '', $excerpt);
										$excerpt = preg_replace('#<tr[^>]*?>#', '', $excerpt);
										$excerpt = preg_replace('#</tr[^>]*?>#', '', $excerpt);
										//td
										$excerpt = preg_replace('#<td[^>]*?>#', '', $excerpt);
										$excerpt = preg_replace('#</td[^>]*?>#', '', $excerpt);
										//th
										$excerpt = preg_replace('#<th[^>]*?>#', '', $excerpt);
										$excerpt = preg_replace('#</th[^>]*?>#', '', $excerpt);
                                        $excerpt = substr($excerpt, 0, 120);
                                        printf("%s", $excerpt);
                                        if (strlen($excerpt) > 120)
                                            echo "[...]";
                                        $user_info = get_userdata($cfield->post_author);
                                        ?>
                                        <ul class="post_meta">
                                            <li class="estimate"><?php echo human_time_diff(get_the_time('U', $cfield->ID), current_time('timestamp')) . ' ago'; ?></li>
                                            <li class="cate"><?php echo $taxonomies; ?></li>
                                            <li class="author">by&nbsp;<a href="<?php echo get_author_posts_url($cfield->post_author); ?>"><?php echo $user_info->user_login; ?></a></li>
                                        </ul>
                                    </section>
                                </li>
                                <!-- row 1 -->   
                                <?php
                            endforeach;
                        }else {
                            echo "Your search query not found";
                        }
                        ?>
                    </ul>
                    <div class="paging"><span style="float:right;"><?php echo $paginationDisplay; ?></span></div>
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