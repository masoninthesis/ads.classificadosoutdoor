<?php

global $wpdb, $table_prefix, $price_table_name, $cfield_tbl_name, $cc_leads_tbl_name;
$price_table_name = $table_prefix . "cc_price";
$cfield_tbl_name = $tblprefix . 'cc_custom_fields';

class cc_db_create extends cc_db_function {

    function __construct() {
        $this->cc_create_custom_field();
        $this->cc_create_pricine();
        $this->cc_leads();
        $this->cc_expire_ad();
    }

    function cc_create_custom_field() {
        global $wpdb;
        $tblprefix = $wpdb->prefix;
        /**
         * Create Table for custom field 
         */
        global $cfield_tbl_name;
        $cfield_tbl_name = $tblprefix . 'cc_custom_fields';

        if ($wpdb->get_var("SHOW TABLES LIKE \"$cfield_tbl_name\"") != $cfield_tbl_name) {
            $cfield_table = "CREATE TABLE " . $cfield_tbl_name . "(
                field_id int(8) NOT NULL AUTO_INCREMENT,
                field_category varchar(200) CHARACTER SET utf8 NOT NULL,
                field_type varchar(255) CHARACTER SET utf8 NOT NULL,
                option_value text NOT NULL,
                field_des text NOT NULL,
                field_title varchar(500) CHARACTER SET utf8 NOT NULL,
                field_var varchar(255) CHARACTER SET utf8 NOT NULL,
                defalut_value text NOT NULL,
                position_order int(12) NOT NULL,
                is_active int(10) NOT NULL,
                is_require int(10) NOT NULL,
                show_on_detail int(10) NOT NULL,
                show_free varchar(10) CHARACTER SET utf8 NOT NULL,
                html_id varchar(100) NOT NULL,
                PRIMARY KEY field_id(field_id)
        )";
            $wpdb->query($cfield_table);

            $custom_field_file = CSVPATH . "custom_field.csv";
            $custom_field = fopen($custom_field_file, 'r');
            $theData = fgets($custom_field);
            $i = 0;
            while (!feof($custom_field)) {
                $custom_field_data[] = fgetcsv($custom_field, 111024);
                $custom_field_array = $custom_field_data[$i];
                $insert_custom_field = array();
                $insert_custom_field['field_id'] = $custom_field_array[0];
                $insert_custom_field['field_category'] = $custom_field_array[1];
                $insert_custom_field['field_type'] = $custom_field_array[2];
                $insert_custom_field['option_value'] = $custom_field_array[3];
                $insert_custom_field['field_des'] = $custom_field_array[4];
                $insert_custom_field['field_title'] = $custom_field_array[5];
                $insert_custom_field['field_var'] = $custom_field_array[6];
                $insert_custom_field['defalut_value'] = $custom_field_array[7];
                $insert_custom_field['position_order'] = $custom_field_array[8];
                $insert_custom_field['is_active'] = $custom_field_array[9];
                $insert_custom_field['is_require'] = $custom_field_array[10];
                $insert_custom_field['show_on_detail'] = $custom_field_array[11];
                $insert_custom_field['show_free'] = $custom_field_array[12];
                $insert_custom_field['html_id'] = $custom_field_array[13];
                $i++;
                $wpdb->insert($cfield_tbl_name, $insert_custom_field);
            }
            fclose($custom_field);
        }
    }

    function cc_create_pricine() {
        global $wpdb, $table_prefix, $price_table_name;
        $price_table_name = $table_prefix . "cc_price";

        if ($wpdb->get_var("SHOW TABLES LIKE \"$price_table_name\"") != $price_table_name) {
            $price_table = 'CREATE TABLE IF NOT EXISTS ' . $price_table_name . ' (
	  `pid` int(11) NOT NULL AUTO_INCREMENT,
	  `price_title` varchar(255) CHARACTER SET utf8 NOT NULL,
	  `price_desc` varchar(1000) CHARACTER SET utf8 NOT NULL,
	  `price_post_cat` varchar(100) CHARACTER SET utf8 NOT NULL,
	  `is_show` varchar(10) NOT NULL,
	  `package_cost` int(255) NOT NULL,
	  `validity` int(10) NOT NULL,
	  `validity_per` varchar(10) CHARACTER SET utf8 NOT NULL,
	  `status` int(10) NOT NULL ,
	  `is_recurring` int(10) NOT NULL ,
          `first_billing_per` int(10) NOT NULL,
	  `first_billing_cycle` varchar(10) CHARACTER SET utf8 NOT NULL,
	  `rebill_time` int(10) NOT NULL,
          `rebill_period` int(10) NOT NULL,
          `second_price` int(255) NOT NULL,
          `second_billing_per` int(10) NOT NULL,
          `second_billing_cycle` varchar(10) CHARACTER SET utf8 NOT NULL,          	  
	  `is_featured` int(10) NOT NULL,
	  `feature_amount` int(10) NOT NULL,
	  `feature_cat_amount` int(10) NOT NULL,
          `package_type` varchar(100) CHARACTER SET utf8 NOT NULL,
          `package_type_period` varchar(100) CHARACTER SET utf8 NOT NULL,
	  PRIMARY KEY (`pid`)
	)';
            $wpdb->query($price_table);
            $insert_value1 = array(
                'pid' => 1,
                'price_title' => 'Free Business Ad',
                'price_desc' => "No Charge for placing your classified ad. You won't receive any leads. Ads Expires after 4 Months. You can reactivate ad later",
                'price_post_cat' => '',
                'is_show' => '1',
                'package_cost' => 0,
                'validity' => 4,
                'validity_per' => 'M',
                'status' => 1,
                'is_recurring' => '',
                'first_billing_per' => '',
                'first_billing_cycle' => '',
                'rebill_time' => '',
                'rebill_period' => '',
                'second_price' => '',
                'second_billing_per' => '',
                'second_billing_cycle' => '',
                'is_featured' => '',
                'feature_amount' => '',
                'feature_cat_amount' => '',
                'package_type' => 'pkg_free',
                'package_type_period' => 'pkg_period_one'
            );
            $insert_value2 = array(
                'pid' => 2,
                'price_title' => 'One Time Ad Payment',
                'price_desc' => "One time charge for placing your classified ad. You can feature the ad on Homepage slider or Category Page slider. You will receive leads in your Dashboard. Ads Expires after 6 Months. You can reactivate ad later for no extra charge.",
                'price_post_cat' => '',
                'is_show' => 1,
                'package_cost' => 26,
                'validity' => 6,
                'validity_per' => 'M',
                'status' => 1,
                'is_recurring' => '',
                'first_billing_per' => '',
                'first_billing_cycle' => '',
                'rebill_time' => '',
                'rebill_period' => '',
                'second_price' => '',
                'second_billing_per' => '',
                'second_billing_cycle' => '',
                'is_featured' => 1,
                'feature_amount' => 12,
                'feature_cat_amount' => 10,
                'package_type' => 'pkg_one_time',
                'package_type_period' => 'pkg_period_two'
            );
            $insert_value3 = array(
                'pid' => 3,
                'price_title' => "Recurring Ad Payment",
                'price_desc' => "Small recurring charge for placing your classified ad. You can feature the classified ad on Homepage slider or Category Page slider. You will receive enquiries from leads in your dashboard. No Ad expiry till you have active subscription.",
                'price_post_cat' => '',
                'is_show' => 1,
                'package_cost' => 36,
                'validity' => 6,
                'validity_per' => 'M',
                'status' => 1,
                'is_recurring' => 1,
                'first_billing_per' => 1,
                'first_billing_cycle' => 'M',
                'rebill_time' => 1,
                'rebill_period' => 12,
                'second_price' => 15,
                'second_billing_per' => 6,
                'second_billing_cycle' => 'M',
                'is_featured' => 1,
                'feature_amount' => 22,
                'feature_cat_amount' => 15,
                'package_type' => 'pkg_recurring',
                'package_type_period' => 'pkg_period_three'
            );
            $wpdb->insert($price_table_name, $insert_value1);
            $wpdb->insert($price_table_name, $insert_value2);
            $wpdb->insert($price_table_name, $insert_value3);
        }
    }

    function cc_leads() {
        global $wpdb, $cc_leads_tbl_name;
        $tblprefix = $wpdb->prefix;
        $cc_leads_tbl_name = $tblprefix . 'cc_leads';

        if ($wpdb->get_var("SHOW TABLES LIKE \"$cc_leads_tbl_name\"") != $cc_leads_tbl_name) {
            $clead_table = "CREATE TABLE " . $cc_leads_tbl_name . "(
                ID int(8) NOT NULL AUTO_INCREMENT,
                lead_name varchar(200) CHARACTER SET utf8 NOT NULL,
                lead_email varchar(50) CHARACTER SET utf8 NOT NULL,
                lead_message text NOT NULL,  
                post_author int(8) NOT NULL,
                PRIMARY KEY ID(ID)
        )";
            $wpdb->query($clead_table);
        }
    }

    /**
     * Create table for expiring ads
     * Authentication.
     */
    function cc_expire_ad() {
        global $wpdb, $table_prefix;
        global $expiry_tbl_name;
        $expiry_tbl_name = $table_prefix . 'cc_expiry';
        if ($wpdb->get_var("SHOW TABLES LIKE \"$expiry_tbl_name\"") != $expiry_tbl_name) {
            $activation_table = "CREATE TABLE " . $expiry_tbl_name . "(
                    ID INT(100) NOT NULL AUTO_INCREMENT,
                    pid INT(100) DEFAULT NULL,
                    listing_title VARCHAR(100) CHARACTER SET utf8 DEFAULT NULL,
                    validity INT(11) DEFAULT NULL,
                    validity_per VARCHAR(11) CHARACTER SET utf8 DEFAULT NULL,
                    listing_date datetime DEFAULT NULL,
                    `package_type` varchar(100) CHARACTER SET utf8 NOT NULL,
                    PRIMARY KEY (ID)
                    )";
            $wpdb->query($activation_table);
        }
    }

}

