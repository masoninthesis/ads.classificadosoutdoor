<?php
$pid = $_REQUEST['pid'];
global $wpdb, $user_ID;
$update_msg = '';
if (isset($_POST['update'])) {
    $fields = array('cc_title', 'cc_description', 'cc_category', 'cc_tags');

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $posted[$field] = stripcslashes(trim($_POST[$field]));
        }
    }
    $category = explode(',', $posted['cc_category']);
    $tags = explode(',', $posted['cc_tags']);

    $post_status = "pending";
    $cc_my_post['ID'] = $pid;
    $cc_my_post['post_title'] = esc_attr($posted['cc_title']);
    $cc_my_post['post_content'] = $posted['cc_description'];
    $cc_my_post['post_status'] = $post_status;
    $cc_my_post['post_author'] = $user_ID;
    $cc_my_post['post_type'] = POST_TYPE;
    $cc_my_post['post_category'] = $category;
    $cc_my_post['tags_input'] = $tags;

    //set the custom field value
    $cc_custom_meta = cc_get_custom_field();
    foreach ($cc_custom_meta as $key => $meta) {
        $field = $meta['htmlvar_name'];
        if (isset($_POST[$field])) {
            $posted[$field] = stripcslashes(trim($_POST[$field]));
        }
    }
    wp_update_post($cc_my_post);
    //set the categories and tags
    wp_set_object_terms($pid, $category, $taxonomy = CUSTOM_CAT_TYPE);
    wp_set_object_terms($pid, $tags, $taxonomy = CUSTOM_TAG_TYPE);
    //Save custom field value
    $custom_meta = cc_get_custom_field();
    if ($custom_meta) {
        foreach ($custom_meta as $meta):
            update_post_meta($pid, $meta['htmlvar_name'], $posted[$meta['htmlvar_name']]);
        endforeach;
    }
    $update_msg = "Your ad has been updated.";
}
$post_type = POST_TYPE;
$sql = "SELECT * FROM $wpdb->posts WHERE post_type = '$post_type' AND ID = {$pid}";
$ad = $wpdb->get_row($sql);
?>
<?php if ($update_msg) { ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<div id="add_post">
    <form name="edit_post_form" id="edit_post_form" action="<?php $_SERVER[PHP_SELF]; ?>" method="post" enctype="multipart/form-data">
        <div class="label">
            <label><?echo AD_TTLE; ?></label>
        </div>
        <div class="row">
            <input type="text" name="cc_title" id="title" value="<?php echo $ad->post_title; ?>"/>
        </div>   
        <div class="label">
            <label><?php echo SLT_CAT; ?></label>
        </div>
        <div class="row">
            <?php
            $cates = array();
            $cates = wp_get_post_terms($pid, CUSTOM_CAT_TYPE);
            $count = count($cates);
            for ($i = 0; $i <= $count; $i++) {
                $allc = explode(',', $cates[$i]->term_id);
                if ($allc[0] != "") {
                    $locat .= $allc[0] . ",";
                }
            }
            $cates = explode(',', $locat);
            $taxonomies = array(CUSTOM_CAT_TYPE);
            $args = array('orderby' => 'count', 'hide_empty' => false);
            $myterms = get_terms($taxonomies, $args);
            $output = "<select name='cc_category'>";
            $selected = "";
            foreach ($myterms as $term) {
                $root_url = get_bloginfo('url');
                $term_taxonomy = $term->taxonomy;
                $termid = $term->term_id;
                $term_slug = $term->slug;
                $term_name = $term->name;
                if (in_array($termid, $cates)) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = "";
                }
                $link = $root_url . '/' . $term_taxonomy . '/' . $term_slug;
                $output .= "<option $selected  value='" . $term_slug . "'>" . $term_name . "</option>";
            }
            $output .= "</select>";
            echo $output;
            ?>
        </div>                               
        <div class="label">
            <label>Tags</label>
        </div>
        <div class="row">
            <?php
            $terms = get_the_terms($pid, CUSTOM_TAG_TYPE);
            $tags = '';
            if ($terms) {
                foreach ($terms as $term) {
                    $tags .= $term->name . ',';
                }
            }
            ?>
            <input type="text" name="cc_tags" value="<?php echo $tags; ?>"/>
            <p class="description"><?php echo "Tags are short keywords. with comas no space within. Up to 40 characters only."; ?></p>
        </div>
        <div class="label">
            <label><?php echo DESC; ?></label>
        </div>
        <div class="row">
            <textarea name="cc_description"><?php echo $ad->post_content; ?></textarea>
        </div>  
        <?php
        $cc_custom_meta = cc_get_custom_field();
        foreach ($cc_custom_meta as $key => $meta) {
            $title = $meta['field_title'];
            $var_name = $meta['htmlvar_name'];
            $var_id = $meta['id'];
            $des = $meta['description'];
            $options = $meta['option_values'];
            $type = $meta['type'];
            $is_required = '';
            $default = $meta['default'];
            if ($meta['is_require'] == 1) {
                $is_required = '<span class="required">*</span>';
            }
            if ($type == 'text' || $type == 'cc_map_input') {
                if ($var_name == 'cc_latitude' || $var_name == 'cc_longitude') {
                    $script = 'onblur="changeMap();"';
                } else {
                    $script = '';
                }
                ?>
                <div class="label">
                    <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                </div>
                <div class="row">
                    <input type="text" id="<?php echo $var_id; ?>" name="<?php echo $var_name; ?>" <?php echo $script; ?> value="<?php echo get_post_meta($pid, $var_name, true); ?>" />
                    <p class="description"><?php echo $des; ?></p>
                </div> 
                <?php
            }
            if ($type == 'cc_map') {
                if ($meta['is_require'] == 1) {
                    $is_required = '<span class="required">*</span>';
                }
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <input id="cc_address" type="text" name="<?php echo $var_name; ?>" value="<?php echo get_post_meta($pid, $var_name, true); ?>"/>                        <br/>
                        <span id="cc_address_rr" class="cc_address_error"></span>
                        <br/>
                        <?php include_once(LIBRARYPATH . "map/address_map.php"); ?> 
                        <br/>
                        <span class="description"><?php echo stripslashes($description); ?></span>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'checkbox') {
                $field = get_post_meta($pid, $var_name, true);
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <input name="<?php echo $var_name; ?>" id="<?php echo $var_id; ?>" <?php if ($field) echo 'checked="checked"'; ?> type="checkbox"  value="<?php echo get_post_meta($pid, $var_name, true); ?>" />
                        <p class="description"><?php echo $des; ?></p>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'radio') {
                ?>       
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <?php
                        if ($options) {
                            $chkcounter = 0;
                            $value = get_post_meta($pid, $var_name, true);
                            //$option_values_arr = explode(',', $options);
                            for ($i = 0; $i < count($options); $i++) {
                                $chkcounter++;
                                $seled = '';
                                if ($default_value == $options[$i]) {
                                    $seled = 'checked="checked"';
                                }
                                if (trim($value) == trim($options[$i])) {
                                    $seled = 'checked="checked"';
                                }
                                echo '<label class="r_lbl">
                                    <input name="' . $key . '"  id="' . $key . '_' . $chkcounter . '" type="radio" value="' . $options[$i] . '" ' . $seled . '  /> ' . $options[$i] . '
                            </label>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'multicheckbox') {
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <?php
                        if ($options) {
                            $chkcounter = 0;
                            echo '<div class="multicheck">';
                            for ($i = 0; $i < count($options); $i++) {
                                $chkcounter++;
                                $seled = '';
                                if ($default != '') {
                                    if (in_array($options[$i], $default)) {
                                        $seled = 'checked="checked"';
                                    }
                                }

                                echo '
                                <div class="multicheck_list">
                                        <label>
                                                <input name="' . $key . '[]"  id="' . $key . '_' . $chkcounter . '" type="checkbox" value="' . $options[$i] . '" ' . $seled . ' /> ' . $options[$i] . '
                                        </label>
                                </div>';
                            }
                            echo '</div>';
                        }
                        ?>
                        <p class="description"><?php echo $des; ?></p>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'textarea') {
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <textarea style="width:250px; height: 100px;" id="<?php echo $var_id; ?>" name="<?php echo $var_name; ?>" row="20" col="25"><?php echo get_post_meta($pid, $var_name, true); ?></textarea>
                        <p class="description"><?php echo $des; ?></p>
                        <span id="description_error" class="description_error"></span>
                    </div>
                </div>
                <!--End Row-->
                <?php
            }
            if ($type == 'select') {
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <select name="<?php echo $var_name; ?>" id="<?php echo $var_id; ?>" class="textfield textfield>">
                            <?php
                            $field = get_post_meta($pid, $var_name, true);
                            $options = $meta['option_values'];
                            if ($options) {
                                foreach ($options as $values) {
                                    ?>
                                    <option value="<?php echo $values; ?>" <?php
                    if ($field == $values) {
                        echo 'selected="selected"';
                    }
                                    ?>><?php echo $values; ?></option>
                                            <?php
                                        }
                                        ?>
                                    <?php } ?>

                        </select>
                        <p class="description"><?php echo $des; ?></p>
                    </div>
                </div>
                <!--End Row-->
                <?php
            }
            $count = 1;
            if ($type == 'image_uploader') {
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">                       
                        <div style="margin-bottom: 20px;">
                            <input class='of-input' name='<?php echo $var_name; ?>' id='place_image1_upload' type='text' value='<?php echo get_post_meta($pid, $var_name, true); ?>' />
                            <div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $var_name; ?>" value="<?php echo "Upload Image"; ?>" />                                                  
                                <div class="button image_reset_button hide" id="reset_<?php echo $var_name; ?>" title="<?php echo $var_name; ?>"></div>
                            </div>             
                        </div>
                        <p class="description"><?php echo $des; ?></p>
                    </div>
                </div>
                <!--End Row-->
                <?php
                $count++;
            }
        }
        ?>
        <input type="button"  onclick="window.location.href='<?php echo CC_DASHBOARD ?>'" value="<?php _e('Cancel', THEME_SLUG) ?>" />&nbsp;&nbsp;
        <input type="submit" name="update" value="<?php echo UPDT; ?>"/>
        <!--Start Row--> 
        <div class="form_row">
            <div class="label">
                <label><?php echo UPGRADE_NOTIFY; ?></label>
            </div>
            <div class="row">                                            
                <label><?php echo UPGRADE; ?><input type="checkbox" id="be_paid" name="be_paid" /></label> <br/>
                <span class="description"><?php echo UPGRADE_NOTIFY_DES; ?></span>
            </div>
        </div>
        <!--End Row--> 
    </form>
    <div class="clear"></div>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            jQuery('input:checkbox[name=be_paid]').change(function(){  
                if(this.checked){
                    jQuery("#upgrade_form").slideDown();
                } 
                else{
                    jQuery("#upgrade_form").slideUp();
                }
            });         
                                                          
        });
    </script>
    <form style="display:none;" name="upgrade_form" id="upgrade_form" action="<?php $_SERVER[PHP_SELF]; ?>" method="post" enctype="multipart/form-data">  
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
                    if ($package->package_type != 'pkg_free') {
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
                                            <span><?php echo VLD_UP_TO.'&nbsp;'.$package->validity . "&nbsp;" . $valid_to; ?></span>
                                        <?php } ?>
                                    </p></div></label>

                        </div>               
                        <?php
                        $count++;
                    }
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
        <input type="submit" name="upgrade" value="<?php echo UPGRADE; ?>"/>   
    </form>
