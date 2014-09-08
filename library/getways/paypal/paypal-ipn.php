<?php

// this is the paypal ipn listener which waits for the request
function gc_ipn_listener() {

    // validate the paypal request by sending it back to paypal
    function gc_ipn_request_check() {

        define('SSL_P_URL', 'https://www.paypal.com/cgi-bin/webscr');
        define('SSL_SAND_URL', 'https://www.sandbox.paypal.com/cgi-bin/webscr');

        $hostname = gethostbyaddr($_SERVER ['REMOTE_ADDR']);
        if (!preg_match('/paypal\.com$/', $hostname)) {
            $ipn_status = 'Validation post isn\'t from PayPal';
            if (get_option('paypal_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'fail');
            }
            return false;
        }

        // parse the paypal URL
        $paypal_url = ($_REQUEST['test_ipn'] == 1) ? SSL_SAND_URL : SSL_P_URL;
        $url_parsed = parse_url($paypal_url);


        $post_string = '';
        foreach ($_REQUEST as $field => $value) {
            $post_string .= $field . '=' . urlencode(stripslashes($value)) . '&';
        }
        $post_string.="cmd=_notify-validate"; // append ipn command
        // get the correct paypal url to post request to
        $get_paypal = get_option('pay_method_paypal');
        if (is_array($get_paypal)):
            $paypal_mode_status = $get_paypal['paypal_sandbox'];
        endif;
        if ($paypal_mode_status == true)
            $fp = fsockopen('ssl://www.sandbox.paypal.com', "443", $err_num, $err_str, 60);
        else
            $fp = fsockopen('ssl://www.paypal.com', "443", $err_num, $err_str, 60);


        $ipn_response = '';


        if (!$fp) {
            // could not open the connection.  If loggin is on, the error message
            // will be in the log.
            $ipn_status = "fsockopen error no. $err_num: $err_str";
            if (get_option('paypal_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'fail');
            }
            return false;
        } else {
            // Post the data back to paypal
            fputs($fp, "POST $url_parsed[path] HTTP/1.1\r\n");
            fputs($fp, "Host: $url_parsed[host]\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: " . strlen($post_string) . "\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $post_string . "\r\n\r\n");

            // loop through the response from the server and append to variable
            while (!feof($fp)) {
                $ipn_response .= fgets($fp, 1024);
            }
            fclose($fp); // close connection
        }

        // Invalid IPN transaction.  Check the $ipn_status and log for details.
        if (!preg_match("/VERIFIED/s", $ipn_response)) {
            $ipn_status = 'IPN Validation Failed';
            if (get_option('paypal_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'fail');
            }
            return false;
        } else {
            $ipn_status = "IPN VERIFIED";
            if (get_option('paypal_ipn') == true) {
                wp_mail(get_option('admin_email'), $ipn_status, 'SUCCESS');
            }
            return true;
        }
    }

    // if the test variable is set (sandbox mode), send a debug email with all values
    if (isset($_REQUEST['test_ipn'])) {
        $_REQUEST = stripslashes_deep($_REQUEST);
        if (get_option('paypal_ipn') == true) {
            wp_mail(get_option('admin_email'), 'PayPal IPN Debug Email Test IPN', "" . print_r($_REQUEST, true));
        }
    }

    // make sure the request came from classicraft (pid) or paypal (txn_id refund, update)
    if (isset($_REQUEST['txn_id']) || isset($_REQUEST['invoice'])) {
        $_REQUEST = stripslashes_deep($_REQUEST);

        // if paypal sends a response code back let's handle it
        if (gc_ipn_request_check()) {

            // send debug email to see paypal ipn post vars
            if (get_option('paypal_ipn') == true) {
                wp_mail(get_option('admin_email'), 'PayPal IPN Debug Email Main', "" . print_r($_REQUEST, true));
            }
            // process the ad since paypal gave us a valid response
            //do_action('gc_init_ipn_response', $_REQUEST);
            gc_handle_ipn_response($_REQUEST);
        }
    }
}

add_action('init', 'gc_ipn_listener');

function gc_handle_ipn_response($request) {
    global $wpdb;

    //step functions required to process orders
    //include_once("wp-load.php");
    // make sure the ad unique trans id (stored in invoice var) is included
    if (!empty($request['txn_id'])) {


        // process the ad based on the paypal response
        switch (strtolower($request['payment_status'])) :

            // payment was made so we can approve the ad
            case 'completed' :
                update_option('cc_payment_status', 'yes');
                //Update listing
                cc_set_listing($request['post_id']);

                //Renewing listing
                gc_renew_listing($request['post_id'], $request['pkg_type']);

                //Upgrading listing
                $upgrade_meta_values = get_post_meta($request['post_id'], 'cc_add_type', true);
                if ($upgrade_meta_values == "free") {
                    cc_upgrade_listing($request['post_id'], $request['mc_gross']);
                }

                //Set transactions fields
                cc_set_transaction($request);
                //admin email confirmation
                //TODO - move into wordpress options panel and allow customization
                wp_mail(get_option('admin_email'), 'Payment Receive', "A membership payment has been completed. Check to make sure this is a valid order by comparing this messages Paypal Transaction ID to the respective ID in the Paypal payment receipt email.");

                //Mail details to admin email
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment receiver', THEME_SLUG);
                $headers = 'From: ' . __('Classicraft Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment is receive on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";
                //admin email
                wp_mail($mailto, $subject, $message, $headers);

                $blogtime = current_time('mysql');
                $transaction_details .= "--------------------------------------------------------------------------------\r";
                $transaction_details .= "Payment Details for Listing ID #{$request['post_id']}\r";
                $transaction_details .= "--------------------------------------------------------------------------------\r";
                $transaction_details .= "Listing Title: {$request['post_title']} \r";
                $transaction_details .= "--------------------------------------------------------------------------------\r";
                $transaction_details .= "Trans ID: {$request['txn_id']}\r";
                $transaction_details .= "Status: {$request['payment_status']}\r";
                $transaction_details .= "Date: $blogtime\r";
                $transaction_details .= "--------------------------------------------------------------------------------\r";
                $transaction_details = __($transaction_details, THEME_SLUG);
                $subject = __("Listing Submitted and Payment Success Confirmation Email", THEME_SLUG);
                $site_name = get_option('blogname');
                $fromEmail = 'Admin';
                $filecontent = $transaction_details;
                global $wpdb;
                $placeinfosql = "SELECT ID, post_title, guid, post_author from $wpdb->posts where ID ={$request['post_id']}";
                $placeinfo = $wpdb->get_results($placeinfosql);
                foreach ($placeinfo as $placeinfoObj) {
                    $post_link = $placeinfoObj->guid;
                    $post_title = '<a href="' . $post_link . '">' . $placeinfoObj->post_title . '</a>';
                    $authorinfo = $placeinfoObj->post_author;
                    $userInfo = get_author_info($authorinfo);
                    $to_name = $userInfo->user_nicename;
                    $to_email = $userInfo->user_email;
                    $user_email = $userInfo->user_email;
                }
                $headers = 'From: ' . $to_admin . ' <' . $user_email . '>' . "\r\n" . 'Reply-To: ' . $to_admin;
                wp_mail($user_email, $subject, $filecontent, $headers); //email to client

                break;

            case 'pending' :
                update_option('cc_payment_status', 'no');
                // send an email if payment is pending
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment pending', THEME_SLUG);
                $headers = 'From: ' . __('Classicraft Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment is pending on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";

                wp_mail($mailto, $subject, $message, $headers);

                break;

            // payment failed so don't approve the ad
            case 'denied' :
            case 'expired' :
            case 'failed' :
            case 'voided' :
                update_option('cc_payment_status', 'no');
                //Expire listing 
                cc_listing_expire($request['post_id']);
                // send an email if payment didn't work
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment failed', THEME_SLUG);
                $headers = 'From: ' . __('Classicraft Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment has failed on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";

                wp_mail($mailto, $subject, $message, $headers);

                break;

            case 'refunded' :
            case 'reversed' :
            case 'chargeback' :
                update_option('cc_payment_status', 'no');
                //Expire listing 
                cc_listing_expire($request['post_id']);
                // send an email if payment was refunded
                $mailto = get_option('admin_email');
                $subject = __('PayPal IPN - payment refunded/reversed', THEME_SLUG);
                $headers = 'From: ' . __('Classicraft Admin', THEME_SLUG) . ' <' . get_option('admin_email') . '>' . "\r\n";
                $blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);

                $message = __('Dear Admin,', THEME_SLUG) . "\r\n\r\n";
                $message .= sprintf(__('The following payment has been marked as refunded on your %s website.', THEME_SLUG), $blogname) . "\r\n\r\n";
                $message .= __('Payment Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= __('Payer PayPal address: ', THEME_SLUG) . $request['payer_email'] . "\r\n";
                $message .= __('Transaction ID: ', THEME_SLUG) . $request['txn_id'] . "\r\n";
                $message .= __('Payer first name: ', THEME_SLUG) . $request['first_name'] . "\r\n";
                $message .= __('Payer last name: ', THEME_SLUG) . $request['last_name'] . "\r\n";
                $message .= __('Payment type: ', THEME_SLUG) . $request['payment_type'] . "\r\n";
                $message .= __('Reason code: ', THEME_SLUG) . $request['reason_code'] . "\r\n";
                $message .= __('Amount: ', THEME_SLUG) . $request['mc_gross'] . " (" . $request['mc_currency'] . ")\r\n\r\n";
                $message .= __('Full Details', THEME_SLUG) . "\r\n";
                $message .= __('-----------------') . "\r\n";
                $message .= print_r($request, true) . "\r\n";

                wp_mail($mailto, $subject, $message, $headers);

                break;
        endswitch;
    }
}

add_action('gc_init_ipn_response', 'gc_handle_ipn_response');
?>