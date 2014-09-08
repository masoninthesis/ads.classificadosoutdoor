<?php
//globlization
global $wpdb, $cc_leads_tbl_name, $current_user;
$update_msg = '';
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delete" && isset($_REQUEST['pid'])) {
    $id = $_REQUEST['pid'];
    //Deleting ad by requested id
    wp_delete_post($id);
    $update_msg = "An ad has been deleted";
}
?>
<?php if($update_msg){ ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<div class="box-head"><h2><?php echo UR_ADS; ?></h2></div>
<div class="box-content no-pad">
    <table class="display dataTable">
        <thead>                                   
            <tr>
                <th><?php echo AD_TTLE; ?></th>
                <th><?php echo VIEWS; ?></th>
                <th><?php echo STATUS; ?></th>
                <th><?php echo OPTIONS; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $user_id = get_current_user_id();
            $limit = get_option('posts_per_page');
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $post_type = POST_TYPE;
            query_posts(array("post_type" => $post_type, "posts_per_page" => $limit, "paged" => $paged, "author" => $user_id, 'post_status' => 'publish, pending, draft',));
            $count = 1;
            if (have_posts()) :
                while (have_posts()): the_post();
                    global $post;
                    ?>
                    <tr class="<?php if ($count % 2 == 0) echo "even"; else echo "odd"; ?>">                                                
                        <td>
                            <?php if ($post->post_status == 'pending' || $post->post_status == 'draft') { ?>
                                <?php the_title(); ?>
                            <?php } else { ?>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>  
                            <?php } ?>
                        </td>
                        <td><?php echo cc_getPostViews(get_the_ID()); ?></td>
                        <td>
                            <?php
                            if ($post->post_status == 'publish')
                                echo "Approved";
                            elseif ($post->post_status == 'pending')
                                echo "Pending for Approval"
                                ?>
                        </td>
                        <td>
                            <a href="<?php echo site_url(CC_DASHBOARD . "?action=edit&pid=$post->ID"); ?>" title="Edit Ad"><img src="<?php echo TEMPLATEURL . '/images/pencil.png'; ?>"/></a>&nbsp;
                            <a href="<?php echo site_url(CC_DASHBOARD . "?action=delete&pid=$post->ID"); ?>" title="Delete Ad"><img src="<?php echo TEMPLATEURL . '/images/cross.png'; ?>"/></a>
                        </td>
                    </tr>  
                    <?php
                    $count++;
                endwhile;
                ?>                
            <?php else: ?>
                <tr>
                    <td colspan="4">
                        <p><?php echo NO_ADS; ?></p>   
                    </td>
                </tr>                                            
            <?php
            endif;
            ?>
        </tbody>
    </table>
    <p><?php cc_pagination(); ?></p>
    <?php
    wp_reset_query();
    ?>
</div>
<div class="clear"></div>
