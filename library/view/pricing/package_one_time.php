<div class="group" id="of-option-onetime"> 
    <div class="section section-text ">
        <h3 class="heading"><?php echo PKG_TITLE; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="package_title" type="text" id="package_title" value="<?php echo $priceinfo[0]->price_title; ?>" class="of-input" />
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
                <textarea class="of-input" name="package_description" id="package_description" cols="8" rows="8"><?php echo stripslashes($priceinfo[0]->price_desc); ?></textarea>
                <br/>
                <span id="pkg_error"></span>
            </div>
            <div class="explain"><?php echo PKG_DDES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>                        
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo PKG_COST; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="package_cost" type="text" id="package_cost" value="<?php echo $priceinfo[0]->package_cost; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo PKG_COST_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo LISTING_ACT_PERIOD; ?></h3>
        <div class="option">
            <div class="controls">
                <input style="width:150px; margin-right: 15px;" name="package_period" type="text" id="package_period" value="<?php echo $priceinfo[0]->validity; ?>" class="of-biliing" />
                <select style="width:150px;" name="package_day" id="package_day">
                    <?php $valid_period = $priceinfo[0]->validity_per; ?>
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
            <div class="explain"><?php ACT_PERIOD_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo STATUS; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="package_status" class="of-input" id="package_status">
                    <?php $status = $priceinfo[0]->status; ?>
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
            <div class="explain"><?php echo STATUS_DES; ?>

</div>
            <div class="clear"> </div>
        </div>
    </div>                       
    <div class="section section-text saperator">
        <h3 class="heading"><?php echo PRICE_SETTING_F; ?></h3>                          
    </div>
    <p><?php echo PRICE_SETTING_F_DES; ?></p>
    <div class="section section-text ">
        <h3 class="heading"><?php echo F_LISTING_STATUS; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="feature_status" class="of-input" id="feature_status">
                    <?php $f_status = $priceinfo[0]->is_featured; ?>
                    <option value="1" <?php
                    if ($f_status == 1) {
                        echo $pstatus = 'selected="selected"';
                    } else {
                        echo $fstatus = '';
                    }
                    ?>><?php echo ACTIVE ?></option>
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
                <input name="feature_home" type="text" id="feature_home" value="<?php echo $priceinfo[0]->feature_amount; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo F_HOME_COST_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo F_CAT_COST; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="feature_cat" type="text" id="feature_cat" value="<?php echo $priceinfo[0]->feature_cat_amount; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo F_CAT_COST_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
</div>  