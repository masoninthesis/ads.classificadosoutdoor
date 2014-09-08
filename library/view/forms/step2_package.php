<form name="add_post_form" id="Add_post_form" action="" method="post" enctype="multipart/form-data">
    <div class="step3">
        <?php
        global $wpdb;
        $is_recurring = $is_recurring[0]['is_recurring'];

        $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_%' order by option_id limit 1";
        $paymentinfo = $wpdb->get_results($paymentsql);
        if ($paymentinfo) {
            ?>                           
            <ul id="payments">
                <?php
                $paymentOptionArray = array();
                $paymethodKeyarray = array();
                foreach ($paymentinfo as $paymentinfoObj) {
                    $paymentInfo = unserialize($paymentinfoObj->option_value);
                    if ($paymentInfo['isactive']) {
                        $paymethodKeyarray[] = $paymentInfo['key'];
                        $paymentOptionArray[$paymentInfo['display_order']][] = $paymentInfo;
                    }
                }
                ksort($paymentOptionArray);
                if ($paymentOptionArray) {
                    foreach ($paymentOptionArray as $key => $paymentInfoval) {
                        for ($i = 0; $i < count($paymentInfoval); $i++) {
                            $paymentInfo = $paymentInfoval[$i];
                            $jsfunction = 'onclick="showoptions(this.value);"';
                            $chked = '';
                            $chked = 'checked="checked"';
                            // if ($paymentInfo['isactive'] == 1):
                            ?>

                            <li id="<?php echo $paymentInfo['key']; ?>">
                                <label class="r_lbl">                                        
                                    <input  type="hidden" value="<?php echo $paymentInfo['key']; ?>" id="<?php echo $paymentInfo['key']; ?>_id" name="pay_method" <?php echo $chked; ?> />  
                                    <?php //echo $paymentInfo['name'] ?>
                                </label>                       

                            </li>
                            <?php
                            // endif;
                        }
                    }
                }
                ?>
            </ul>
        <?php } ?> 
            <h4 class="row_title"><?php echo SELECT_PACKAGE; ?></h4>
        <script type="text/javascript" src="<?php echo LIBRARYURL; ?>js/package_price.js"></script>
        <div class="form_row" id="packages_checkbox">                
            <?php
            global $wpdb, $price_table_name, $posted;
            $packages = $wpdb->get_results("SELECT * FROM $price_table_name WHERE status=1");
            $count = 1;
            if ($packages):
                foreach ($packages as $package):
                    $valid_to = $package->validity_per;
                    if ($valid_to == 'D'):
                        $valid_to = 'Days';
                    endif;
                    if ($valid_to == 'M'):
                        $valid_to = "Months";
                    endif;
                    if ($valid_to == 'Y'):
                        $valid_to = 'Years';
                    endif;
                    $rebill_time = $package->rebill_time;
                    ?>            
                    <div class="package">
                        <label><input type="radio" <?php if ($package->package_cost == 0) echo "checked='checked'"; ?> value="<?php echo $package->package_cost; ?>"  name="price_select" id="price_select<?php echo $count; ?>" />
                            <input type="hidden" class="f_home" name="f_home" value="<?php echo $package->feature_amount; ?>"/>
                            <input type="hidden" class="f_cate" name="f_cate" value="<?php echo $package->feature_cat_amount; ?>"/>
                            <input type="hidden" class="<?php echo $package->package_type; ?>" name="<?php echo $package->package_type; ?>" value="<?php echo $package->validity; ?>"/>
                            <input type="hidden" class="<?php echo $package->package_type_period; ?>" name="<?php echo $package->package_type_period; ?>" value="<?php echo $package->validity_per; ?>"/>
                            <input type="hidden" class="is_recurring" name="is_recurring" value="<?php echo $package->is_recurring; ?>"/>
                            <input type="hidden" class="validity" name="validity" value="<?php echo $package->validity; ?>"/>
                            <input type="hidden" class="validity_per" name="validity_per" value="<?php echo $package->validity_per; ?>"/>
                            <input type="hidden" class="pkg_type" name="pkg_type" value="<?php echo $package->package_type; ?>"/>
                            <?php
                            $is_recurring = $package->is_recurring;
                            if ($is_recurring == 1) {
                                ?>
                                <input type="hidden" class="f_period" name="f_period" value="<?php echo $package->first_billing_per; ?>"/>
                                <input type="hidden" class="f_cycle" name="f_cycle" value="<?php echo $package->first_billing_cycle; ?>"/>
                                <?php
                                $rebill_time = $package->rebill_time;
                                if ($rebill_time == 1) {
                                    ?>
                                    <input type="hidden" class="installment" name="installment" value="<?php echo $package->rebill_period; ?>"/>
                                <?php } ?>
                                <input type="hidden" class="s_price" name="s_price" value="<?php echo $package->second_price; ?>"/>
                                <input type="hidden" class="s_period" name="s_period" value="<?php echo $package->second_billing_per; ?>"/>
                                <input type="hidden" class="s_cycle" name="s_cycle" value="<?php echo $package->second_billing_cycle; ?>"/>
                            <?php } ?>     
                            <input type="hidden" class="is_featured" name="is_featured" value="<?php echo $package->is_featured; ?>"/>                            
                            <input type="hidden" id="price_title" name="price_title" value="<?php echo $package->price_title; ?>"/>
                            <div class="pkg_ct">                                
                                <h3><?php echo stripslashes($package->price_title); ?></h3>
                                <p><?php echo stripslashes($package->price_desc); ?></p>
                                <p class="cost"><span><?php _e('Cost : ', THEME_SLUG); ?><?php echo get_option('currency_symbol'); ?><?php echo $package->package_cost; ?></span>                       
                                    <?php
                                    if ($package->package_type == 'pkg_recurring') {
                                        if ($package->first_billing_cycle == "D"):
                                            $first_period = 'Day';
                                        elseif ($package->first_billing_cycle == 'M'):
                                            $first_period = 'Month';
                                        elseif ($package->first_billing_cycle == 'Y'):
                                            $first_period = 'Year';
                                        endif;
                                        if ($package->second_billing_cycle == "D"):
                                            $second_period = 'Days';
                                        elseif ($package->second_billing_cycle == 'M'):
                                            $second_period = 'Month';
                                        elseif ($package->second_billing_cycle == 'Y'):
                                            $second_period = 'Year';
                                        endif;
                                        if ($rebill_time == 1) {
                                            ?>
                                            <span><?php
                            printf('<span>'.BILLING_TERM1.'</span>', get_option('currency_symbol'), $package->package_cost, get_option('currency_code'), $package->first_billing_per, $first_period, get_option('currency_symbol'), $package->second_price, get_option('currency_code'), $package->second_billing_per, $second_period, $package->rebill_period);
                                            ?></span>  
                                            <?php
                                        } else {
                                            printf('<span>'.BILLING_TERM2.'</span>', get_option('currency_symbol'), $package->package_cost, get_option('currency_code'), $package->first_billing_per, $first_period, get_option('currency_symbol'), $package->second_price, get_option('currency_code'), $package->second_billing_per, $second_period);
                                        }
                                    } else {
                                        ?>
                                        <span> <?php echo VLD_UP_TO.'&nbsp;'.$package->validity . "&nbsp;" . $valid_to; ?></span>
                                    <?php } ?>
                                </p></div></label>

                    </div>               
                    <?php
                    $count++;
                endforeach;
            endif;
            ?>
        </div>
        <!--Start Row-->
        <div class="form_row" id="featured">
            <div class="label">
                <label><?php echo FEATURE_NOTIFY; ?></label>
            </div>
            <div class="field">              
                <label><input id="feature_h"  type="checkbox" name="feature_h"  value="0" /><?php echo FEATURE_HOME; ?> <span><?php echo get_option('currency_symbol'); ?></span><span id="fhome">0</span></label>
                <br/>                
                <label><input id="feature_c"  type="checkbox" name="feature_c"  value="0" /><?php echo FEATURE_CAT; ?><span><?php echo get_option('currency_symbol'); ?></span><span id="fcat">0</span></label>                
                <br/><br/><span style="display:block;" class="description"><?php echo FEATURE_DES; ?></span>                                 
            </div>
        </div>
        <!--End Row--> 
        <!--Start Row-->
        <div class="form_row">
            <div class="label">
                &nbsp;
            </div>
            <div class="field">
                <span id='loading' style='display:none;'><img src="<?php echo TEMPLATEURL . "/images/loading.gif"; ?>" alt='Loading..' /></span>
            </div> 
        </div>
        <!--Start Row-->
        <div class="form_row">
            <div class="label">
                <label><?php echo TTLE_PRICE; ?> <span class="required">*</span></label>

                <style type="text/css">.field span{font-size: 14px;}</style>
                <?php echo get_option('currency_symbol'); ?><span id="pkg_price">0</span>&nbsp;+&nbsp;<?php echo get_option('currency_symbol'); ?><span id="feature_price">0</span>&nbsp;=&nbsp;<?php echo get_option('currency_symbol'); ?><span id="result_price">0</span>
                <input type="hidden" name="billing" id="billing" value="0"/>
                <input type="hidden" name="total_price" id="total_price" value="0"/>  
                <input type="hidden" name="package_title" id="package_title" value=""/>
                <input type="hidden" name="package_validity" id="package_validity" value=""/>
                <input type="hidden" name="package_validity_per" id="package_validity_per" value=""/>
                <input type="hidden" name="package_type" id="package_type" value=""/>  
                <br/>
                <span class="description"><?php echo PREVIEW_NOTE; ?></span>
            </div>
        </div>
        <!--End Row-->  
    </div>
    <div class="clear"></div>
    <?php
    $get_paypal = get_option('pay_method_paypal');
    if (is_array($get_paypal)):
        $paymethod = $get_paypal['key'];
        $paypal_mode_status = $get_paypal['paypal_sandbox'];
    endif;
    $method = '';
    if ($paymethod == 'paypal') {
        if ($paypal_mode_status == 1)
            $method = "sandbox";
        else
            $method = "paypal";
    }
    ?>
    <input type="hidden" name="paypal_mode" value="<?php if ($method != '') echo $method; ?>"/>
    <input type="button" name="step1" value="<?php echo GO_BACK; ?>" onclick="history.back()"/>
    <input type="submit" name="cc_submit" value="<?php echo SUBMIT; ?>"/>
    <input type="hidden" name="step3" value="" />
    <input type="hidden" name="cc_check_submit" value="<?php echo rand(); ?>"/>
    <input type="hidden" value="<?php echo base64_encode(serialize($posted)); ?>" name="posted" />
</form>