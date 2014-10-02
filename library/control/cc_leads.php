<?php

function cc_captcha1() {
    return rand(0, 9);
}

function cc_captcha2() {
    return rand(0, 9);
}

$captcha1 = cc_captcha1();
$captcha2 = cc_captcha2();
$cc_sbs_captcha = $captcha1 + $captcha2;
$success = false;
if (isset($_POST['submit'])) {
    $nameError = '';
    $emailError = '';
    $messageError = '';
    $captchaError = '';
    //validate name
    if (trim($_POST['uname']) === '') {
        $nameError = ENTER_NAME;
        $hasError = true;
    } else {
        $name = esc_attr(trim($_POST['uname']));
    }
    //validate email
    if (trim($_POST['email']) === '') {
        $emailError = ENTER_EMAIL;
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
        $emailError = EMAIL_ERR;
        $hasError = true;
    } else {
        $email = esc_attr(trim($_POST['email']));
    }
    //validate message
    if (trim($_POST['message']) === '') {
        $messageError = ENTER_MSG;
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $message = stripslashes(trim($_POST['message']));
        } else {
            $message = trim($_POST['message']);
        }
    }
    //validate captcha 
    if ($_POST['captcha'] !== $_POST['captchacode']) {
        $hasError = true;
        $captchaError = ENTER_CAPTCHA;
    }
    $validate = $_POST['is_lead'];
    //Globlazation the required variables
    global $wpdb, $cc_leads_tbl_name, $post;
    //Get ad type
    $ad_type = get_post_meta($post->ID, 'cc_add_type', true);
    $is_lead = get_option('is_lead');
    if (empty($is_lead)) {
        add_option('is_lead');
    }
    //validate and prevent to enter duplicate field
    if ($is_lead !== $validate && !isset($hasError)) {
        //insertion in leads field
        $wpdb->insert(
                $cc_leads_tbl_name, array(
            'lead_name' => $name,
            'lead_ad_title' => $post->post_title,
            'lead_email' => $email,
            'lead_message' => $message,
            'post_author' => $post->post_author
        ));
        $success = true;
        update_option('is_lead', $validate);
        //If the add is premium type, email notification will be sent to the post author mail box.
        if ($ad_type == 'pro') {
            $email_message .="Ad Title: " . $post->post_title . "\n";
            $email_message .="User Name: " . $name . "\n";
            $email_message .="Message: " . $message . "\n";
            $email_message .="Email: " . $email . "\n";
            $to = get_the_author_meta('user_email', $post->post_author);
            $subject = 'Your ad subscription notification';
            $headers = 'From: ' . $name . ' <' . $email . '>' . "\r\n" . 'Reply-To: ' . $email;
            wp_mail($to, $subject, $email_message, $headers);
        }
    } else {
        $success = false;
    }
}
?>
<!--Start sidebar_widget-->
<div class="sidebar_widget">
    <div class="contact_widget">
        <div class="subscribe_form">
            <h5 class="contact_header"><?php echo CONTACT_PERSON; ?></h5>
            <div class="form_element">
                <form id="lead_form" action="<?php $_SERVER[PHP_SELF]; ?>" method="post" enctype="multipart/form-data">
                    <input placeholder="<?php echo NM; ?>" id="uname" type="text" name="uname"/>
                    <?php if ($nameError != '') { ?>
                        <span class="error"><?php echo $nameError; ?></span>
                    <?php } ?>
                    <input placeholder="<?php echo EMAIL; ?>" id="eMail" type="text" name="email"/> 
                    <?php if ($emailError != '') { ?>
                        <span class="error"><?php echo $emailError; ?></span>
                    <?php } ?>
                    <textarea placeholder="<?php echo MSG; ?>" id="message" name="message"></textarea>
                    <?php if ($messageError != '') { ?>
                        <span class="error"><?php echo $messageError; ?></span>
                    <?php } ?>
                    <input class="captcha" id="captcha" placeholder="<?php echo ETR_CAPTCHA; ?>" type="text" name="captcha" value=""/>
                    <span style="" class="show_captcha"><?php echo $captcha1 . ' + ' . $captcha2; ?></span>
                    <div class="clear"></div>
                    <?php if ($captchaError != '') { ?>
                        <span class="error"><?php echo $captchaError; ?> </span>
                    <?php } ?>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                        <?php
                        if ($success == true) {
                            echo "alert('".LEAD_NOTIFY."');";
                        }
                        ?>
                            });
                    </script>
                    <input type="hidden" name="captchacode" value="<?php echo $cc_sbs_captcha; ?>"/>
                    <input type="hidden" name="is_lead" value="<?php echo rand(); ?>"/>                   
                    <input type="submit" name="submit"/>                    
                </form>
                <?php require_once(LIBRARYPATH.'js/lead_capture_validation.php'); ?>
            </div>
        </div>
    </div>
</div>
<!--End sidebar_widget-->