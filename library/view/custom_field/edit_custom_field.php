<script type="text/javascript">
    function show_option_add(htmltype){
        if(htmltype=='select' || htmltype=='multiselect' || htmltype=='radio' || htmltype=='multicheckbox')	{
            document.getElementById('opt_value').style.display='';		
        }else{
            document.getElementById('opt_value').style.display='none';	
        }                            
    }
    if(document.getElementById('feild_type').value){
        show_option_add(document.getElementById('feild_type').value)	;
    }
</script>
<div class="group" id="of-option-ccfield">
    <div class="section section-text">
        <h3 class="heading"><?php echo EDIT_FIELD; ?></h3>         
        <a href="<?php echo admin_url("admin.php?page=field&action=mng_field#of-option-customfields"); ?>"><?php echo BACK_TO_MNG_FIELD; ?></a>      
    </div>
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo "Field Type"; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="feild_type" class="of-input" id="feild_type" onchange="show_option_add(this.value)" >                        
                    <option value="text" <?php if ($cfields->field_type == 'text') echo 'selected="selected"' ?>><?php echo TEXT; ?></option>
                    <option value="radio" <?php if ($cfields->field_type == 'radio') echo 'selected="selected"' ?>><?php echo RADIO; ?></option>
                    <option value="select" <?php if ($cfields->field_type == 'select') echo 'selected="selected"' ?>><?php echo SELECT; ?></option>
                    <option value="textarea" <?php if ($cfields->field_type == 'textarea') echo 'selected="selected"' ?>><?php echo TEXTAREA; ?></option>  
<!--                    <option value="cc_map" <?php if ($cfields->field_type == 'cc_map') echo 'selected="selected"' ?>><?php echo "Map"; ?></option>-->
                    <option value="image_uploader" <?php if ($cfields->field_type == 'image_uploader') echo 'selected="selected"' ?>><?php echo IMG_UPLOADER; ?></option>                      
                </select>
            </div>
            <div class="explain"><?php echo FIELD_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"></div>
    <div class="section section-text" id="opt_value" style="<?php if ($cfields->option_value != '') echo "display:;"; else echo "display:none;"; ?>">
        <h3 class="heading"><?php echo OPTION_VALUES; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="option_value" id="option_value" value="<?php echo $cfields->option_value; ?>" />
            </div>
            <div class="explain"><?php echo OPTION_VALUES_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>    
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo NAME_FIELD; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="field_title" id="field_title" value="<?php echo $cfields->field_title; ?>" />
            </div>
            <div class="explain"><?php echo NAME_FIELD_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo HTML_VAR_NAME; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="htmlvar_name" id="htmlvar_name" value="<?php echo $cfields->field_var; ?>" />
            </div>
            <div class="explain"><?php echo HTML_VAR_NAME_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div> 
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo FIELD_ID; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="html_id" id="field_id" value="<?php echo $cfields->html_id; ?>" />
            </div>
            <div class="explain"><?php echo FIELD_ID_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo DES; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="field_des" id="field_des" value="<?php echo $cfields->field_des; ?>" />
            </div>
            <div class="explain"><?php echo DES_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"> </div>
    <!--    <div class="section section-text ">
            <h3 class="heading"><?php echo "Default value"; ?></h3>
            <div class="option">
                <div class="controls">
                    <input class="of-input" type="text" name="field_default" id="field_default" value="<?php echo $cfields->defalut_value; ?>" />
                </div>
                <div class="explain"><?php echo "Enter the default value of the custom field."; ?></div>
                <div class="clear"> </div>
            </div>
        </div>  
        <div class="clear"> </div>-->
    <div class="section section-text ">
        <h3 class="heading"><?php echo POSITION; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="field_position" id="field_position" value="<?php echo $cfields->position_order; ?>" />
            </div>
            <div class="explain"><?php echo POSITION_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo ACTIVE; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="is_active" id="is_active">
                    <option <?php if ($cfields->is_active == 0) echo 'selected="selected"'; ?> value="0"><?php echo NO; ?></option>
                    <option <?php if ($cfields->is_active == 1) echo 'selected="selected"'; ?>value="1"><?php echo YES; ?></option>                
                </select>
            </div>
            <div class="explain"><?php echo ACTIVE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo REQUIRED; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="is_require" id="is_require">
                    <option <?php if ($cfields->is_require == 0) echo 'selected="selected"'; ?> value="0"><?php echo NO; ?></option>
                    <option <?php if ($cfields->is_require == 1) echo 'selected="selected"'; ?> value="1"><?php echo YES; ?></option>                
                </select>
            </div>
            <div class="explain"><?php echo REQUIRED_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo SHOW_DETAIL_PAGE; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="show_on_detail" id="show_on_detail">
                    <option <?php if ($cfields->show_on_detail == 0) echo 'selected="selected"'; ?> value="0"><?php echo NO; ?></option>
                    <option <?php if ($cfields->show_on_detail == 1) echo 'selected="selected"'; ?> value="1"><?php echo YES; ?></option>                
                </select>
            </div>
            <div class="explain"><?php echo SHOW_DETAIL_PAGE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"> </div>
    <?php
    global $wpdb, $cfield_tbl_name;
    $social = $wpdb->get_row("SELECT * FROM $cfield_tbl_name WHERE field_id = $id", ARRAY_A);
    if ($social['field_type'] !== 'image_uploader') {
        ?>
        <div class="section section-text ">
            <h3 class="heading"><?php echo ENABLE_FREE; ?></h3>
            <div class="option">
                <div class="controls">
                    <label><input style="width: auto;" class="of-input" type="checkbox" name="show_free" <?php if (strtoupper($cfields->show_free) == strtoupper('true')) echo 'checked="checked"'; ?> value="true"/>
                        <?php echo FREE_AD; ?></label>
                </div>
                <div class="explain"><?php echo ENABLE_FREE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div> 
<?php } ?>
</div>