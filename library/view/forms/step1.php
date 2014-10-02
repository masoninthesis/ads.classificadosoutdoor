<form name="add_post_form" id="Add_post_form" action="" method="post" enctype="multipart/form-data">
    <?php
    $title = base64_encode(serialize($_POST['cc_title']));
    $description = base64_encode(serialize($_POST['cc_description']));
    ?>
    <input type="hidden" name="cc_title" value="<?php echo stripslashes($title); ?>"/>
    <input type="hidden" name="cc_description" value="<?php echo stripslashes($description); ?>"/>
    <input type="hidden" name="cc_tags" value="<?php echo stripslashes($_POST['cc_tags']); ?>"/>

    <!--Start Step2-->
    <div class="step2">
        <?php
        global $posted, $validation_field;
        $validation_field = array();
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
                //Required custom fields
                $validation_field[] = array(
                    'name' => $key,
                    'span' => $key . '_error',
                    'type' => $meta['type'],
                );
            }
            if ($type == 'text' || $type == 'cc_map_input') {
                if ($var_name == 'cc_latitude' || $var_name == 'cc_longitude') {
                    $script = 'onblur="changeMap();"';
                    $field_type = "hidden";
                } else {
                    $script = '';
                    $field_type = 'text';
                }
                ?>
                <div class="label">
                    <?php if($type == 'text') { ?>
                    <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
                    <?php } ?>
                </div>
                <div class="row">
                    <input type="<?php echo $field_type; ?>" id="<?php echo $var_name; ?>" name="<?php echo $var_name; ?>" <?php echo $script; ?> />
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
<!--                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>-->
                    </div>
                    <div class="row">
                        <input id="<?php echo $var_name; ?>" type="hidden" name="<?php echo $var_name; ?>" value=""/>                     
                        <?php include_once(LIBRARYPATH . "map/address_map.php"); ?> 
                        <br/>
                        <span class="description"><?php echo stripslashes($des); ?></span>
                    </div>
                </div>
                <!--End Row--> 
                <?php
            }
            if ($type == 'checkbox') {
                ?>
                <!--Start Row-->
                <div class="form_row">
                    <div class="label">
                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <input name="<?php echo $var_name; ?>" id="<?php echo $var_name; ?>" type="checkbox"  value="" />
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
                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <?php
                        if ($options) {
                            $chkcounter = 0;

                            //$option_values_arr = explode(',', $options);
                            for ($i = 0; $i < count($options); $i++) {
                                $chkcounter++;
                                $seled = '';
                                if ($default_value == $options[$i]) {
                                    $seled = 'checked="checked"';
                                }
                                if ($value == $options[$i]) {
                                    $seled = 'checked="checked"';
                                }
                                echo '<label class="r_lbl">
							<input name="' . $key . '"  id="' . $key . '" type="radio" value="' . $options[$i] . '" ' . $seled . '  /> ' . $options[$i] . '
						</label>';
                            }
                        }
                        ?>
                        <?php 
                         if ($meta['is_require'] == 1) {
                             echo '<div class="clear"></div>';
                            echo '<span id="' . $key . '_error"></span>';
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
                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
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
                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <textarea style="width:250px; height: 100px;" id="<?php echo $var_name; ?>" name="<?php echo $var_name; ?>" row="20" col="25"></textarea>
                        <p class="description"><?php echo $des; ?></p>
                        <?php 
                         if ($meta['is_require'] == 1) {
                            echo '<span id="' . $key . '_error"></span>';
                         }
                        ?>
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
                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">
                        <select name="<?php echo $var_name; ?>" id="<?php echo $var_name; ?>" class="textfield textfield>">
                            <?php
                            $options = $meta['option_values'];
                            if ($options) {
                                foreach ($options as $values) {
                                    ?>
                                    <option value="<?php echo $values; ?>" <?php
                                        if ($default == $values) {
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
                        <label for="<?php echo $var_name; ?>"><?php echo $title . $is_required; ?></label>
                    </div>
                    <div class="row">                       
                        <div style="margin-bottom: 0;">
                            <input class='of-input <?php echo $var_name; ?>' name='<?php echo $var_name; ?>' id='place_image1_upload' type='text' value='' />
                            <div style="display: inline;" class="upload_button_div"><input type="button" class="button image_upload_button" id="<?php echo $var_name; ?>" value="<?php echo UPLD_IMAGE; ?>" />                                                  
                                <div class="button image_reset_button hide" id="reset_<?php echo $var_name; ?>" title="<?php echo $var_name; ?>"></div>
                            </div> 
                            <div class="clear"></div>
                            <p class="description"><?php echo $des; ?></p>
                        </div>                        
                    </div>
                </div>
                <!--End Row-->
                <?php
                $count++;
            }
        }
        ?>
    </div>
    <div class="clear"></div>
    <input type="button" name="step" value="<?php echo GO_BACK; ?>" onclick="history.back()"/>
    <input type="submit" name="step2" value="<?php echo CONTI; ?>" onclick="tinyMCE.triggerSave()"/>                             
</form>
<!--End Step2-->
<?php if(file_exists(LIBRARYPATH.'js/custom_field_validation.php')){ require_once(LIBRARYPATH.'js/custom_field_validation.php');} ?>