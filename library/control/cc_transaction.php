<?php
function cc_payment_status($atts) {
    //Getting paypal response values
    $item_name = $_REQUEST['item_name'];
    $item_number = $_REQUEST['item_number'];
    $payment_amount = $_REQUEST['mc_gross'];
    $payment_currency = $_REQUEST['mc_currency'];
    $txn_id = $_REQUEST['txn_id'];
    $receiver_email = $_REQUEST['receiver_email'];
    $payer_email = $_REQUEST['payer_email'];
    $payment_method = $_REQUEST['pay_method'];
    //Displaying transaction status to user
    if (get_option('cc_payment_status') == 'yes') {
        $payment_status = "Completed";
        $str = "<h3>Thank you for your payment</h3>";
    } else {
        $payment_status = "Failed";
    }    
    $str .= POST_NAME . "&nbsp;&nbsp;<b>" . $item_name . '</b><br/>';
    //echo "Your Post Number:&nbsp;&nbsp;<b>" . $item_number . '</b><br/>';
    $str .= PMT_STATUS . "&nbsp;&nbsp;<b>" . $payment_status . '</b><br/>';
    $str .= PMT_CURRENCY . "&nbsp;&nbsp;<b>" . $payment_currency . '</b><br/>';
    $str .= PAYER_EMAIL . "&nbsp;&nbsp;<b>" . $payer_email . '</b><br/>';
    return $str;
}

add_shortcode('pay-status', 'cc_payment_status');

