<?php
//globlization
global $wpdb, $cc_leads_tbl_name, $current_user;
$update_msg = '';
if (isset($_REQUEST['action']) && $_REQUEST['action'] == "lead" && isset($_REQUEST['id'])) {
    $id = $_REQUEST['id'];
    //Deleting leads by requested id
    $query = "DELETE FROM $cc_leads_tbl_name WHERE ID = $id";
    $wpdb->query($query);
    $update_msg = LEAD_DELTD;
}
?>
<?php if($update_msg){ ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<div class="box-head"><h2><?php echo UR_LEADS; ?></h2></div>
<div class="box-content no-pad">
    <table class="display dataTable">
        <thead>                                   
            <tr>
                <th><?php echo S_NO; ?></th>
                <th><?php echo 'Ad Title'; ?></th>
                <th><?php echo NM; ?></th>
                <th><?php echo EMAIL_AD; ?></th>
                <th><?php echo MSG; ?></th>
                <th><?php echo ACTION; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = $wpdb->query("SELECT * FROM $cc_leads_tbl_name WHERE post_author = $current_user->ID"); // Get total of Num rows from the database query
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
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$add1") . '">' . $add1 . '</a></li>';
            } else if ($pn == $lastPage) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
            } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$sub2") . '">' . $sub2 . '</a></li>';
                $centerPages .= '<li> <a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$add2") . '">' . $add1 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$add2") . '">' . $add2 . '</a></li>';
            } else if ($pn > 1 && $pn < $lastPage) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$add1") . '">' . $add1 . '</a></li>';
            }

            $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;

            $paginationDisplay = "<ul class='paginate'>"; // Initialize the pagination output variable
            if ($lastPage != "1") {
                //$paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '<li> <a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$previous") . '"> Back</a></li>';
                }
                $paginationDisplay .= $centerPages;
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '<li><a href="' . site_url(CC_DASHBOARD . "/?action=lead&pn=$nextPage") . '"> Next</a></li> ';
                }
            }
            $paginationDisplay .= '</ul>';
            $count = 1;
            $leads = $wpdb->get_results("SELECT * FROM $cc_leads_tbl_name WHERE post_author = $current_user->ID $limit");
            if ($leads):
                foreach ($leads as $lead):
                    ?>
                    <tr class="<?php if ($count % 2 == 0) echo "even"; else echo "odd"; ?>">                                                
                        <td><?php echo $count; ?></td>
                        <td><?php echo $lead->lead_ad_title; ?></td> 
                        <td><?php echo $lead->lead_name; ?></td>                       
                        <td><a target="new" href="mailto:<?php echo $lead->lead_email; ?>"><?php echo $lead->lead_email; ?></a></td>
                        <td><?php echo $lead->lead_message; ?></td>
                        <td><a title="Delete lead" href="<?php echo site_url(CC_DASHBOARD . "?action=lead&id=$lead->ID"); ?>"><img src="<?php echo TEMPLATEURL . '/images/delete.png'; ?>"/></a></td>
                    </tr>  
                    <?php
                    $count++;
                endforeach;
                ?>               
            <?php else: ?>
                <tr>
                    <td colspan="5">
                        <p><?php echo NO_LEAD_MSG; ?></p>   
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
