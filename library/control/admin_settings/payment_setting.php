<?php

class cc_getway_option {

    function cc_paypal_getway_setting() {
        if (isset($_POST['submit'])) {
            global $wpdb;
            $paymentupdsql = "select option_value from $wpdb->options where option_name='pay_method_paypal'";
            $paymentupdinfo = $wpdb->get_results($paymentupdsql);
            if ($paymentupdinfo) {
                foreach ($paymentupdinfo as $paymentupdinfoObj) {
                    $option_value = unserialize($paymentupdinfoObj->option_value);
                    $payment_method = trim($_POST['method_name']);
                    $display_order = trim($_POST['pay_order']);
                    $paymet_isactive = $_POST['paypal_status'];
                    $paypal_sandbox = trim($_POST['paypal_sandbox']);
                    if ($payment_method) {
                        $option_value['name'] = $payment_method;
                    }
                    $option_value['display_order'] = $display_order;
                    //$option_value['isactive'] = $paymet_isactive;
                    if ($option_value['key'] == 'paypal') {
                        $option_value['paypal_sandbox'] = $paypal_sandbox;
                    }
                    $paymentOpts = $option_value['payOpts'];
                    for ($j = 0; $j < count($paymentOpts); $j++) {
                        $paymentOpts[$j]['value'] = $_POST[$paymentOpts[$j]['fieldname']];
                    }
                    $option_value['payOpts'] = $paymentOpts;
                    $option_value_str = serialize($option_value);
                }
            }
            if(isset($_POST['paypal_ipn'])){
            update_option('paypal_ipn', $_POST['paypal_ipn']);
        }
            $updatestatus = "update $wpdb->options set option_value= '$option_value_str' where option_name='pay_method_paypal'";
            $wpdb->query($updatestatus);
        }
        if ($_GET['status'] != '') {
            $option_value['isactive'] = $_GET['status'];
        }
        global $wpdb;
        $paymethodsql = "select option_value from $wpdb->options where option_name='pay_method_paypal'";
        $paymentinfo = $wpdb->get_results($paymethodsql);
        if ($paymentinfo) {
            foreach ($paymentinfo as $paymentinfoObj) {
                $option_value = unserialize($paymentinfoObj->option_value);
                $paymentOpts = $option_value['payOpts'];
            }
        }
        $ipn = get_option('paypal_ipn');
         if (file_exists(VIEWPATH . "payment_option.php")):
            require_once(VIEWPATH . "payment_option.php");
        endif;
    }

}

?>
