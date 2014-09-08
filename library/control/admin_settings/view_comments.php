<div class="box-head"><h2><?php echo UR_COMMENTS; ?></h2></div>
<div class="box-content no-pad">
    <table class="display dataTable">
        <thead>                                   
            <tr>
                <th><?php echo AUTHOR; ?></th>
                <th><?php echo COMMENTS; ?></th>
                <th><?php echo RESPONSE_TO; ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            global $wpdb, $current_user;
            $author = $current_user->ID;
            $post_type = POST_TYPE;
            $inner_join = "INNER JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID";
            $where = "WHERE";
            $where .= " $wpdb->comments.comment_approved = 1";
            $where .= " AND $wpdb->posts.post_status = 'publish'";
            $where .= " AND $wpdb->posts.post_author = $author";
            $where .= " AND $wpdb->posts.post_type = '$post_type'";
            $sql = "SELECT $wpdb->comments.*,$wpdb->posts.* FROM  $wpdb->comments $inner_join $where";
            //Pagination for comments
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
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$add1") . '">' . $add1 . '</a></li>';
            } else if ($pn == $lastPage) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
            } else if ($pn > 2 && $pn < ($lastPage - 1)) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$sub2") . '">' . $sub2 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$add2") . '">' . $add1 . '</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$add2") . '">' . $add2 . '</a></li>';
            } else if ($pn > 1 && $pn < $lastPage) {
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$sub1") . '">' . $sub1 . '</a></li>';
                $centerPages .= '<li><a class="current" href="">'.$pn.'</a></li>';
                $centerPages .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$add1") . '">' . $add1 . '</a></li>';
            }

            $limit = 'LIMIT ' . ($pn - 1) * $itemsPerPage . ',' . $itemsPerPage;

            $paginationDisplay = "<ul class='paginate'>"; // Initialize the pagination output variable
            if ($lastPage != "1") {
                //$paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage . '&nbsp;  &nbsp;  &nbsp; ';

                if ($pn != 1) {
                    $previous = $pn - 1;
                    $paginationDisplay .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$previous") . '"> Back</a></li>';
                }
                $paginationDisplay .= $centerPages;
                if ($pn != $lastPage) {
                    $nextPage = $pn + 1;
                    $paginationDisplay .= '<li><a href="' . site_url(CC_DASHBOARD."/?action=comment&pn=$nextPage") . '"> Next</a></li>';
                }
            }
            $paginationDisplay .= '</ul>';
            $cmt = "SELECT $wpdb->comments.*,$wpdb->posts.* FROM  $wpdb->comments $inner_join $where $limit";
            $comments = $wpdb->get_results($cmt);
            $count = 1;
            if ($comments) :
                foreach ($comments as $comment):
                    global $post;
                    ?>
                    <tr class="<?php if ($count % 2 == 0) echo "even"; else echo "odd"; ?>">                                                
                        <td>
                            <?php echo $comment->comment_author; ?><br/>
                            <a class="author_email" href="mailto:<?php echo $comment->comment_author_email; ?>"><?php echo $comment->comment_author_email; ?></a>
                        </td>
                        <td><span class="comment_date"> <?php echo SUBMITED.'&nbsp;'.$comment->comment_date; ?> </span><br/><?php echo $comment->comment_content; ?></td>                       
                        <td>
                            <span class="post_count"><span class="comment_count"><?php echo $comment->comment_count; ?></span></span><a target="new" href="<?php echo $comment->guid; ?>"><?php echo $comment->post_title; ?></a>
                        </td>
                    </tr>  
                    <?php
                    $count++;
                endforeach;
                ?>             
            <?php else: ?>
                <tr>
                    <td colspan="3">
                        <p><?php echo NO_COMMENTS; ?></p>   
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
