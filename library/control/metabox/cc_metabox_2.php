<?php
/**
 * Metabox arrays for retrieving
 * Custom values
 */
$ad_expiry = array(
    'expired_listing' => array('name' => THEME_SLUG . '_ad_expiry',
        'label' => '(Optional) Format: mm/dd/yy hh:mm:ss',
        'type' => 'text',
        'description' => 'You can set any custom expiry date here. Leave blank if you want the expiry date to be calculated automatically depending upon the ad active period setting inside payment settings under theme options panel.'
        ));
$key = 'cc_meta';
$listing_type_meta = array(
    'free_listing' => array(
        'name' => 'cc_add_type',
        'description' => __('', THEME_SLUG),
        'label' => FREE_AD,
        'value' => 'free',
        'type' => 'radio'
    ),
    'pro_listing' => array(
        'name' => 'cc_add_type',
        'description' => __('', THEME_SLUG),
        'label' => PREM_AD,
        'value' => 'pro',
        'type' => 'radio'
    ),
);
$checkbox_meta = array(
    'F_checkbox1' => array(
        'name' => 'cc_f_checkbox1',
        'description' => __('', THEME_SLUG),
        'label' => FEATURE_HOME_SLIDER,
        'type' => 'checkbox'
    ),
    'F_checkbox2' => array(
        'name' => 'cc_f_checkbox2',
        'description' => __('', THEME_SLUG),
        'label' => FEATURE_CAT_SLIDER,
        'type' => 'checkbox'
    ),
);

function cc_create_meta_box2() {
    if (function_exists('add_meta_box')) {
        add_meta_box('cc_ad_type_metabox', AD_TYPE, 'cc_ad_type_meta', POST_TYPE, 'normal', 'high');
        add_meta_box('checkbox-meta-boxes', FEATURE_THIS_AD, 'cc_checkbox_meta', POST_TYPE);
        add_meta_box('expiry-meta-boxes', __('Set Expiry Date', THEME_SLUG), 'cc_ad_expiry', POST_TYPE, 'normal', 'high');
    }
}

/**
 * Function : geocraft_listing_type_meta()
 * @global type $post
 * @global array $listing_type_meta
 * @global string $key 
 */
function cc_ad_type_meta() {
    global $post, $listing_type_meta, $key;
    ?>
    <div class="form-wrap">
        <?php
        wp_nonce_field(plugin_basename(__FILE__), $key . '_wpnonce', false, true);
        foreach ($listing_type_meta as $listing) {
            $data = get_post_meta($post->ID, $listing['name'], true);
            ?>
            <div class="form-required" style="margin:0; padding: 0 8px">
                <?php
                switch ($listing['type']) :
                    case "radio" :
                        ?>
                        <input style="float:left; width:20px;" id="<?php echo $listing['value']; ?>" class="radio of-input" value="<?php echo $listing['value']; ?>" type="radio" name="<?php echo $listing['name']; ?>" <?php
                echo checked($data, true, true);
                if ($data == $listing['value']) {
                    echo 'checked="checked"';
                }
                if ($data == '' && $listing['value'] == 'free') {
                    echo 'checked="checked"';
                }
                        ?> />
                        <label for="<?php echo $listing['value']; ?>"><?php echo $listing['label']; ?></label>
                        <?php
                        if ($listing['description'])
                            echo wpautop(wptexturize($listing['description']));
                        break;
                    default :
                        ?>                      
                        <?php
                        break;
                endswitch;
                ?>				
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div>
    <?php
}

/**
 * Function for creating 
 * Checkbox meta box
 * @global type $post
 * @global array $checkbox_meta
 * @global string $key 
 */
function cc_checkbox_meta() {
    global $post, $checkbox_meta, $key;
    ?>
    <div class="form-wrap">
        <?php
        wp_nonce_field(plugin_basename(__FILE__), $key . '_wpnonce', false, true);
        foreach ($checkbox_meta as $check_box) {
            $data = get_post_meta($post->ID, $check_box['name'], true);
            ?>
            <div class="form-required" style="margin:0; padding: 0 8px">                   
                <?php
                if (!isset($check_box['type']))
                    $check_box['type'] = 'input';
                switch ($check_box['type']) :
                    case "checkbox" :
                        ?>
                        <input style="float:left; width:20px;" id="<?php echo $check_box['name']; ?>" class="checkbox of-input" type="checkbox" name="<?php echo $check_box['name']; ?>" <?php
                        echo checked($data, 1, false);
                        if ($data) {
                            echo 'checked="checked"';
                        }
                        ?> />
                        <label for="<?php echo $check_box['name']; ?>" class="check-label"><?php echo $check_box['label']; ?></label>
                        <?php
                        if ($check_box['description'])
                            echo wpautop(wptexturize($check_box['description']));
                        break;
                    default :
                        ?>
                        <input type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $check_box['name']; ?>" value="<?php echo htmlspecialchars($data); ?>" /><?php
                        if ($check_box['description'])
                            echo wpautop(wptexturize($check_box['description']));
                        ?>
                        <?php
                        break;
                endswitch;
                ?>				
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div>
    <?php
}
function cc_ad_expiry(){
    global $ad_expiry,$post;
    ?>
     <div class="form-wrap">
        <?php
        wp_nonce_field('cc_meta_box', 'ad_nonce', true, true);
        foreach ($ad_expiry as $expiry) {
            $data = get_post_meta($post->ID, 'cc_listing_duration', true);
            ?>
            <div class="form-required" style="margin:0; padding: 0 8px">
                <label for="<?php echo $expiry['name']; ?>" style="color: #666;overflow:hidden;"><?php echo $expiry['label']; ?></label>
                <?php                
                switch ($expiry['type']) :
                    case "text" :              
                        ?>
                        <input id="<?php echo $expiry['name']; ?>" type="text" style="width:320px; margin-right: 10px; float:left" name="<?php echo $expiry['name']; ?>" value="<?php echo htmlspecialchars($data); ?>" />
                            <?php
                if ($expiry['description'])
                    echo wpautop(wptexturize($expiry['description']));
                        ?>
                        <?php
                        break;
                endswitch;
                ?>				
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div> 
<?php
}
/**
 * @global type $post
 * @global array $meta_boxes
 * @global string $key
 * @param type $post_id
 * @return type 
 */
function cc_save_meta_box($post_id) {
    global $post, $meta_boxes, $key, $image_meta, $checkbox_meta, $listing_type_meta, $custom_meta,$ad_expiry;
    if (!isset($_POST['ad_nonce']))
            return $post_id;
    if (!isset($_POST[$key . '_wpnonce']))
        return $post_id;
    if (!wp_verify_nonce($_POST[$key . '_wpnonce'], plugin_basename(__FILE__)))
        return $post_id;
    if (!current_user_can('edit_post', $post_id))
        return $post_id;
    foreach ($checkbox_meta as $check_box) {
        //if ($_POST[$check_box['name']] != '') {
        update_post_meta($post_id, $check_box['name'], $_POST[$check_box['name']]);
        //}
    }
    foreach ($listing_type_meta as $listing_type) {
        //if ($_POST[$listing_type['name']] != '') {
        update_post_meta($post_id, $listing_type['name'], $_POST[$listing_type['name']]);
        //}
    }
    if($ad_expiry){
        foreach ($ad_expiry as $expiry) {
            update_post_meta($post_id, 'cc_listing_duration', $_POST[$expiry['name']]);
        }
    }
}

add_action('add_meta_boxes', 'cc_create_meta_box2');
add_action('save_post', 'cc_save_meta_box');
