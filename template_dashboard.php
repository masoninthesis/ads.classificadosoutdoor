<?php
/**
 * Template Name: Template Dashboard 
 */
auth_redirect_login();
get_header();
?>
<!--Start Cotent Wrapper-->
<div class="content_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_17 alpha">
                <!--Start Cotent-->
                <div class="content" id="dashboard">
                    <?php
                    wp_enqueue_script('cc_ajax_upload', LIBRARYURL . '/js/ajaxupload.js', array('jquery'));
                    if (file_exists(LIBRARYPATH . 'js/image-upload.php')) {
                        include_once(LIBRARYPATH . 'js/image-upload.php');
                    }
                    //Loading author's listing in dashboard
                    if (!isset($_REQUEST['action']) || $_REQUEST['action'] == 'view' || $_REQUEST['action'] == 'delete') {
                        if (file_exists(SETTINGPATH . 'dashboard.php')) {
                            include_once(SETTINGPATH . 'dashboard.php');
                        }
                    }
                    //Loading author's listing for edit
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit') {
                        if (file_exists(SETTINGPATH . 'edit_ad.php')) {
                            include_once(SETTINGPATH . 'edit_ad.php');
                        }
                    }
                    //Loading cmments file
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'comment') {
                        if (file_exists(SETTINGPATH . 'view_comments.php')) {
                            include_once(SETTINGPATH . 'view_comments.php');
                        }
                    }
                    //Showing user profile
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'profile') {
                        if (file_exists(SETTINGPATH . 'edit_profile.php')) {
                            include_once(SETTINGPATH . 'edit_profile.php');
                        }
                    }
                    //Showing leads
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'lead') {
                        if (file_exists(SETTINGPATH . 'cc_show_leads.php')) {
                            include_once(SETTINGPATH . 'cc_show_leads.php');
                        }
                    }
                    //Showing expired ads
                    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'expire' || $_REQUEST['action'] == 'renew') {
                        if (file_exists(SETTINGPATH . 'cc_show_expire_ads.php')) {
                            include_once(SETTINGPATH . 'cc_show_expire_ads.php');
                        }
                    }                    
                    ?>
                </div>
                <!--End Cotent-->
            </div>
            <div class="grid_7 omega">
                <div class="sidebar">
                    <?php
                    cc_dashboard_sidebar('ad');
                    ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--End Cotent Wrapper-->
<?php
get_footer();
