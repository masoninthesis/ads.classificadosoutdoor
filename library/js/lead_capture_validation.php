<script type="text/javascript">
    jQuery(document).ready(function(){
        var Form = jQuery("#lead_form");
        var uname = jQuery('#uname');
        var email = jQuery("#eMail");
        var message = jQuery("#message");
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var captcha = jQuery('#captcha');
        function validate_uname(){
            if(uname.val() == ''){
                uname.addClass('error');
                uname.css('border', 'solid 1px red');
                uname.attr("placeholder","<?php echo REQUIRED_FIELD; ?>");
                return false;
            }else{
                uname.removeClass('error');
                uname.css("border","1px solid #c3c3c3");
                return true;
            }
        }
        uname.blur(validate_uname);
        uname.keyup(validate_uname);        
        function validate_email(){
            if(email.val() == ''){
                email.addClass('error');
                email.css("border", "solid 1px red");
                email.attr("placeholder","<?php echo REQUIRED_FIELD; ?>");
                return false;
            }else if(!emailReg.test(email.val())){
                email.addClass("error");
                email.css("border", "solid 1px red");
                email.attr("placehoder", "<?php echo VALID_EMAIL; ?>");
                return false;
            }else{
                email.removeClass("error");
                email.css("border","1px solid #c3c3c3");
                return true;
            }
        }
        email.blur(validate_email);
        email.keyup(validate_email);
                    
        function validate_message(){
            if(message.val() == ''){
                message.addClass("error");
                message.attr("placeholder","<?php echo REQUIRED_FIELD; ?>");
                message.css("border","solid 1px red"); 
                return false;
            }else{
                message.removeClass("error");
                message.css("border","1px solid #c3c3c3");
                return true;
            }
        }
        message.blur(validate_message);
        message.keyup(validate_message);
        function validate_captcha(){
            if(captcha.val() == ''){
                captcha.addClass("error");
                captcha.attr("placeholder","<?php echo REQUIRED_FIELD; ?>");
                captcha.css("border","solid 1px red"); 
                return false;
            }else{
                captcha.removeClass("error");
                captcha.css("border","1px solid #c3c3c3");
                return true;
            } 
        }
        captcha.blur(validate_captcha);
        captcha.keyup(validate_captcha);
        Form.submit(function(){
            if(validate_uname() & validate_email() & validate_message() & validate_captcha()){
                return true;
            }else{
                return false;
            }
        });
    });   
</script>
