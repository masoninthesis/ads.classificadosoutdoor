<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function cc_admin_menu() {
    add_menu_page(__('ClassiCraft', THEME_SLUG), __('Settings', THEME_SLUG), 'read', basename(__FILE__), 'inkthemes_optionsframework_add_admin', TEMPLATEURL . '/images/setting.png', 4);
    add_submenu_page(basename(__FILE__), __('Theme Options', THEME_SLUG), __('Theme Options', THEME_SLUG), 'manage_options', 'optionsframework', 'inkthemes_optionsframework_options_page');
    add_submenu_page(basename(__FILE__), __('Pricing', THEME_SLUG), __('Pricing', THEME_SLUG), 'manage_options', 'pricing', 'cc_pricing_option');
    add_submenu_page(basename(__FILE__), __('Custom Field', THEME_SLUG), __('Custom Field', THEME_SLUG), 'manage_options', 'field', 'cc_cuctom_field_option');
    add_submenu_page(basename(__FILE__), __('Transaction', THEME_SLUG), __('Transaction', THEME_SLUG), 'manage_options', 'transaction', 'cc_show_transaction');
    add_submenu_page(basename(__FILE__), __('Lead Capture', THEME_SLUG), __('Leads Capture', THEME_SLUG), 'manage_options', 'leads', 'cc_show_leads');
    add_submenu_page(basename(__FILE__), __('Import Export', THEME_SLUG), __('Import Export', THEME_SLUG), 'manage_options', 'import', 'cc_import_csv');
    remove_submenu_page(basename(__FILE__), basename(__FILE__));
}

add_action('admin_menu', 'cc_admin_menu');

if (!function_exists('cc_cuctom_field_option')) {

    function cc_cuctom_field_option() {
        ?>
        <div class="wrap" id="of_container">
            <div id="of-popup-save" class="of-save-popup">
                <div class="of-save-save"></div>
            </div>
            <div id="of-popup-reset" class="of-save-popup">
                <div class="of-save-reset"></div>
            </div>
            <div id="header">
                <div class="logo">
                    <h2><?php echo CUSTOM_FIELDS; ?></h2>
                </div>
                <a href="http://www.inkthemes.com" target="_new">
                    <div class="icon-option"> </div>
                </a>
                <div class="clear"></div>
            </div>
            <form enctype="multipart/form-data" id="setting_form" name="setting_form" method="post">
                <div id="main">
                    <div id="of-nav">
                        <ul>
                            <li> <a  class="pn-view-a" href="#of-option-ccfield" title="Custom field"><?php echo CUSTOM_FIELDS; ?></a></li>                  
                        </ul>
                    </div>
                    <div id="content">
                        <?php
                        $file_name = array(
                            'custom_fields'
                        );
                        foreach ($file_name as $files):
                            if (file_exists(SETTINGPATH . $files . '.php')):
                                require_once(SETTINGPATH . $files . '.php');
                            endif;
                        endforeach;
                        $cc_field_obj = new cc_custom_fields();
                        if (!isset($_REQUEST['ref']) || $_REQUEST['action'] == 'mng_field') {
                            $cc_field_obj->cc_list_custom_field();
                        }
                        if (isset($_REQUEST['ref']) && $_REQUEST['ref'] == 'custom_field') {
                            $cc_field_obj->cc_add_custom_field();
                        }
                        if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'field' && isset($_REQUEST['ref']) && $_REQUEST['ref'] == 'cedit' && $_REQUEST['action'] == 'edit'):
                            $cc_field_obj->cc_edit_custom_field();
                        endif;
                        ?> 
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="save_bar_top">
                    <img style="display:none" src="<?php echo ADMINURL; ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
                    <input type="submit" id="submit_setting" name="submit" value="<?php echo SAVE_ALL; ?>" class="button-primary" />   
                </div>
            </form>          
        </div> 
        <!--wrap-->
        <?php
    }

}

if (!function_exists('cc_pricing_option')) {

    function cc_pricing_option() {
        ?>
        <div class="wrap" id="of_container">
            <div id="of-popup-save" class="of-save-popup">
                <div class="of-save-save"></div>
            </div>
            <div id="of-popup-reset" class="of-save-popup">
                <div class="of-save-reset"></div>
            </div>
            <div id="header">
                <div class="logo">
                    <h2><?php echo PS; ?></h2>
                </div>
                <a href="http://www.inkthemes.com" target="_new">
                    <div class="icon-option"> </div>
                </a>
                <div class="clear"></div>
            </div>
            <form enctype="multipart/form-data" id="setting_form" name="setting_form" method="post">
                <div id="main">
                    <div id="of-nav">
                        <ul>
                            <li> <a  class="pn-view-a" href="#of-option-free" title="Free Package"><?php echo FREE_PKG; ?></a></li>                  
                            <li> <a  class="pn-view-a" href="#of-option-onetime" title="One Time Package"><?php echo ONE_TIME_PKG; ?></a></li>    
                            <li> <a  class="pn-view-a" href="#of-option-recurring" title="Recurring Package"><?php echo RECURRING_PKG; ?></a></li> 
                            <li> <a  class="pn-view-a" href="#of-option-payment" title="Payment Option"><?php echo PAYMENT_SETTING; ?></a></li>
                            <li> <a  class="pn-view-a" href="#of-option-currency" title="Manage Currency"><?php echo MNG_CURRENCY; ?></a></li>
                        </ul>
                    </div>
                    <div id="content">
                        <?php
                        //Package option
                        $cc_package_obj = new cc_price_package();
                        $cc_package_obj->cc_free_package();
                        $cc_package_obj->cc_onetime_package();
                        $cc_package_obj->cc_recurring_package();
                        //Payment option
                        $cc_payment_obj = new cc_getway_option();
                        $cc_payment_obj->cc_paypal_getway_setting();
                        $cc_currency_obj = new cc_manage_currency();
                        $cc_currency_obj->cc_currency();
                        ?> 
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="save_bar_top">
                    <img style="display:none" src="<?php echo ADMINURL; ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
                    <input type="submit" id="submit_setting" name="submit" value="<?php echo SAVE_ALL; ?>" class="button-primary" />   
                </div>
            </form>          
        </div> 
        <!--wrap-->
        <?php
    }

}
//show transaction
function cc_show_transaction() {
    if (file_exists(SETTINGPATH . 'cc_show_transaction.php')):
        require_once(SETTINGPATH . 'cc_show_transaction.php');
    endif;
}
//show leads
function cc_show_leads() {
    if (file_exists(SETTINGPATH . 'cc_show_admin_leads.php')):
        require_once(SETTINGPATH . 'cc_show_admin_leads.php');
    endif;
}
//import and export csv
function cc_import_csv() {
    if (file_exists(SETTINGPATH . 'cc_import.php')):
        require_once(SETTINGPATH . 'cc_import.php');
    endif;
}