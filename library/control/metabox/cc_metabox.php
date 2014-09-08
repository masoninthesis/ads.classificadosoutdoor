<?php
function cc_custom_meta_box() {
    global $key;
    if (function_exists('add_meta_box')) {
        add_meta_box('custom-type-meta-boxes', AD_DETAILS, 'cc_custom_metabox', POST_TYPE, 'normal', 'high');        
    }
}

function cc_custom_metabox() {
    global $post, $cc_custom_meta;
    ?>
    <div id="panel-wrap">
        <script type="text/javascript">
                                                                                        
            // -- NO CONFLICT MODE --
            var $s = jQuery.noConflict();
            $s(function(){
                //AJAX Upload
                jQuery('.image_upload_button').each(function(){
                                                                                    			
                    var clickedObject = jQuery(this);
                    var clickedID = jQuery(this).attr('id');	
                    new AjaxUpload(clickedID, {
                        action: '<?php echo admin_url("admin-ajax.php"); ?>',
                        name: clickedID, // File upload name
                        data: { // Additional data to send
                            action: 'of_ajax_post_action',
                            type: 'upload',
                            data: clickedID },
                        autoSubmit: true, // Submit file after selection
                        responseType: false,
                        onChange: function(file, extension){},
                        onSubmit: function(file, extension){
                            clickedObject.text('Uploading'); // change button text, when user selects file	
                            this.disable(); // If you want to allow uploading only 1 file at time, you can disable upload button
                            interval = window.setInterval(function(){
                                var text = clickedObject.text();
                                if (text.length < 13){	clickedObject.text(text + '.'); }
                                else { clickedObject.text('Uploading'); } 
                            }, 200);
                        },
                        onComplete: function(file, response) {
                                                                                    				   
                            window.clearInterval(interval);
                            clickedObject.text('Upload Image');	
                            this.enable(); // enable upload button
                                                                                    					
                            // If there was an error
                            if(response.search('Upload Error') > -1){
                                var buildReturn = '<span class="upload-error">' + response + '</span>';
                                jQuery(".upload-error").remove();
                                clickedObject.parent().after(buildReturn);
                                                                                    					
                            }
                            else{
                                var buildReturn = '<img class="hide meta-image" id="image_'+clickedID+'" src="'+response+'" alt="" />';
                                jQuery(".upload-error").remove();
                                jQuery("#image_" + clickedID).remove();	
                                clickedObject.parent().after(buildReturn);
                                jQuery('img#image_'+clickedID).fadeIn();
                                clickedObject.next('span').fadeIn();
                                clickedObject.parent().prev('input').val(response);
                            }
                        }
                    });
                                                                                    			
                });
                //AJAX Remove (clear option value)
                jQuery('.image_reset_button').click(function(){
                                                                                			
                    var clickedObject = jQuery(this);
                    var clickedID = jQuery(this).attr('id');
                    var theID = jQuery(this).attr('title');	
                                                                                	
                    var ajax_url = '<?php echo admin_url("admin-ajax.php"); ?>';
                                                                                				
                    var data = {
                        action: 'of_ajax_post_action',
                        type: 'image_reset',
                        data: theID
                    };
                                                                                					
                    jQuery.post(ajax_url, data, function(response) {
                        var image_to_remove = jQuery('#image_' + theID);
                        var button_to_hide = jQuery('#reset_' + theID);
                        image_to_remove.fadeOut(500,function(){ jQuery(this).remove(); });
                        button_to_hide.fadeOut();
                        clickedObject.parent().prev('input').val('');
                    });
                                                                                					
                    return false; 
                                                                                					
                }); 
                                                                            		
                                                                            	
                                                                            	
            });
        </script>
        <div class="form-wrap">
            <?php
            $cc_custom_meta = cc_get_custom_field();
            foreach ($cc_custom_meta as $key => $meta) {
                global $post;
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
                $data = get_post_meta($post->ID, $var_name, true);
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
                        <?php if ($type !== 'cc_map_input') { ?>
                        <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                        <?php } ?>
                    </div>
                    <div class="row">
                        <input type="<?php echo $field_type; ?>" id="<?php echo $var_id; ?>" name="<?php echo $var_name; ?>" <?php echo $script; ?> value="<?php echo $data; ?>" />
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
<!--                            <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>-->
                        </div>
                        <div class="row">
                              <input id="cc_address" type="hidden" name="<?php echo $var_name; ?>" value=""/> 
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
                            <label for="<?php echo $var_id; ?>"><?php echo $title . $is_required; ?></label>
                        </div>
                        <div class="row">
                            <input name="<?php echo $var_name; ?>" id="<?php echo $var_id; ?>" type="checkbox"  value=""<?php echo $data; ?> />
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
                            $array = $options;
                            if ($array) {
                                    foreach ($array as $id => $option) {

                                        $checked = '';
                                        if ($meta_box['default'] == $option) {
                                            $checked = 'checked="checked"';
                                        }
                                        if (($data) == ($option)) {
                                            $checked = 'checked="checked"';
                                        }
                                        echo "\t\t" . '<input style="width:20px;" class="radio of-input" type="radio" ' . $checked . ' value="' . $option . '" name="' . $var_name . '" />  ' . $option . '' . "<br/>";
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
                            <textarea style="width:250px; height: 100px;" id="<?php echo $var_id; ?>" name="<?php echo $var_name; ?>" row="20" col="25"><?php echo $data; ?></textarea>
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
                                echo '<option>Select a ' . $title . '</option>';

                                $array = $options;

                                if ($array) {
                                    foreach ($array as $id => $option) {
                                        $selected = '';
                                        if ($meta['default'] == $option) {
                                            $selected = 'selected="selected"';
                                        }
                                        if ($data == $option) {
                                            $selected = 'selected="selected"';
                                        }
                                        echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                                    }
                                }?>

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
                            <div class="image_uploader" style="margin-right: 10px; min-width: 320px;">                               
                                <input class='of-input' name='<?php echo $var_name; ?>' id='<?php echo $var_name; ?>_upload' type='text' value='<?php echo htmlspecialchars($data); ?>' />
                                <div class="upload_button_div"><span class="button image_upload_button" id="<?php echo $var_name; ?>">Upload Image</span>
                                    <?php $hide = ($data != '') ? '' : 'hide'; ?>                               
                                    <span class="button image_reset_button <?php echo $hide; ?>" id="reset_<?php echo $var_name; ?>" title="<?php echo $var_name; ?>"><?php echo "Remove"; ?></span>
                                </div>
                                <?php if ($data != '') { ?>
                                    <img src="<?php echo $data; ?>" class="meta-image" id="image_<?php echo $var_name; ?>" style="display: inline; "/>
                                <?php } ?>
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
        </div>
    </div>	
    <?php
}

function cc_save_custommeta_box($post_id) {
    $cc_custom_meta = cc_get_custom_field();
    global $post, $meta_boxes, $key;
    if (!isset($_POST[$key . '_wpnonce']))
        return $post_id;
    if (!current_user_can('edit_post', $post_id))
        return $post_id;
    foreach ($cc_custom_meta as $custom_type) {
        update_post_meta($post_id, $custom_type['htmlvar_name'], $_POST[$custom_type['htmlvar_name']]);
        if ($custom_type['type'] == 'date') {
            $year = $_POST[$custom_type['htmlvar_name'] . '_year'];
            $month = $_POST[$custom_type['htmlvar_name'] . '_month'];
            $day = $_POST[$custom_type['htmlvar_name'] . '_day'];
            $hour = $_POST[$custom_type['htmlvar_name'] . '_hour'];
            $min = $_POST[$custom_type['htmlvar_name'] . '_min'];
            if (!$hour)
                $hour = '00';
            if (!$min)
                $min = '00';
            if (checkdate($month, $day, $year)) :
                $date = $year . $month . $day . ' ' . $hour . ':' . $min;
                update_post_meta($post_id, $custom_type['htmlvar_name'], strtotime($date));
            endif;
        }
    }
}

add_action('admin_menu', 'cc_custom_meta_box');
add_action('save_post', 'cc_save_custommeta_box');