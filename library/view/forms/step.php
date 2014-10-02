<?php if(file_exists(LIBRARYPATH.'js/ad_field_validation.php')) require_once(LIBRARYPATH.'js/ad_field_validation.php'); ?>
    <script type="text/javascript">
        function displaychk_frm(){
            dom = document.forms['add_post_form'];
            chk = dom.elements['category[]'];
            len = dom.elements['category[]'].length;
                                                                                                                                                	
            if(document.getElementById('selectall').checked == true) { 
                for (i = 0; i < len; i++)
                    chk[i].checked = true ;
            } else { 
                for (i = 0; i < len; i++)
                    chk[i].checked = false ;
            }
        }
    </script>
<form name="add_post_form" id="Add_post_form" action="" method="post" enctype="multipart/form-data">
    <!--Start Step1-->
    <div class="step1">
        <div class="label">
            <label><?php echo AD_TTLE; ?><span class="required">*</span></label>
        </div>
        <div class="row">
            <input type="text" name="cc_title" id="title"/>
        </div>   
        <div class="label">
            <label><?php echo SLT_CAT; ?></label>
        </div>
        <div class="row">
          <div id="ad-categories">						
                <div id="catlvl0">
                    <?php
                    wp_dropdown_categories('show_option_none=' . __('Select one', 'appthemes') . '&class=dropdownlist&orderby=name&order=ASC&hide_empty=0&hierarchical=1&taxonomy=' . CUSTOM_CAT_TYPE . '&depth=1');
                    ?>
                    <div style="clear:both;"></div>
                </div>
            </div>    
            <div id="ad-categories-footer">               
                <div id="chosenCategory"><input id="cat" name="cc_category" type="hidden" value="-1" /></div>
                <div style="clear:both;"></div>
            </div>  
        </div>                               
        <div class="label">
            <label><?php echo TAGS; ?></label>
        </div>
        <div class="row">
            <input type="text" name="cc_tags"/>
            <p class="description"><?php echo TAGS_DES; ?></p>
        </div>
        <div class="label">
            <label for="cc_desc"><?php echo DESC; ?><span class="required">*</span></label>
        </div>
        <div class="row">
            <textarea name="cc_description" id="cc_desc"></textarea>           
            <span class="desc_error"></span>
        </div>                             
    </div>
    <!--End Step1-->
    <input class="submit" type="submit" name="step1" value="<?php echo NEXT; ?>" onclick="tinyMCE.triggerSave()"/>
</form>