<?php
/**
 * This file shows free package 
 */
?>
<div class="group" id="of-option-free"> 
    <div class="section section-text ">
        <h3 class="heading"><?php echo PKG_TITLE; ?></h3>
        <div class="option">
            <div class="controls">
                <input name="package_ftitle" type="text" id="package_ftitle" value="<?php echo $freeinfo[0]->price_title; ?>" class="of-input" />
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
                <textarea class="of-input" name="package_fdescription" id="package_fdescription" cols="8" rows="8"><?php echo stripslashes($freeinfo[0]->price_desc); ?></textarea>
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
                <input name="package_fcost" type="text" id="package_fcost" value="<?php echo $freeinfo[0]->package_cost; ?>" class="of-input" />
            </div>
            <div class="explain"><?php echo PKG_FREE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo LISTING_ACT_PERIOD; ?></h3>
        <div class="option">
            <div class="controls">
                <input style="width:150px; margin-right: 15px;" name="package_fperiod" type="text" id="package_fperiod" value="<?php echo $freeinfo[0]->validity; ?>" class="of-biliing" />
                <select style="width:150px;" name="package_fday" id="package_fday">
                    <?php $valid_period = $freeinfo[0]->validity_per; ?>
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
            <div class="explain"><?php echo ACT_FREE_DES; ?>
            </div>
            <div class="clear"> </div>
        </div>
    </div>
        <div class="section section-text ">
        <h3 class="heading"><?php echo "Ad Renewal Period"; ?></h3>
        <div class="option">
            <div class="controls">
                <input style="width:150px; margin-right: 15px;" name="renewal_per" type="text" id="renewal_per" value="<?php echo $freeinfo[0]->renewal_per; ?>" class="of-biliing" />
                <select style="width:150px;" name="renewal_cycle" id="renewal_cycle">
                    <?php $valid_period = $freeinfo[0]->renewal_cycle; ?>
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
            <div class="explain"><?php echo "Set the period for renewing the expired ad."; ?>
            </div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo STATUS; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="package_fstatus" class="of-input" id="package_fstatus">
                    <?php $status = $freeinfo[0]->status; ?>
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
            <div class="explain"><?php echo STATUS_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div> 
</div>  