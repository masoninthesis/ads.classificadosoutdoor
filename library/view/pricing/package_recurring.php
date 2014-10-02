<div class="group" id="of-option-recurring"> 
    <div class="section section-text ">
        <h3 class="heading"><?php echo PKG_TITLE; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="package_title1" type="text" id="package_title1" value="<?php echo $priceinfo2[0]->price_title; ?>" class="of-input" />
                <br/>
                <span id="pkg_error"></span>
            </div>
            <div class="explain"><?php echo PKG_TITLE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo PKG_DES; ?></h3>
        <div class="option">
            <div class="controls">
                <textarea class="of-input" name="package_description1" id="package_description1" cols="8" rows="8"><?php echo stripslashes($priceinfo2[0]->price_desc); ?></textarea>
                <br/>
                <span id="pkg_error"></span>
            </div>
            <div class="explain"><?php echo PKG_DDES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>                        
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo FIRST_PRICE; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="package_cost1" type="text" id="package_cost1" value="<?php echo $priceinfo2[0]->package_cost; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo FIRST_PRICE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
<!--    <div class="section section-text ">
        <h3 class="heading">Recurring</h3>
        <div class="option">
            <div class="controls">
                <select name="pay_recurring1" class="of-input" id="pay_recurring1">
                    <?php $recurring = $priceinfo2[0]->is_recurring; ?>
                    <option value="1" <?php
                    if ($recurring == 1) {
                        echo $srecur = 'selected="selected"';
                    } else {
                        echo $srecur = '';
                    }
                    ?>>Yes</option>
                    <option value="0" <?php
                    if ($recurring == 0) {
                        echo $srecur = 'selected="selected"';
                    } else {
                        echo $srecur = '';
                    }
                    ?>>No</option>
                </select>
            </div>

            <div class="explain">Specify whether users will be automatically charged after the billing period gets over.(NOTE: Only work for paypal)</div>
            <div class="clear"> </div>
        </div>
    </div>                       -->
    <div class="section section-text ">
        <h3 class="heading"><?php echo FIRST_PERIOD; ?></h3>
        <div class="option">
            <div class="controls">
                <input style="width:150px; margin-right: 15px;" name="first_billing_per" type="text" id="first_billing_per" value="<?php echo $priceinfo2[0]->first_billing_per; ?>" class="of-input" /> 
                <select style="width:150px;" name="first_billing_cycle" id="first_billing_cycle">
                    <?php $first_billing_cycle = $priceinfo2[0]->first_billing_cycle; ?>
                    <option value="D" <?php
                    if ($first_billing_cycle == 'D') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo DAYS; ?></option>
                    <option value="M" <?php
                    if ($first_billing_cycle == 'M') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo MONTHS; ?></option>
                    <option value="Y" <?php
                    if ($first_billing_cycle == 'Y') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo YEARS; ?></option>
                </select>
            </div>                                
            <div class="explain"><?php echo PERIOD_SUBS; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo REBILL_TIME; ?></h3>
        <div class="option">
            <div class="controls">
                <script type="text/javascript">
            jQuery(document).ready(function(){
                var rebill_period = jQuery('#rebill_period');
                var rebill_time = jQuery("#rebill_time option:selected").val();
                   if(rebill_time == 1){
                       rebill_period.show();
                   }else if(rebill_time == 0){
                       rebill_period.hide();
                   }
                jQuery('#rebill_time').change(function(){
                   var rebill_time = jQuery("#rebill_time option:selected").val();
                   if(rebill_time == 1){
                       rebill_period.show();
                   }else if(rebill_time == 0){
                       rebill_period.hide();
                   }
                });                
            });    
            </script>
                <select style="width:220px; margin-right: 15px;" name="rebill_time" id="rebill_time">
                    <?php $rebill_time = $priceinfo2[0]->rebill_time; ?>
                    <option value="1" <?php if ($rebill_time == 1) echo 'selected="selected"'; ?>><?php echo CHARGE_X_TIME; ?></option>
                    <option value="0" <?php if ($rebill_time == 0) echo 'selected="selected"'; ?>><?php echo CHARGE_UNTILL_CHARGE; ?></option>
                </select>
                <input style="width:100px;" name="rebill_period" type="text" id="rebill_period" value="<?php echo $priceinfo2[0]->rebill_period; ?>" class="of-input" />                                    
            </div>                                
            <div class="explain"><?php echo REBILL_TIME_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo SECOND_PRICE; ?></h3>
        <div class="option">
            <div class="controls">                                    
                <input name="second_price" type="text" id="second_price" value="<?php echo $priceinfo2[0]->second_price; ?>" class="of-input" />                                    
            </div>                                
            <div class="explain"><?php echo SECOND_PRICE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo SECOND_PERIOD; ?></h3>
        <div class="option">
            <div class="controls">                                    
                <input style="width:150px; margin-right: 15px;" name="second_billing_per" type="text" id="second_billing_per" value="<?php echo $priceinfo2[0]->second_billing_per; ?>" class="of-input" />                                    
                <select style="width:150px;" name="second_billing_cycle" id="second_billing_cycle">
                    <?php $second_billing_cycle = $priceinfo2[0]->second_billing_cycle; ?>
                    <option value="D" <?php
                    if ($second_billing_cycle == 'D') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo DAYS; ?></option>
                    <option value="M" <?php
                    if ($second_billing_cycle == 'M') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo MONTHS; ?></option>
                    <option value="Y" <?php
                    if ($second_billing_cycle == 'Y') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo YEARS; ?></option>
                </select>
            </div>                                
            <div class="explain"><?php echo SECOND_PERIOD_SUBS; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
