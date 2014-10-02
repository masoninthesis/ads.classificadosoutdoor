<!--Start Comment box-->
<?php
if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
    die('Please do not load this page directly. Thanks!');
if (post_password_required()) {
    ?>
    <p class="nocomments"><?php echo PW_PROTECT; ?></p>
    <?php
    return;
}
?>
<!-- You can start editing here. -->
<div id="commentsbox">
    <?php if (have_comments()) : ?>
        <h3 id="comments">
            <?php comments_number(NO_RESPONSE, ONE_RESPONSE, UNTIL_RESPONSE); ?>
            <?php echo SO_FAR; ?></h3>
        <ol class="commentlist">
            <?php wp_list_comments(); ?>
        </ol>
        <div class="comment-nav">
            <div class="alignleft">
                <?php previous_comments_link() ?>
            </div>
            <div class="alignright">
                <?php next_comments_link() ?>
            </div>
        </div>
    <?php else : // this is displayed if there are no comments so far ?>
        <?php if (comments_open()) : ?>
            <!-- If comments are open, but there are no comments. -->
        <?php else : // comments are closed  ?>
            <!-- If comments are closed. -->
            <p class="nocomments"><?php echo COMMENTS_ARE_CLOSED; ?></p>
        <?php endif; ?>
    <?php endif; ?>
    <?php if (comments_open()) : ?>
        <div id="comment-form">
            <div id="respond" class="rounded">
                <div class="cancel-comment-reply"> <small>
                        <?php cancel_comment_reply_link(); ?>
                    </small> </div>
                <?php if (get_option('comment_registration') && !is_user_logged_in()) : ?>
                    <p><?php echo U_MUST_BE; ?> <a href="<?php echo wp_login_url(get_permalink()); ?>"><?php echo LOGGED_IN; ?></a> <?php echo TO_COMMENT; ?></p>
                <?php else : ?>
                    <h2><?php echo COMMENT_MSG; ?></h2>
                    <form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
                        <?php if (is_user_logged_in()) : ?>
                            <p><?php echo LOGGEDIN; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="<?php echo LOGOUT_THIS; ?>"><?php echo LOG_OUT; ?> &raquo;</a></p>
                        <?php else : ?>
                            <label for="author"><?php echo NM; ?> <small>
                                    <?php if ($req) echo "(required)"; ?>
                                </small></label>
                            <input type="text" name="author" id="author" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" <?php if ($req) echo "aria-required='true'"; ?> />
                            <label for="email"><?php echo MAIL; ?> <small>
                                    <?php if ($req) echo REQUIRED; ?>
                                </small></label>
                            <input type="text" name="email" id="email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" <?php if ($req) echo "aria-required='true'"; ?> />
                            <label for="url"><?php echo C_WEBSITE; ?></label>
                            <input type="text" name="url" id="url" value="<?php echo esc_attr($comment_author_url); ?>" size="22" tabindex="3" />
                        <?php endif; ?>
                            <label for="comment"><?php echo MESSAGE; ?></label>
                        <textarea name="comment" id="comment" cols="100%" rows="10" tabindex="4"></textarea>
                        <br />
                        <input name="submit" type="submit" id="commentSubmit" tabindex="5" value="<?php echo SUBMIT; ?>" />
                        <?php comment_id_fields(); ?>
                        <?php do_action('comment_form', $post->ID); ?>
                    </form>
                <?php endif; // If registration required and not logged in ?>
            </div>
        </div>
    <?php endif; // if you delete this the sky will fall on your head  ?>
</div>
<!--End Comment box--> 