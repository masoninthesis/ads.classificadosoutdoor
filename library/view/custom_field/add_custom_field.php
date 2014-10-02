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
        <h3 class="heading"><?php echo AD_NEW_FIELD; ?></h3>         
        <a href="<?php echo admin_url("admin.php?page=field&action=mng_field#of-option-customfields"); ?>"><?php echo BACK_TO_MNG_FIELD; ?></a>      
    </div>
    <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo FIELD_TYPE; ?></h3>
        <div class="option">
            <div class="controls">
                <select name="feild_type" class="of-input" id="feild_type" onchange="show_option_add(this.value)" >                        
                    <option value="text"><?php echo TEXT; ?></option>
                    <option value="radio"><?php echo RADIO; ?></option>
                    <option value="select"><?php echo SELECT; ?></option>
                    <option value="textarea"><?php echo TEXTAREA; ?></option> 
<!--                    <option value="cc_map"><?php echo "Map"; ?></option>-->
                    <option value="image_uploader"><?php echo IMG_UPLOADER; ?></option>                      
                </select>
            </div>
            <div class="explain"><?php echo FIELD_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
    <div class="clear"></div>
    <div class="section section-text" id="opt_value" style="display:none;">
        <h3 class="heading"><?php echo OPTION_VALUES; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="option_value" id="option_value" value="" />
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
                <input class="of-input" type="text" name="field_title" id="field_title" value="" />
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
                <input class="of-input" type="text" name="htmlvar_name" id="htmlvar_name" value="" />
            </div>
            <div class="explain"><?php echo HTML_VAR_NAME_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div> 
<!--      <div class="clear"> </div>
    <div class="section section-text ">
        <h3 class="heading"><?php echo FIELD_ID; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="html_id" id="htmlvar_name" value="" />
            </div>
            <div class="explain"><?php echo FIELD_ID_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>
    <div class="clear"> </div>-->
    <div class="section section-text ">
        <h3 class="heading"><?php echo DES; ?></h3>
        <div class="option">
            <div class="controls">
                <input class="of-input" type="text" name="field_des" id="field_des" value="" />
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
                <input class="of-input" type="text" name="default_value" id="default_value" value="" />
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
                <input class="of-input" type="text" name="sort_order" id="sort_order" value="" />
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
                    <option value="0"><?php echo NO; ?></option>
                    <option value="1"><?php echo YES; ?></option>                
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
                    <option value="0"><?php echo NO; ?></option>
                    <option value="1"><?php echo YES; ?></option>                
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
                    <option value="0"><?php echo NO; ?></option>
                    <option value="1"><?php echo YES; ?></option>                
                </select>
            </div>
            <div class="explain"><?php echo SHOW_DETAIL_PAGE_DES; ?></div>
            <div class="clear"> </div>
        </div>
    </div>  
     <div class="clear"> </div>
         <div class="section section-text ">
            <h3 class="heading"><?php echo ENABLE_FREE; ?></h3>
            <div class="option">
                <div class="controls">
                    <label><input style="width: auto;" class="of-input" type="checkbox" name="show_free" value="true"/>
                    <?php echo FREE_AD; ?></label>
                </div>
                <div class="explain"><?php echo ENABLE_FREE_DES; ?></div>
                <div class="clear"> </div>
            </div>
        </div> 
</div>