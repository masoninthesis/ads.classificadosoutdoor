<?php
//globlization
global $wpdb, $transection_table_name, $current_user;
$update_msg = "";
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "delete" && isset($_REQUEST['tid'])) {
    $id = $_REQUEST['tid'];
    //Deleting transaction by requested id
    $query = "DELETE FROM $transection_table_name WHERE trans_id = $id";
    $wpdb->query($query);
    $update_msg = "A transaction has been deleted";
}
//Pagination for transection details
$sql = "SELECT * FROM $transection_table_name ORDER BY trans_id ASC ";

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
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
} else if ($pn == $lastPage) {
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
} else if ($pn > 2 && $pn < ($lastPage - 1)) {
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$sub2") . '">' . $sub2 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$add2") . '">' . $add1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$add2") . '">' . $add2 . '</a> &nbsp;';
} else if ($pn > 1 && $pn < $lastPage) {
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$sub1") . '">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="active">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . admin_url("/admin.php?page=transaction&pn=$add1") . '">' . $add1 . '</a> &nbsp;';
}

$limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;

$paginationDisplay = ""; // Initialize the pagination output variable
if ($lastPage != "1") {
    $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

    if ($pn != 1) {
        $previous = $pn - 1;
        $paginationDisplay .= '&nbsp;  <a href="' . admin_url("/admin.php?page=transaction&pn=$previous") . '"> Back</a> ';
    }
    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
    if ($pn != $lastPage) {
        $nextPage = $pn + 1;
        $paginationDisplay .= '&nbsp;  <a href="' . admin_url("/admin.php?page=transaction&pn=$nextPage") . '"> Next</a> ';
    }
}
?>
<?php if($update_msg){ ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<div class="wrap" id="of_container" style="width:1050px;">
    <div id="header">
        <div class="logo">
            <h2><?php echo TRANSACTION; ?></h2>
        </div>
        <a href="http://www.inkthemes.com" target="_new">
            <div class="icon-option"> </div>
        </a>
        <div class="clear"></div>
    </div>
    <table id="tblspacer" class="widefat fixed">

        <thead>
            <tr>
                <th scope="col"><?php echo ID; ?></th>
                <th scope="col"><?php echo LISTING_ID; ?></th>
<!--                <th scope="col"><?php echo USER_NAME; ?></th>-->
                <th scope="col" style="width: 210px !important;"><?php echo PAYER_EMAIL; ?></th>
                <th scope="col"><?php echo LISTING_TITLE; ?></th>                    
                <th scope="col" style="width:125px;"><?php echo TRANSACTION_ID; ?></th>                    
                <th scope="col"><?php echo PAYMENT_STATUS; ?></th>
                <th scope="col"><?php echo TOTAL_AMT; ?></th>
                <th scope="col"><?php echo 'Payment Type'; ?></th>
                <th scope="col" style="width:150px;"><?php echo PAID_DATE; ?></th>
                <th scope="col"><?php echo "Action"; ?></th>
            </tr>
        </thead>
        <?php
        global $wpdb, $transection_table_name;
        $sql = "SELECT * FROM $transection_table_name ORDER BY trans_id ASC $limit";
        $results = $wpdb->get_results($sql);
        if ($results):
            ?>
            <tbody id="trans_list">
                <?php
                foreach ($results as $result):
                    ?>
                    <tr>
                        <td><?php echo $result->trans_id; ?></td>
                        <td><?php echo $result->post_id; ?></td>
<!--                        <td><?php echo $result->user_name; ?></td>-->
                        <td><a target="_blank" href="mailto:<?php echo $result->pay_email; ?>"><?php echo $result->pay_email; ?></a></td>
                        <td><?php echo $result->post_title; ?></td>                            
                        <td><?php echo $result->paypal_transection_id; ?></td>                 
                        <td><?php if ($result->status == 1) echo 'Paid'; if ($result->status == 0) echo 'Pending'; ?></td>
                        <td><?php echo $result->payable_amt; ?></td>
                        <td><?php echo $result->txn_type; ?></td>
                        <td><?php echo mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $result->payment_date); ?></td>
                        <td><a title="Delete transaction" href="<?php echo admin_url("admin.php?page=transaction&action=delete&tid=$result->trans_id"); ?>"><img src="<?php echo TEMPLATEURL . '/images/delete.png'; ?>"/></a></td>
                    </tr>
                    <?php
                endforeach;
                ?>
                <tr>
                    <td colspan="10"><div class="paging"><span style="float:left;"><?php echo "Items"; ?>: <?php echo $query; ?></span>&nbsp;<span style="float:right;"><?php echo $paginationDisplay; ?></span></div></td>
                </tr>
            </tbody>
        <?php else: ?>
            <tr>
                <td colspan="10"><?php echo NO_TRANS_FOUND; ?></td>
            </tr>
        <?php endif; ?>

    </table> <!-- this is ok -->

</div>