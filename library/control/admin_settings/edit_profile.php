<?php
global $current_user;
$status = false;
$update_msg = '';
if (isset($_POST['edit_profile'])):
    $user_meta = array();
    $user_meta['first_name'] = esc_attr($_POST['fname']);
    $user_meta['last_name'] = esc_attr($_POST['lname']);
    $user_meta['nickname'] = esc_attr($_POST['nname']);
    $user_meta['user_email'] = esc_attr($_POST['email']);
    $user_meta['user_url'] = esc_attr($_POST['website']);
    $user_meta['aim'] = esc_attr($_POST['aim']);
    $user_meta['yim'] = esc_attr($_POST['yahoo']);
    $user_meta['jabber'] = esc_attr($_POST['gtalk']);
    $user_meta['description'] = esc_attr($_POST['abtme']);
    //Update user data
    foreach ($user_meta as $meta_key => $meta_value):
        if ($meta_value != ''):
            update_user_meta($current_user->ID, $meta_key, $meta_value);
            $status = true;
            $update_msg = P_UPDATE_NOTIFY;
        endif;
    endforeach;

    /* Update user password. */
    if (!empty($_POST['npass']) && !empty($_POST['passagain'])) {
        if ($_POST['npass'] == $_POST['passagain']) {
            wp_update_user(array('ID' => $current_user->ID, 'user_pass' => esc_attr($_POST['npass'])));
            $status = true;
            $update_msg = PWD_UPDATE_NOTIFY;
        } else {
            echo "<p class='error'>'".PWD_DONT_MATCH."</p>";
            $status = false;
        }
    }
endif;
?>
<?php if($update_msg){ ?><p class="notification"><?php echo $update_msg; ?></p> <?php } ?>
<h1><?php echo "Edit Profile"; ?></h1>
<div id="add_post">
    <form name="edit_profile_form" id="edit_profile_form" action="<?php $_SERVER[PHP_SELF]; ?>" method="post" enctype="multipart/form-data">
        <div class="label">
            <label for="user"><?php echo USR_NM; ?></label>
        </div>
        <div class="row">
            <input type="text" id="user" name="user" readonly="readonly" value="<?php echo get_the_author_meta('user_login', $current_user->ID); ?>"/>
            <span class="description"><?php echo USR_NM_DES; ?></span>
        </div> 
        <div class="label">
            <label for="first_name"><?php echo FIRST_NM; ?></label>
        </div>
        <div class="row">
            <input type="text" id="first_name" name="fname" value="<?php echo get_the_author_meta('first_name', $current_user->ID); ?>"/>
            <span class="description"><?php echo FRIST_NM_DES; ?></span>
        </div>
        <div class="label">
            <label for="lname"><?php echo LAST_NAME; ?></label>
        </div>
        <div class="row">
            <input type="text" id="lname" name="lname" value="<?php echo get_the_author_meta('last_name', $current_user->ID); ?>"/>
            <span class="description"><?php echo LAST_NAME_DES; ?></span>
        </div>
        <div class="label">
            <label for="nname"><?php echo NICK_NM; ?></label>
        </div>
        <div class="row">
            <input type="text" id="nname" name="nname" value="<?php echo get_the_author_meta('nickname', $current_user->ID); ?>"/>
            <span class="description"><?php echo NICK_NM_DES; ?></span>
        </div>
        <div class="label">
            <label for="email"><?php echo UR_EMAIL; ?></label>
        </div>
        <div class="row">
            <input type="text" id="email" name="email" value="<?php echo get_the_author_meta('user_email', $current_user->ID); ?>"/>
            <span class="description"><?php echo UR_EMAIL_DES; ?></span>
        </div>
        <div class="label">
            <label for="website"><?php echo WEBSITE; ?></label>
        </div>
        <div class="row">
            <input type="text" id="website" name="website" value="<?php echo get_the_author_meta('url', $current_user->ID); ?>"/>
            <span class="description"><?php echo WEBSITE_DES; ?></span>
        </div>
        <div class="label">
            <label for="aim"><?php echo AIM; ?></label>
        </div>
        <div class="row">
            <input type="text" id="aim" name="aim" value="<?php echo get_the_author_meta('aim', $current_user->ID); ?>"/>
            <span class="description"><?php echo AIM_DES; ?></span>
        </div>
        <div class="label">
            <label for="yahoo"><?php echo YAHOO_IM; ?></label>
        </div>
        <div class="row">
            <input type="text" id="yahoo" name="yahoo" value="<?php echo get_the_author_meta('yim', $current_user->ID); ?>"/>
            <span class="description"><?php echo YAHOO_DES; ?></span>
        </div>
        <div class="label">
            <label for="gtalk"><?php echo GTALK; ?></label>
        </div>
        <div class="row">
            <input type="text" id="gtalk" name="gtalk" value="<?php echo get_the_author_meta('jabber', $current_user->ID); ?>"/>
            <span class="description"><?php echo GTALK_DES; ?></span>
        </div>
        <div class="label">
            <label for="abtme"><?php echo ABT; ?></label>
        </div>
        <div class="row">
            <textarea id="abtme" name="abtme"><?php echo get_the_author_meta('description', $current_user->ID); ?></textarea>
            <span class="description"><?php echo ABT_DES; ?></span>
        </div>
        <div class="label">
            <label for="npass"><?php echo NW_PWD; ?></label>
        </div>
        <div class="row">
            <input type="password" id="npass" name="npass" value=""/>
            <span class="description"><?php echo NW_PWD_DES; ?></span>
        </div>
        <div class="label">
            <label for="passagain"><?php echo PWD_TWICE; ?></label>
        </div>
        <div class="row">
            <input type="password" id="passagain" name="passagain" value=""/>
            <span class="description"><?php echo PWD_AGAIN; ?></span>
        </div>
        <div class="row">
            <input type="submit" name="edit_profile" value="<?php echo UPDT_PROFILE; ?>" />
        </div>
    </form>
</div>