</div>
<?php
if (isset($_POST['upgrade']) && $_POST['package_type'] != 'pkg_free') {
    global $post, $posted;
    $post_id = $_REQUEST['pid'];
    $posted['total_cost'] = $_POST['total_price'];
    //Add billing cycle fields
    if ($_POST['billing'] == 1):
        if (isset($_POST['f_period']) && $_POST['f_period'] != ''):
            $posted['f_period'] = $_POST['f_period'];
        endif;
        if (isset($_POST['f_cycle']) && $_POST['f_cycle'] != ''):
            $posted['f_cycle'] = $_POST['f_cycle'];
        endif;
        if (isset($_POST['installment']) && $_POST['installment'] != ''):
            $posted['installment'] = $_POST['installment'];
        endif;
        if (isset($_POST['s_price']) && $_POST['s_price'] != ''):
            $posted['s_price'] = $_POST['s_price'];
        endif;
        if (isset($_POST['s_period']) && $_POST['s_period'] != ''):
            $posted['s_period'] = $_POST['s_period'];
        endif;
        if (isset($_POST['s_cycle']) && $_POST['s_cycle'] != ''):
            $posted['s_cycle'] = $_POST['s_cycle'];
        endif;
        $posted['billing'] = $_POST['billing'];
    endif;
    $posted['package_title'] = $_POST['package_title'];
    if (($_POST['package_validity'] == 0 || $_POST['package_validity'] == '') && ($_POST['package_validity_per'] == '')):
        $posted['package_validity'] = $_POST['pkg_free'];
        $posted['package_validity_per'] = $_POST['pkg_period_one'];
    endif;

    global $posted_value;
    $posted_value = array(
        'listing_title' => $_POST['listing_title'],
        'total_cost' => $_POST['total_price'],
        'post_id' => $post_id,
        'p_method' => "paypal",
        'f_period' => $posted['f_period'],
        'f_cycle' => $posted['f_cycle'],
        'installment' => $posted['installment'],
        's_price' => $posted['s_price'],
        's_period' => $posted['s_period'],
        's_cycle' => $posted['s_cycle'],
        'billing' => $posted['billing']
    );

    //Updating expiry table
    if ($_POST['package_validity'] != '' && $_POST['package_validity_per'] != '') {
        global $wpdb, $expiry_tbl_name;
        $validity = $_POST['package_validity'];
        $validity_per = $_POST['package_validity_per'];
        $pkg_type = $_POST['package_type'];
        if ($pkg_type == '') {
            $pkg_type = 'pkg_free';
        }
        $current_date = date("Y-m-d H:i:s");
        $update_array = array(
            'listing_title' => $posted_value['listing_title'],
            'validity' => $validity,
            'validity_per' => $validity_per,
            'package_type' => $pkg_type
        );
        $wpdb->update($expiry_tbl_name, $update_array, array('pid' => $post_id));
    }
    //For featuring category or homepage
    $featured_home = '';
    $featured_cate = '';
    if ($_POST['feature_h']) {
        // $featured_home = 'on';
    }
    if ($_POST['feature_c']) {
        //$featured_cate = 'on';
    }

    //Apling payment api
    if (isset($_POST['upgrade'])) {
        if (isset($_POST['upgrade']) && $_POST['total_price'] > 0) {

            global $user_ID, $post, $posted, $wpdb, $posted_value;
            $paypalamount = $_POST['total_price'];
            $post_title = $ad->post_title;
            $paymentOpts = cc_get_payment_optins('paypal');
            $merchantid = $paymentOpts['merchantid'];
            $returnUrl = $paymentOpts['returnUrl'];
            $currency_code = get_option('currency_code');
            $pay_method = $_REQUEST['pay_method'];
            //Payment status page
            $page_id = get_option('cc_payment_stat');
            $returnUrl = site_url("?page_id=$page_id&post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
            $notify_url = site_url("?post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
            $cancel_return = site_url("?post_id=$post_id&post_title=$post_title&pay_method=$pay_method");
            $is_recurring = $paymentOpts['is_recurring']; //not showing
            $f_period = $_POST['f_period'];
            $f_cycle = $_POST['f_cycle'];
            $installment = $_POST['installment'];
            $s_price = $_POST['s_price'];
            $s_period = $_POST['s_period'];
            $s_cycle = $_POST['s_cycle'];
            $billing = $_POST['billing'];
            $recurring = $_POST['billing'];
            $billing_time = cc_get_billingtime();

            $paypalurl = '';
            if (isset($_REQUEST['paypal_mode']) && $_REQUEST['paypal_mode'] == 'sandbox') {
                $paypalurl = "https://www.sandbox.paypal.com/cgi-bin/webscr";
            } else {
                $paypalurl = "https://www.paypal.com/cgi-bin/webscr";
            }
            ?> 
            <form name="frm_payment_method" action="<?php echo $paypalurl; ?>" method="post">
                <input type="hidden" name="business" value="<?php echo $merchantid; ?>" />
                <!-- Instant Payment Notification & Return Page Details -->
                <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
                <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
                <input type="hidden" name="return" value="<?php echo $returnUrl; ?>" />
                <input type="hidden" name="rm" value="2" />
                <!-- Configures Basic Checkout Fields -->
                <input type="hidden" name="lc" value="" />
                <input type="hidden" name="no_shipping" value="1" />
                <input type="hidden" name="no_note" value="1" />
               <!-- <input type="hidden" name="custom" value="localhost" />-->
                <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
                <input type="hidden" name="page_style" value="paypal" />
                <input type="hidden" name="charset" value="utf-8" />
                <input type="hidden" name="item_name" value="<?php echo $post_title; ?>" />
                <?php if ($recurring == 1) { ?>
                   <!-- <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />-->
                   <!-- <input type="hidden" name="item_number" value="2" />-->
                    <input type="hidden" name="cmd" value="_xclick-subscriptions" />
                    <!-- Customizes Prices, Payments & Billing Cycle -->
                    <input type="hidden" name="src" value="1" />
                    <!-- Value for each installments -->
                    <?php if ($billing_time[0]->rebill_time == 1) { ?>
                        <input type="hidden" name="srt" value="<?php echo $installment; ?>" /> 
                    <?php } ?>
                   <!-- <input type="hidden" name="sra" value="5" />-->
                    <!-- First Price -->
                    <input type="hidden" name="a1" value="<?php echo $paypalamount; ?>" />
                    <!-- First Period -->
                    <input type="hidden" name="p1" value="<?php echo $f_period; ?>" />
                    <!-- First Period Cycle e.g: Days,Months-->
                    <input type="hidden" name="t1" value="<?php echo $f_cycle; ?>" />
                    <!-- Second Period Price-->
                    <input type="hidden" name="a3" value="<?php echo $s_price; ?>" />
                    <!-- Second Period -->
                    <input type="hidden" name="p3" value="<?php echo $s_period; ?>" />
                    <!-- Second Period Cycle -->
                    <input type="hidden" name="t3" value="<?php echo $s_cycle; ?>" />
                    <!-- Displays The PayPal® Image Button -->
                <?php } else { ?>
                    <input type="hidden" value="_xclick" name="cmd"/>
                    <input type="hidden" name="amount" value="<?php echo $paypalamount; ?>" />
                <?php } ?>
            </form>
            <div class="wrapper" >
                <div class="clearfix container_message">
                    <center><h1 class="head"><?php echo 'Processing.... Please Wait...'; ?></h1></center>
                    <center><img class="processing" src="<?php echo TEMPLATEURL . '/images/loading.gif'; ?>"/></center>
                </div>
            </div>
            <script>
                setTimeout("document.frm_payment_method.submit()",50); 
            </script> 
            <?php
        }
    }
}
?>