add_action('init', 'cc_db');

// initialization of object 	
function cc_db() {
    global $cc_db;

    $cc_db = new cc_db_create();
}

/**
 * Create Payment methods and their each value 
 * Store in wp_options 
 */
//Paypal
$payout = array();
$payout[] = array(
    "title" => 'Merchant Id',
    "fieldname" => "merchantid",
    "value" => "myaccount@paypal.com",
    "description" => MERCHANT_DES,
);
$payout[] = array(
    "title" => 'Cancel Url',
    "fieldname" => "cancel_return",
    "value" => site_url(""),
    "description" => sprintf(__(EXMPL . " %s", THEME_SLUG), site_url("")),
);
$payout[] = array(
    "title" => 'Return Url',
    "fieldname" => "returnUrl",
    "value" => site_url(""),
    "description" => sprintf(__(EXMPL . " %s", THEME_SLUG), site_url("")),
);
$payout[] = array(
    "title" => 'Notify Url',
    "fieldname" => "notify_url",
    "value" => site_url(""),
    "description" => sprintf(__(EXMPL . " %s", THEME_SLUG), site_url("")),
);

$paymethods[] = array(
    "name" => __('Paypal', THEME_SLUG),
    "key" => 'paypal',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '1',
    "paypal_sandbox" => '0',
    "payOpts" => $payout,
);
//Google checkout
$payout = array();
$payout[] = array(
    "title" => MERCHANT_ID_TEXT,
    "fieldname" => "merchantid",
    "value" => "1234567890",
    "description" => __(EXMPL . " 1234567890", THEME_SLUG)
);

