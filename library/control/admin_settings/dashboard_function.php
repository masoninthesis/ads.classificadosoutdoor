<?php

function cc_dashboard_sidebar() {
    ?>
    <div class="sidebar_dashboard">
        <h2 class="head"><?php echo ACT_INFO; ?></h2>
        <div class="author-meta">
            <?php            
            global $current_user;
            get_currentuserinfo();
            echo get_avatar($current_user->ID, 40);
            ?>
            <h4><?php echo WELCOME.',&nbsp;'. $current_user->user_login; ?></h4>
            <small><?php echo MMBR_SINCE."&nbsp;";
            $registered = ($current_user->user_registered . "\n");
            echo date("d/M/y", strtotime($registered));
            ?>
            </small>
        </div>                          
    </div>
    <div class="sidebar_dashboard">
        <h2 class="head"><?php echo USR_OPTIONS; ?></h2>                         
        <ul class="dash-list">
            <li class="addnew"><a href="<?php echo site_url(CC_ADNEW); ?>"><?php echo AD_NEW; ?></a></li>
            <li class="view"><a href="<?php echo site_url(CC_DASHBOARD."?action=view"); ?>"><?php echo VIEW_ADS; ?></a></li>
            <li class="comment"><a href="<?php echo site_url(CC_DASHBOARD."?action=comment"); ?>"><?php echo VEIW_CMTS; ?></a></li>
            <li class="lead"><a href="<?php echo site_url(CC_DASHBOARD."?action=lead"); ?>"><?php echo VEIW_LEADS; ?></a></li>
            <li class="expire"><a href="<?php echo site_url(CC_DASHBOARD."?action=expire"); ?>"><?php echo VEIW_EXPIRED; ?></a></li>
            <li class="profile"><a href="<?php echo site_url(CC_DASHBOARD."?action=profile"); ?>"><?php echo EDIT_PROFILE; ?></a></li>
        </ul>
    </div>
    <?php
}
?>
