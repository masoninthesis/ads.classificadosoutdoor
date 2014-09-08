<script type="text/javascript">
    jQuery(document).ready(function(){
        var Form = jQuery("#contactForm");
        var uname = jQuery('#contactName');
        var email = jQuery("#eMail");
        var message = jQuery("#commentsText");
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        function validate_uname(){
            if(uname.val() == ''){
                uname.addClass('error');
                uname.css('border', 'solid 1px red');
                uname.attr("placeholder","<?php echo REQUIRED_FIELD; ?>");
                return false;
            }else{
                uname.removeClass('error');
                uname.css("border","none");
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
                email.css("border","none");
                return true;
            }
        }
        email.blur(validate_email);
        email.keyup(validate_email);
                    
        function validate_message(){
            if(message.val() == ''){
                message.addClass("error");
                message.css("border","solid 1px red"); 
                message.attr("placeholder","<?php echo REQUIRED_FIELD; ?>");
                return false;
            }else{
                message.removeClass("error");
                message.css("border","none");
                return true;
            }
        }
        message.blur(validate_message);
        message.keyup(validate_message);
        Form.submit(function(){
            if(validate_uname() & validate_email() & validate_message()){
                return true;
            }else{
                return false;
            }
        });
    });   
</script>