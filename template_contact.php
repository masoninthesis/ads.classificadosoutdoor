<?php
/**
 * Template Name: Templact Contact
 */
?>
<?php
$nameError = '';
$emailError = '';
$commentError = '';
if (isset($_POST['submitted'])) {
    if (trim($_POST['contactName']) === '') {
        $nameError = ENTER_NAME;
        $hasError = true;
    } else {
        $name = trim($_POST['contactName']);
    }
    if (trim($_POST['email']) === '') {
        $emailError = ENTER_EMAIL;
        $hasError = true;
    } else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['email']))) {
        $emailError = EMAIL_ERR;
        $hasError = true;
    } else {
        $email = trim($_POST['email']);
    }
    if (trim($_POST['comments']) === '') {
        $commentError = ENTER_MSG;
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $comments = stripslashes(trim($_POST['comments']));
        } else {
            $comments = trim($_POST['comments']);
        }
    }
    if (trim($_POST['website']) != '') {
        $website = trim($_POST['website']);
    }
    if (!isset($hasError)) {
        $emailTo = get_option('tz_email');
        if (!isset($emailTo) || ($emailTo == '')) {
            $emailTo = get_option('admin_email');
        }
        $subject = 'From ' . $name;
        $body = "Name: $name \n\nEmail: $email \n\nWebsite: $website \n\nComments: $comments";
        $headers = 'From: ' . $name . ' <' . $emailTo . '>' . "\r\n" . 'Reply-To: ' . $email;
        mail($emailTo, $subject, $body, $headers);
        $emailSent = true;
    }
}
?>
<?php get_header(); ?>
<!--Start Cotent Wrapper-->
<div class="content_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_17 alpha">
                <!--Start Cotent-->
                <div class="content">
                    <h1 class="page_title"><?php the_title(); ?></h1>
                    <?php while (have_posts()) : the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; // end of the loop. ?>
                    <div id="contact">
                        <?php if (isset($emailSent) && $emailSent == true) { ?>
                            <div class="thanks">
                                <p><?php echo "Thanks, your email was sent successfully."; ?></p>
                            </div>
                        <?php } else { ?>
                            <?php if (isset($hasError) || isset($captchaError)) { ?>
                                <p class="error"><?php echo SORRY_ERROR; ?></p>
                            <?php } ?>
                            <form action="<?php the_permalink(); ?>" class="contactform" method="post" id="contactForm">
                                <label for="contactName"><?php echo UR_NM; ?> <span class="required"><?php echo "*"; ?></span></label>
                                <br/>
                                <?php if ($nameError != '') { ?>
                                    <span class="error"> <?php echo $nameError; ?> </span>
                                    <br/>
                                <?php } ?>                        
                                <input type="text" name="contactName" id="contactName" value="<?php if (isset($_POST['contactName'])) echo $_POST['contactName']; ?>" />
                                <span id="username_error"></span>
                                <br/>
                                <label for="eMail"><?php echo UR_EMAIL; ?> <span class="required"><?php echo "*"; ?></span></label>
                                <br/>
                                <?php if ($emailError != '') { ?>
                                    <span class="error"> <?php echo $emailError; ?> </span>
                                    <br/>
                                <?php } ?>                        
                                <input type="text" name="email" id="eMail" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" />
                                <span id="email_error"></span>
                                <br/>
                                <label for="website"><?php echo C_WEBSITE; ?><span><?php echo OPTIONAL; ?></span></label>
                                <br/>
                                <input class="text" type="text" id="website" name="website"  value="<?php if (isset($_POST['website'])) echo $_POST['website']; ?>"/>
                                <br/>
                                <label for="commentsText"><?php echo UR_MSG; ?> <span class="required"><?php echo "*"; ?></span></label>
                                <br/>
                                <?php if ($commentError != '') { ?>
                                    <span class="error"> <?php echo $commentError; ?> </span>
                                    <br/>
                                <?php } ?>
                                <textarea name="comments" id="commentsText" rows="20" cols="30"><?php
                            if (isset($_POST['comments'])) {
                                if (function_exists('stripslashes')) {
                                    echo stripslashes($_POST['comments']);
                                } else {
                                    echo $_POST['comments'];
                                }
                            }
                                ?></textarea>
                                <span id="comment_error"></span>
                                <br/>
                                <input  class="btnSubmit" type="submit" name="submit" value="<?php echo SUBMIT; ?>"/>
                                <input type="hidden" name="submitted" id="submitted" value="true" />
                            </form>
                        <?php } ?> 
                    </div>
                </div>
                <!--End Cotent-->
            </div>
           <?php require_once(TEMPLATEPATH.'/js/contact_validation.php'); ?>
            <div class="grid_7 omega">
                <div class="sidebar">
                    <?php
                    if (is_active_sidebar('contact-widget-area')) :
                        dynamic_sidebar('contact-widget-area');
                    endif;
                    ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--End Cotent Wrapper-->
<?php get_footer(); ?>