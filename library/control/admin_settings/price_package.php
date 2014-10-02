<?php

class cc_price_package {

    function cc_free_package() {
        global $wpdb, $price_table_name;
        if (isset($_REQUEST['of_save']) && 'reset' == $_REQUEST['of_save']) {
            global $wpdb, $price_table_name;
            $sql = "DROP TABLE IF EXISTS $price_table_name";
            $wpdb->query($wpdb->prepare($sql));
        }
        $get_pricing = $wpdb->get_results("SELECT * FROM $price_table_name");
        $pkg_free_id = $get_pricing[0]->pid;        
        //Free Package
        if (isset($_POST['submit'])) {
            $pkg_free = array();
            $pkg_free['price_title'] = esc_attr($_POST['package_ftitle']);
            $pkg_free['price_desc'] = esc_attr($_POST['package_fdescription']);
            $pkg_free['package_cost'] = esc_attr($_POST['package_fcost']);
            $pkg_free['validity'] = esc_attr($_POST['package_fperiod']);
            $pkg_free['validity_per'] = esc_attr($_POST['package_fday']);
            $pkg_free['status'] = esc_attr($_POST['package_fstatus']);
            $pkg_free['renewal_per'] = esc_attr($_POST['renewal_per']);
            $pkg_free['renewal_cycle'] = esc_attr($_POST['renewal_cycle']);
            if ($pkg_free_id != ''):
                $wpdb->update($price_table_name, $pkg_free, array('pid' => $pkg_free_id));
            endif;
        }
        if ($pkg_free_id != '') {
            global $wpdb, $price_table_name;
            $price_query = "SELECT * FROM $price_table_name WHERE pid=\"$pkg_free_id\"";
            $freeinfo = $wpdb->get_results($price_query);
        }
        if (file_exists(VIEWPATH . "pricing/package_free.php")):
            require_once(VIEWPATH . "pricing/package_free.php");
        endif;
    }

    function cc_onetime_package() {
        global $wpdb, $price_table_name;
        $get_pricing = $wpdb->get_results("SELECT * FROM $price_table_name");
        $pkg_one_time_id = $get_pricing[1]->pid;
        if (isset($_POST['submit'])) {
            $pkg_onetime = array();
            $pkg_onetime['price_title'] = esc_attr($_POST['package_title']);
            $pkg_onetime['price_desc'] = esc_attr($_POST['package_description']);
            $pkg_onetime['package_cost'] = esc_attr($_POST['package_cost']);
            $pkg_onetime['validity'] = esc_attr($_POST['package_period']);
            $pkg_onetime['validity_per'] = esc_attr($_POST['package_day']);
            $pkg_onetime['status'] = esc_attr($_POST['package_status']);
            $pkg_onetime['is_featured'] = esc_attr($_POST['feature_status']);
            $pkg_onetime['feature_amount'] = esc_attr($_POST['feature_home']);
            $pkg_onetime['feature_cat_amount'] = esc_attr($_POST['feature_cat']);
            if ($pkg_one_time_id != ''):
                $wpdb->update($price_table_name, $pkg_onetime, array('pid' => $pkg_one_time_id));
            endif;
        }
        
        if ($pkg_one_time_id != '') {
            global $wpdb, $price_table_name;
            $price_query = "SELECT * FROM $price_table_name WHERE pid=\"$pkg_one_time_id\"";
            $priceinfo = $wpdb->get_results($price_query);
        }
        if (file_exists(VIEWPATH . "pricing/package_one_time.php")):
            require_once(VIEWPATH . "pricing/package_one_time.php");
        endif;
    }
    function cc_recurring_package(){
        global $wpdb, $price_table_name;
        $get_pricing = $wpdb->get_results("SELECT * FROM $price_table_name");
        $pkg_recurring_id = $get_pricing[2]->pid;
         if (isset($_POST['submit'])) {
            $pkg_recurring = array();
            $pkg_recurring['price_title'] = esc_attr($_POST['package_title1']);
            $pkg_recurring['price_desc'] = esc_attr($_POST['package_description1']);
            $pkg_recurring['package_cost'] = esc_attr($_POST['package_cost1']);
            $pkg_recurring['validity'] = esc_attr($_POST['package_period1']);
            $pkg_recurring['validity_per'] = esc_attr($_POST['package_day1']);
            $pkg_recurring['status'] = esc_attr($_POST['package_status1']);            
            $pkg_recurring['first_billing_per'] = esc_attr($_POST['first_billing_per']);
            $pkg_recurring['first_billing_cycle'] = esc_attr($_POST['first_billing_cycle']);
            $pkg_recurring['rebill_time'] = esc_attr($_POST['rebill_time']);
            $pkg_recurring['rebill_period'] = esc_attr($_POST['rebill_period']);
            $pkg_recurring['second_price'] = esc_attr($_POST['second_price']);
            $pkg_recurring['second_billing_per'] = esc_attr($_POST['second_billing_per']);
            $pkg_recurring['second_billing_cycle'] = esc_attr($_POST['second_billing_cycle']);
            $pkg_recurring['is_featured'] = esc_attr($_POST['feature_status1']);
            $pkg_recurring['feature_amount'] = esc_attr($_POST['feature_home1']);
            $pkg_recurring['feature_cat_amount'] = esc_attr($_POST['feature_cat1']);
            if ($pkg_recurring_id != ''):
                $wpdb->update($price_table_name, $pkg_recurring, array('pid' => $pkg_recurring_id));
            endif;
        }
        
           if ($pkg_recurring_id != '') {
            global $wpdb, $price_table_name;
            $price_query = "SELECT * FROM $price_table_name WHERE pid=\"$pkg_recurring_id\"";
            $priceinfo2 = $wpdb->get_results($price_query);
        }
        if (file_exists(VIEWPATH . "pricing/package_recurring.php")):
            require_once(VIEWPATH . "pricing/package_recurring.php");
        endif;
    }

}

?>
