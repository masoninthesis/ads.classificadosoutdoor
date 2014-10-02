<?php

class cc_manage_currency {

    function cc_currency() {
        global $wpdb, $currency_tbl_name;
        $sql = "SELECT * FROM  $currency_tbl_name";
        $currencys = $wpdb->get_results($sql);

        if (isset($_POST['submit'])) {
            if (isset($_POST['currency_symbol']) && $_POST['currency_symbol'] != '') {
                $currency_symbol = $_POST['currency_symbol'];
                update_option('currency_code', $currency_symbol);
            }
        }
        if (file_exists(VIEWPATH . "currency.php")):
            require_once(VIEWPATH . "currency.php");
        endif;
    }

}

?>
