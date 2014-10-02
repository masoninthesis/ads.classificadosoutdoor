<script type="text/javascript">
    jQuery(document).ready(function(){
        var adForm = jQuery("#Add_post_form"); 
        var cc_title = jQuery("#title");   
        var cc_desc = jQuery("#cc_desc");
        var cc_desc_error = jQuery(".desc_error");
        function validate_title(){
            if(cc_title.val() == ''){
                cc_title.addClass("error");
                cc_title.attr("placeholder", "<?php echo ENTER_TITLE; ?>");
                cc_title.css('border', 'solid 1px red');
                cc_title.css('background-color', '#ffece8');
                return false;
            }else{
                cc_title.removeClass("error");   
                cc_title.css('border', 'none');
                cc_title.css('background-color', '');
                return true;
            }
        }
        cc_title.blur(validate_title);
        cc_title.keyup(validate_title);
        function validate_desc(){
            if(cc_desc.val() == ""){
                cc_desc.addClass("error");
                cc_desc.css('border', 'solid 1px red');
                cc_desc_error.addClass("error");
                cc_desc_error.text("<?php echo ENTER_DES; ?>");
                return false;
            }else{
                cc_desc.removeClass("error");
                cc_desc_error.removeClass("error");
                cc_desc_error.text("");
                cc_desc.css('border', 'none');
                return true;
            }
        }
        cc_desc.blur(validate_desc);
        cc_desc.keyup(validate_desc);
    
        adForm.submit(function()
        {
            if(validate_title() & validate_desc())
            {
                return true;
            }
            else
            {
                return false;
            }
        });
    });
</script>