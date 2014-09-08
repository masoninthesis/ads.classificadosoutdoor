<?php

//Dashboard link
define('CC_DASHBOARD', 'dashboard');
define('CC_ADNEW', 'ad-new');
define('CC_SEARCH', 'search');

function cc_reset_permalinks() {
    global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
}

function cc_custom_permalink() {
    add_action('all_admin_notices', 'cc_reset_permalinks');
}

/**
 * This class is used for installing and
 * Setting predefined options and values.
 */
class CC_Install {

// check if theme is activated by admin.
    //calling contructor
    function __construct() {
        global $pagenow;
        if (is_admin() && isset($_GET['activated'])) {
            $this->userid = get_current_user_id();
            $this->cc_create_page();
            $this->cc_default_values();
            $this->cc_default_widgets();
            cc_custom_permalink();
        }
    }

    /**
     * Create default pages 
     */
    function cc_create_page() {
        //Creating add new page
        $pages = get_option('cc_add_new');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => CC_ADNEW,
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Post an Ad', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages_id = wp_insert_post($my_page);
            if ($pages_id) {
                update_option('cc_add_new', $pages_id);
                update_post_meta($pages_id, '_wp_page_template', 'template_ad_new.php');
            }
        }
        //Creating dashboard page
        $pages = get_option('cc_dashboard');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => CC_DASHBOARD,
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Dashboard', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages_id = wp_insert_post($my_page);
            if ($pages_id) {
                update_option('cc_dashboard', $pages_id);
                update_post_meta($pages_id, '_wp_page_template', 'template_dashboard.php');
            }
        }
        //Creating search page
        $pages = get_option('cc_search');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => CC_SEARCH,
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Search', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages_id = wp_insert_post($my_page);
            if ($pages_id) {
                update_option('cc_search', $pages_id);
                update_post_meta($pages_id, '_wp_page_template', 'template_search.php');
            }
        }
        //Creating premium ads page
        $pages = get_option('cc_premium');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'premium-ad',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('Premium Ads', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages_id = wp_insert_post($my_page);
            if ($pages_id) {
                update_option('cc_premium', $pages_id);
                update_post_meta($pages_id, '_wp_page_template', 'template_premium_ads.php');
            }
        }
        //Creating all ads page
        $pages = get_option('cc_all');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'all-ad',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                //'post_content' => '',
                'post_title' => __('All Ads', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages_id = wp_insert_post($my_page);
            if ($pages_id) {
                update_option('cc_all', $pages_id);
                update_post_meta($pages_id, '_wp_page_template', 'template_all_ads.php');
            }
        }
        
         //Creating Payment status page
        $pages = get_option('cc_payment_stat');
        if (empty($pages)) {
            $my_page = array(
                'ID' => false,
                'post_type' => 'page',
                'post_name' => 'payment-status',
                'ping_status' => 'closed',
                'post_status' => 'publish',
                'comment_status' => 'closed',
                'post_author' => $this->userid,
                'post_content' => '[pay-status]',
                'post_title' => __('Payment Status', THEME_SLUG),
                'post_excerpt' => ''
            );
            $pages_id = wp_insert_post($my_page);
            if ($pages_id) {
                update_option('cc_payment_stat', $pages_id);
            }
        }
    }

//end create_page
    function cc_default_values() {
        //check the membership box to enable wordpress registration
        if (get_option('users_can_register') == 0)
            update_option('users_can_register', 1);
    }

    /**
     * Activate and set 
     * Default widgets 
     */
    function cc_default_widgets() {

        $widget_recent_post = array();
        $widget_recent_post[1] = array(
            "title" => 'RECENT_ADS',
            "sort_by" => 'date',
            "show_type" => POST_TYPE,
            "number" => '5',
            "excerpt_length" => '20',
        );
        $widget_recent_post['_multiwidget'] = '1';
        update_option('widget_advanced-recent-posts', $widget_recent_post);
        $widget_recent_post = get_option('widget_advanced-recent-posts');
        krsort($widget_recent_post);
        foreach ($widget_recent_post as $key1 => $val1) {
            $widget_recent_post_key = $key1;
            if (is_int($widget_recent_post_key)) {
                break;
            }
        }

        $widget_recent_review = array();
        $widget_recent_review[1] = array(
            "title" => 'Recent Reviews',
            "number" => '5',
        );
        $widget_recent_review['_multiwidget'] = '1';
        update_option('widget_recent-review', $widget_recent_review);
        $widget_recent_review = get_option('widget_recent-review');
        krsort($widget_recent_review);
        foreach ($widget_recent_review as $key2 => $val1) {
            $widget_recent_review_key = $key2;
            if (is_int($widget_recent_review_key)) {
                break;
            }
        }

        $widget_listing_category = array();
        $widget_listing_category[1] = array(
            "title" => 'Categories',
        );
        $widget_listing_category['_multiwidget'] = '1';
        update_option('widget_custom-categories', $widget_listing_category);
        $widget_listing_category = get_option('widget_custom-categories');
        krsort($widget_listing_category);
        foreach ($widget_listing_category as $key3 => $val1) {
            $widget_listing_category_key = $key3;
            if (is_int($widget_listing_category_key)) {
                break;
            }
        }

        $sidebars_widgets["add-widget-area"] = array("custom-categories-$widget_listing_category_key", "advanced-recent-posts-$widget_recent_post_key");
        $sidebars_widgets["home-widget-area"] = array("custom-categories-$widget_listing_category_key", "advanced-recent-posts-$widget_recent_post_key");
        $sidebars_widgets["contact-widget-area"] = array("custom-categories-$widget_listing_category_key", "advanced-recent-posts-$widget_recent_post_key");
        $sidebars_widgets["blog-widget-area"] = array(0 => 'search-2', 1 => 'recent-posts-2', 2 => 'recent-comments-2', 3 => 'archives-2', 4 => 'categories-2', 5 => 'meta-2',);
        $sidebars_widgets["pages-widget-area"] = array(0 => 'search-2', 1 => 'recent-posts-2', 2 => 'recent-comments-2', 3 => 'archives-2', 4 => 'categories-2', 5 => 'meta-2',);
        update_option('sidebars_widgets', $sidebars_widgets);  //save widget iformations
    }

}

//end class
new CC_Install();