$paymethods[] = array(
    "name" => 'Google Checkout',
    "key" => 'googlechkout',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '2',
    "payOpts" => $payout,
);

//Authorize.net
$payout = array();
$payout[] = array(
    "title" => LOGIN_ID_TEXT,
    "fieldname" => "loginid",
    "value" => "yourname@domain.com",
    "description" => LOGIN_ID_NOTE
);
$payout[] = array(
    "title" => TRANS_KEY_TEXT,
    "fieldname" => "transkey",
    "value" => "1234567890",
    "description" => TRANS_KEY_NOTE,
);

$paymethods[] = array(
    "name" => __('Authorize.net', THEME_SLUG),
    "key" => 'authorizenet',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '3',
    "payOpts" => $payout,
);

//Worldpay
$payout = array();
$payout[] = array(
    "title" => INSTANT_ID_TEXT,
    "fieldname" => "instId",
    "value" => "123456",
    "description" => INSTANT_ID_NOTE
);
$payout[] = array(
    "title" => ACCOUNT_ID_TEXT,
    "fieldname" => "accId1",
    "value" => "12345",
    "description" => ACCOUNT_ID_NOTE
);

$paymethods[] = array(
    "name" => WORLD_PAY_TEXT,
    "key" => 'worldpay',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '4',
    "payOpts" => $payout,
);
//////////worldpay end////////
//////////2co start////////

