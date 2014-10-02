<?php
//globlization
global $wpdb, $expiry_tbl_name, $current_user;
$update_msg = '';
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "expire" && isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    //Deleting leads by requested id
    wp_delete_post($id);
    $update_msg = "A ad has been deleted.";
}
 $post_id = $_REQUEST['pid'];
    if ($post_id && $_REQUEST['action'] == "renew") {
        $listing_type = get_post_meta($post_id, 'cc_add_type', true);
        //if ($listing_type == "free") {
            $renew_response = gc_renew_listing($post_id);
            if($renew_response == true){
                $success = '<script type="text/javascript">';
                $success .= 'jQuery(document).ready(function(){';
                $success .= 'alert("Your listing has been renewed");';
                $success .= '});';
                $success .= '</script>';
                echo $success;
                $update_msg = "Ad has been renewed";
            }
        //}
    }
?>
<?php if($update_msg){ ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<div class="box-head"><h2><?php echo EXPIRED_ADS; ?></h2></div>
<div class="box-content no-pad">
    <table class="display dataTable">
        <thead>                                   
            <tr>
                <th><?php echo S_NO; ?></th>
                <th><?php echo AD_TTLE; ?></th>
                <th><?php echo CATES; ?></th>               
                <th><?php echo ACTION; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $post_type = POST_TYPE;
            $query = $wpdb->query("SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'draft') ORDER BY  " . $wpdb->posts . ".`ID` DESC"); // Get total of Num rows from the database query
            if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
                $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
            } else { // If the pn URL variable is not present force it to be value of page number 1
                $pn = 1;
            }

            $itemsPerPage = 20;

            $lastPage = ceil($query / $itemsPerPage);

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
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$add1") . '">' . $add1 . '</a></li>';
            } else if ($pn == $lastPage) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
            } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$sub2") . '">' . $sub2 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$add2") . '">' . $add1 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$add2") . '">' . $add2 . '</a></li>';
            } else if ($pn > 1 && $pn < $lastPage) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$add1") . '">' . $add1 . '</a></li>';
            }

            $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;

            $paginationDisplay = "<ul class='paginate'>"; // Initialize the pagination output variable
            if ($lastPage != "1") {
                //$paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$previous") . '"> Back</a></li>';
                }
                $paginationDisplay .= $centerPages;
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=expire&pn=$nextPage") . '"> Next</a></li>';
                }
            }
            $paginationDisplay .= '</ul>';
            $count = 1;
            $expires = $wpdb->get_results("SELECT " . $wpdb->posts . ".*  FROM " . $wpdb->posts . " WHERE " . $wpdb->posts . ".post_author = $current_user->ID AND " . $wpdb->posts . ".post_type = '$post_type' AND (" . $wpdb->posts . ".post_status = 'draft') ORDER BY  " . $wpdb->posts . ".`ID` DESC $limit");
            if ($expires):
                foreach ($expires as $expire):
                $categories = get_the_term_list($expire->ID, CUSTOM_CAT_TYPE, '', ',', '');
                    ?>
                    <tr class="<?php if ($count % 2 == 0) echo "even"; else echo "odd"; ?>">                                                
                        <td><?php echo $count; ?></td>
                        <td><?php echo $expire->post_title; ?></td>
                        <td><?php echo $categories; ?></td>
                        <td><a title="Delete this ad" href="<?php echo site_url(CC_DASHBOARD . "?action=expire&id=$expire->ID"); ?>"><img src="<?php echo TEMPLATEURL . '/images/delete.png'; ?>"/></a>&nbsp;
                        <a title="Renew this ad" href="<?php echo site_url(CC_DASHBOARD . "?action=renew&pid=$expire->ID"); ?>"><img src="<?php echo TEMPLATEURL . '/images/renew.png'; ?>"/></a></td>
                    </tr>  
                    <?php
                    $count++;
                endforeach;
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
    <div class="paging"><span style="float:right;"><?php echo $paginationDisplay; ?></span></div>  
</div>
<div class="clear"></div>