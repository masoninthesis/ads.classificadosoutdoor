<?php
//globlization
global $wpdb, $cc_leads_tbl_name, $current_user;
$update_msg = '';
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delete" && isset($_REQUEST['lid'])) {
    $id = $_REQUEST['lid'];
    //Deleting leads by requested id
    $query = "DELETE FROM $cc_leads_tbl_name WHERE ID = $id";
    $wpdb->query($query);
    $update_msg = LEAD_DELTD;
}

$sql = "SELECT * FROM $cc_leads_tbl_name";
//Pagination for leads
$query = $wpdb->query($sql); // Get total of Num rows from the database query
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
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
} else if ($pn == $lastPage) {
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
} else if ($pn > 2 && $pn < ($lastPage - 1)) {
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
} else if ($pn > 1 && $pn < $lastPage) {
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=leads&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
}

$limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;

$paginationDisplay = ""; // Initialize the pagination output variable
if ($lastPage != "1") {
    $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

    if ($pn != 1) {
        $previous = $pn - 1;
        $paginationDisplay .= '&nbsp;  <a href="' . admin_url("/admin.php?page=leads&pn=$previous") . '"> Back</a> ';
    }
    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
    if ($pn != $lastPage) {
        $nextPage = $pn + 1;
        $paginationDisplay .= '&nbsp;  <a href="' . admin_url("/admin.php?page=leads&pn=$nextPage") . '"> Next</a> ';
    }
}
?>
<?php if($update_msg){ ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<div class="wrap" id="of_container" style="width:1050px;">
    <div id="header">
        <div class="logo">
            <h2><?php echo AD_LEAD; ?></h2>
        </div>
        <a href="http://www.inkthemes.com" target="_new">
            <div class="icon-option"> </div>
        </a>
        <div class="clear"></div>
    </div>
    <table id="tblspacer" class="widefat fixed">

        <thead>
            <tr>
                <th scope="col"><?php echo S_NO; ?></th>
                <th scope="col"><?php echo "Ad Title"; ?></th>
                <th scope="col"><?php echo NM; ?></th>
                <th scope="col"><?php echo EMAIL_AD; ?></th>
                <th scope="col"><?php echo POST_AUTHOR; ?></th>
                <th scope="col"><?php echo MSG; ?></th>
                <th scope="col"><?php echo ACTION; ?></th>
            </tr>
        </thead>
        <?php
        global $wpdb, $cc_leads_tbl_name;
        $count = 1;
            $leads = $wpdb->get_results("SELECT * FROM $cc_leads_tbl_name $limit");
            if($leads):
            ?>
            <tbody id="trans_list">
                <?php
                foreach ($leads as $lead):
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $lead->lead_ad_title; ?></td>
                        <td><?php echo $lead->lead_name; ?></td>
                        <td><a target="_blank" href="mailto:<?php echo $lead->lead_email; ?>"><?php echo $lead->lead_email; ?></a></td>
                        <td><?php echo get_the_author_meta('user_login',$lead->post_author) ?></td>                        
                        <td><?php echo $lead->lead_message; ?></td> 
                        <td><a title="Delete lead" href="<?php echo admin_url("admin.php?page=leads&action=delete&lid=$lead->ID"); ?>"><img src="<?php echo TEMPLATEURL.'/images/cross.png' ?>"/></a></td>
                    </tr>
                    <?php
                    $count++;
                endforeach;
                ?>
            <tr>
                <td colspan="6"><div class="paging"><span style="float:left;"><?php echo ITMS; ?>: <?php echo $query; ?></span>&nbsp;<span style="float:right;"><?php echo $paginationDisplay; ?></span></div></td>
            </tr>
            </tbody>
        <?php else: ?>
            <tr>
                <td colspan="6"><?php echo NO_LEAD_MSG; ?></td>
            </tr>
        <?php endif; ?>

    </table> <!-- this is ok -->

</div>