$payout = array();
$payout[] = array(
    "title" => VENDOR_ID_TEXT,
    "fieldname" => "vendorid",
    "value" => "1303908",
    "description" => VENDOR_ID_NOTE
);
$payout[] = array(
    "title" => NOTIFY_URL_TEXT,
    "fieldname" => "ipnfilepath",
    "value" => site_url(""),
    "description" => sprintf(__("Example : %s", THEME_SLUG), site_url("")),
);

$paymethods[] = array(
    "name" => __('2CO (2Checkout)', THEME_SLUG),
    "key" => '2co',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '5',
    "payOpts" => $payout,
);

//Pre bank transfer
$payout = array();
$payout[] = array(
    "title" => BANK_INFO_TEXT,
    "fieldname" => "bankinfo",
    "value" => "State Bank Of India",
    "description" => BANK_INFO_NOTE
);
$payout[] = array(
    "title" => ACCOUNT_ID_TEXT,
    "fieldname" => "bank_accountid",
    "value" => "AB1234567890",
    "description" => ACCOUNT_ID_NOTE2,
);

$paymethods[] = array(
    "name" => PRE_BANK_TRANSFER_TEXT,
    "key" => 'prebanktransfer',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '6',
    "payOpts" => $payout,
);

//Pay cash on delivery
$payout = array();
$paymethods[] = array(
    "name" => PAY_CASH_TEXT,
    "key" => 'payondelevary',
    "isactive" => '1', // 1->display,0->hide
    "display_order" => '7',
    "payOpts" => $payout,
);

//Insert options in payment optiions

for ($i = 0; $i < count($paymethods); $i++) {
    $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_" . $paymethods[$i]['key'] . "' order by option_id asc";
    $paymentinfo = $wpdb->get_results($paymentsql);
    if (count($paymentinfo) == 0) {
        $paymethodArray = array(
            "option_name" => 'pay_method_' . $paymethods[$i]['key'],
            "option_value" => serialize($paymethods[$i]),
        );
        $wpdb->insert($wpdb->options, $paymethodArray);
    }
}

/**
 * Create table for payment currencies
 * 
 */
if (!get_option('currency_symbol')) {
    add_option('currency_symbol', '$');
}

if (!get_option('currency_code')) {
    add_option('currency_code', 'USD');
}

