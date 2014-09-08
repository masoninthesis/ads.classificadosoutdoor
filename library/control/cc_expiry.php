<?php
/**
 * Classicraft expiry Ads
 * This file is the backbone for expire listing
 * Modifying this will void your warranty and could cause
 * problems with your instance of CP. Proceed at your own risk!
 *
 * @package Classicraft
 * @author InkThemes
 * @since version: 1.1
 * 
 * Terms for expire ads:
 * 
 * 1. Free ads and onetime ads expire based on ad
 *    active period on payment setting.
 * 
 * 2. Recurring ad expire based on first period of payment +
 *    Second periond of payment.
 * 
 * 3. If ads are loder than current version, ads are expiry  
 *    period will be set by based on current active period.
 * 
 * 4. If admin post the ads, The expiry duration will be set based on
 *    current free package active period.
 * 
 * 5. After the ads expired, an email notification would be send on
 *    ad author.
 */
//Expiry term for older and front end listings.
global $wpdb, $expiry_tbl_name;
if($expiry_tbl_name){
$sql_expiry = "SELECT * FROM " . $expiry_tbl_name . "";
$expiry_query = $wpdb->get_results($sql_expiry);
if ($expiry_query):
    foreach ($expiry_query as $expiry):
        $listing_type = get_post_meta($expiry->pid, 'cc_listing_type', true);
        $pkg_type = $expiry->package_type;
        $vper = $expiry->validity_per;
        $validity = $expiry->validity;
        cc_set_expiry($expiry->pid, $pkg_type);
        $expire = cc_has_ad_expired($expiry->pid);
        if ($expire == true) {
            //Send notification to user
            global $wpdb, $post;
            $slq_notify = "SELECT post_author FROM $wpdb->posts WHERE ID = $expiry->pid";
            $post_result = $wpdb->get_row($slq_notify);
            $post_author = $post_result->post_author;
            $site_name = get_option('blogname');
            $email = get_option('admin_email');
            $website_link = get_option('siteurl');
            $listing_title = $listing->post_title;
            $lisgint_guid = $listing->guid;
            $login_url = site_url("/wp-login.php?action=login");
            $listing_user_name = get_the_author_meta('user_login', $post_author);
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Dear $listing_user_name, \r";
            $message .= "Your ad has expired. We inform you that, if you are interested to reactivate your ad, \r";
            $message .= "Login in our website and reactivate it. \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Listing Title: $listing_title \r";
            $message .= "Login On: $login_url \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Website: $site_name\r";
            $message .= "Website URL: $website_link\r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message = __($message, THEME_SLUG);
            //get listing author email
            $to = get_the_author_meta('user_email', $post_author);
            $subject = 'Your listing reactivation notice';
            $headers = 'From: Site Admin <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            wp_mail($to, $subject, $message, $headers);
        }
    endforeach;
endif;
}
//Expiry term for older and admin's listings. 
global $wpdb;
$post_type = POST_TYPE;
$listing_query = 'SELECT * FROM '.$wpdb->posts.' WHERE post_type = "'.$post_type.'" AND post_status = "publish"';
$listing_result = $wpdb->get_results($listing_query);
if (!empty($listing_result)) {
    foreach ($listing_result as $listing) {
        $is_expiry_set = get_post_meta($listing->ID, 'cc_listing_duration', true);
        /**
         * Check if ad expiry date is already set. 
         * If not set, it will set the expiry date based on current free package active period.
         */
        if (empty($is_expiry_set)) {
            cc_set_default_expiry($listing->ID);
        }
        //getting listing status
        $expire = cc_has_ad_expired($listing->ID);
        //if listing expired
        if ($expire == true) {
            $post_author = $listing->post_author;
            $site_name = get_option('blogname');
            $email = get_option('admin_email');
            $website_link = get_option('siteurl');
            $listing_title = $listing->post_title;
            $lisgint_guid = $listing->guid;
            $login_url = site_url("/wp-login.php?action=login");
            $listing_user_name = get_the_author_meta('user_login', $post_author);
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Dear $listing_user_name, \r";
            $message .= "Your ad has expired. We inform you that, if you are interested to reactivate your ad, \r";
            $message .= "Login in our website and reactivate it. \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Listing Title: $listing_title \r";
            $message .= "Login On: $login_url \r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message .= "Website: $site_name\r";
            $message .= "Website URL: $website_link\r";
            $message .= "--------------------------------------------------------------------------------\r";
            $message = __($message, THEME_SLUG);
            //get listing author email
            $to = get_the_author_meta('user_email', $post_author);
            $subject = 'Your ad reactivation notice';
            $headers = 'From: Site Admin <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            wp_mail($to, $subject, $message, $headers);
        }
    }
}