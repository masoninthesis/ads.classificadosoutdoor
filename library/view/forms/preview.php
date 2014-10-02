<!--Start Post-->
<div class="post classi">           
    <div class="post_content">
        <?php
        $title = unserialize(base64_decode($_POST['cc_title']));
        $description = unserialize(base64_decode($_POST['cc_description']));
        ?>
        <h1 class="post_title"><?php if (isset($_POST['cc_title'])) echo stripslashes($title); ?></h1>
        <?php
        $currency_symbol = get_option('currency_symbol');
        $custom_meta = cc_get_custom_field();
        if ($custom_meta) {
            foreach ($custom_meta as $meta):
                if ($meta['htmlvar_name'] == 'cc_price') {
                    if ($_POST[$meta['htmlvar_name']] != '') {
                        echo '<span class="price_meta">' . $currency_symbol . $_POST[$meta['htmlvar_name']] . ' </span>';
                    }
                }
            endforeach;
        }
        ?>                                     
        <div class="clear"></div>
        <div class="meta_boxes">
            <div class="meta_slider">
                <div class="flexslider">
                    <ul class="slides">
                        <?php
                        $custom_meta = cc_get_custom_field();
                        if ($custom_meta) {
                            foreach ($custom_meta as $meta) {
                                if ($meta['type'] == 'image_uploader') {
                                    if ($_POST[$meta['htmlvar_name']] != '') {
                                        echo '<li> <img src="' . $_POST[$meta['htmlvar_name']] . '" /> </li>';
                                    }
                                }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="meta_table">
                <table class="ar_desc">
                    <?php
                    if ($custom_meta) {
                        foreach ($custom_meta as $meta) {
                            if ($meta['type'] != 'image_uploader' && $meta['type'] != 'cc_map' && $meta['type'] != 'cc_map_input' && $meta['htmlvar_name'] !== 'cc_price') {
                                if ($_POST[$meta['htmlvar_name']] != '') {
                                    $field = '<tr>';
                                    $field .= '<td class="label">' . $meta['field_title'] . ':</td>';
                                    $field .='<td>' . $_POST[$meta['htmlvar_name']] . '</td>';
                                    $field .='</tr>';
                                    echo $field;
                                }
                            }
                        }
                    }
                    ?>
                </table>
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>                                      
    </div>
    <div class="post-content">
        <?php if (isset($_POST['cc_description'])) echo stripslashes ($description); ?>
    </div>
</div>
<!--End Post-->  