global $wpdb, $table_prefix;
$currency_tbl_name = $table_prefix . 'cc_currency';
global $currency_tbl_name;
if ($wpdb->get_var("SHOW TABLES LIKE \"$currency_tbl_name\"") != $currency_tbl_name) {
    $currency_table = "CREATE TABLE " . $currency_tbl_name . "(
                c_id int(8) NOT NULL AUTO_INCREMENT,
                c_name varchar(100) CHARACTER SET utf8 NOT NULL,
                c_code varchar(10) CHARACTER SET utf8 NOT NULL,
                c_symbol varchar(10) CHARACTER SET utf8 NOT NULL,
                c_des varchar(500) CHARACTER SET utf8 NOT NULL,
                PRIMARY KEY c_id(c_id)
        )";
    $wpdb->query($currency_table);

    $currency_file = CSVPATH . "currency.csv";
    $currency = fopen($currency_file, 'r');
    $theData = fgets($currency);
    $i = 0;
    while (!feof($currency)) {

        $currency_data[] = fgets($currency, 1024);
        $currency_array = explode(",", $currency_data[$i]);
        $insert_currency = array();
        $insert_currency['c_id'] = $currency_array[0];
        $insert_currency['c_name'] = $currency_array[1];
        $insert_currency['c_code'] = $currency_array[2];
        $insert_currency['c_symbol'] = $currency_array[3];
        $insert_currency['c_des'] = $currency_array[4];
        $wpdb->insert($currency_tbl_name, $insert_currency);
        $i++;
    }
    fclose($currency);
}

/**
 * Transaction table for recording all payment transaction 
 */
global $wpdb, $table_prefix;
$transection_table_name = $table_prefix . "cc_transactions";
global $transection_table_name;
if ($wpdb->get_var("SHOW TABLES LIKE \"$transection_table_name\"") != $transection_table_name) {
    $transaction_table = 'CREATE TABLE IF NOT EXISTS `' . $transection_table_name . '` (
	`trans_id` bigint(20) NOT NULL AUTO_INCREMENT,
	`user_id` bigint(20) NOT NULL,
	`post_id` bigint(20) NOT NULL,
	`post_title` varchar(255) CHARACTER SET utf8 NOT NULL,
	`status` int(2) NOT NULL,
	`payment_method` varchar(255) CHARACTER SET utf8 NOT NULL,
	`payable_amt` float(25,2) NOT NULL,
	`payment_date` datetime NOT NULL,
	`paypal_transection_id` varchar(255) NOT NULL,
	`user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	`pay_email` varchar(255) CHARACTER SET utf8 NOT NULL,
	`billing_name` varchar(255) CHARACTER SET utf8 NOT NULL,
	`billing_add` text CHARACTER SET utf8 NOT NULL,
	PRIMARY KEY (`trans_id`)
	)';
    $wpdb->query($transaction_table);
}
global $price_table_name, $wpdb;
//Add column renewal_per for renew listing
$renewal_per = $wpdb->get_var("SHOW COLUMNS FROM $price_table_name LIKE 'renewal_per'");
if (!isset($renewal_per)) {
    $wpdb->query("ALTER TABLE $price_table_name  ADD `renewal_per` INT(10) NOT NULL AFTER `is_recurring`");
}
//Add column renewal_cycle for renew listing
$renewal_cycle = $wpdb->get_var("SHOW COLUMNS FROM $price_table_name LIKE 'renewal_cycle'");
if (!isset($renewal_cycle)) {
    $wpdb->query("ALTER TABLE $price_table_name  ADD `renewal_cycle` VARCHAR(10) NOT NULL AFTER `renewal_per`");
}
//Transaction Table
global $transection_table_name;
//Add column payment type on transaction table 
$txn_type = $wpdb->get_var("SHOW COLUMNS FROM $transection_table_name LIKE 'txn_type'");
if (!isset($txn_type)) {
    $wpdb->query("ALTER TABLE $transection_table_name  ADD `txn_type` VARCHAR(100) NOT NULL AFTER `payment_date`");
}

//Add column post_title on leads table
$cc_leads_tbl_name = $wpdb->prefix.'cc_leads';
$post_title = $wpdb->get_var("SHOW COLUMNS FROM $cc_leads_tbl_name LIKE 'lead_ad_title'");
if (!isset($post_title)) {
    $wpdb->query("ALTER TABLE $cc_leads_tbl_name  ADD `lead_ad_title` VARCHAR(255) NOT NULL AFTER `lead_name`");
}