<!--                        <div class="section section-text ">
        <h3 class="heading"><?php echo LISTING_ACT_PERIOD; ?></h3>
        <div class="option">
            <div class="controls">
                <input style="width:150px; margin-right: 15px;" name="package_period1" type="text" id="package_period1" value="<?php echo $priceinfo2[0]->validity; ?>" class="of-biliing" />
                <select style="width:150px;" name="package_day1" id="package_day">
                    <?php $valid_period = $priceinfo2[0]->validity_per; ?>
                    <option value="D" <?php
                    if ($valid_period == 'D') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo DAYS; ?></option>
                    <option value="M" <?php
                    if ($valid_period == 'M') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo MONTHS; ?></option>
                    <option value="Y" <?php
                    if ($valid_period == 'Y') {
                        echo $validate = 'selected="selected"';
                    } else {
                        echo $validate = '';
                    }
                    ?>><?php echo YEARS; ?></option>
                </select>
            </div>
            <div class="explain"><?php echo LISTING_ACT_PERIOD_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>   -->
    <div class="section section-text ">
        <h3 class="heading"><?php echo STATUS; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="package_status1" class="of-input" id="package_status1">
                    <?php $status = $priceinfo2[0]->status; ?>
                    <option value="1" <?php
                    if ($status == 1) {
                        echo $pstatus = 'selected="selected"';
                    } else {
                        echo $pstatus = '';
                    }
                    ?>><?php echo ACTIVE; ?></option>
                    <option value="0" <?php
                    if ($status == 0) {
                        echo $pstatus = 'selected="selected"';
                    } else {
                        echo $pstatus = '';
                    }
                    ?>><?php echo DEACTIVE; ?></option>
                </select>
            </div>
            <div class="explain"><?php echo STATUS_DES2; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text saperator">
        <h3 class="heading"><?php echo PRICE_SETTING_F; ?></h3>                          
    </div>
    <p><?php echo PRICE_SETTING_F_DES ?></p>
    <div class="section section-text ">
        <h3 class="heading"><?php echo F_LISTING_STATUS; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="feature_status1" class="of-input" id="feature_status">
                    <?php $f_status = $priceinfo2[0]->is_featured; ?>
                    <option value="1" <?php
                    if ($f_status == 1) {
                        echo $pstatus = 'selected="selected"';
                    } else {
                        echo $fstatus = '';
                    }
                    ?>><?php echo ACTIVE; ?></option>
                    <option value="0" <?php
                    if ($f_status == 0) {
                        echo $pstatus = 'selected="selected"';
                    } else if ($f_status == null) {
                        echo $fstatus = '';
                    }
                    ?>><?php echo DEACTIVE; ?></option>
                </select>
            </div>
            <div class="explain"><?php echo F_LISTING_STATUS_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo F_HOME_COST; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="feature_home1" type="text" id="feature_home" value="<?php echo $priceinfo2[0]->feature_amount; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo F_HOME_COST_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo F_CAT_COST; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="feature_cat1" type="text" id="feature_cat" value="<?php echo $priceinfo2[0]->feature_cat_amount; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo F_CAT_COST_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
</div> 