<?php

function cc_get_custom_field() {
    global $wpdb, $cfield_tbl_name;
    $query = "SELECT * FROM $cfield_tbl_name WHERE is_active = 1 ORDER BY position_order asc,field_id asc";
    $fields = $wpdb->get_results($query);
    $returnarray = array();
    foreach ($fields as $field) {
        if ($field->field_type) {
            $options = explode(',', $field->option_value);
        }
        $custom_fields = array(
            "field_id" => $field->field_id,
            "field_title" => $field->field_title,
            "field_category" => $field->field_category,
            "htmlvar_name" => $field->field_var,
            "default" => $field->defalut_value,
            "type" => $field->field_type,
            "description" => $field->field_des,
            "option_values" => $options,
            "order" => $field->position_order,
            "is_require" => $field->is_require,
            "is_active" => $field->is_active,
            "show_on_detail" => $field->show_on_detail,
            "show_free" => $field->show_free,
            "id" => $field->html_id,
        );
        if ($options) {
            $custom_fields["option_values"] = $options;
        }
        $returnarray[$field->field_var] = $custom_fields;
    }
    return $returnarray;
}

function cc_get_terms_dropdown($taxonomies, $args) {
    $myterms = get_terms($taxonomies, $args);
    $output = "<select name='cc_category'>";
    foreach ($myterms as $term) {
        $root_url = get_bloginfo('url');
        $term_taxonomy = $term->taxonomy;
        $term_slug = $term->slug;
        $term_name = $term->name;
        $link = $root_url . '/' . $term_taxonomy . '/' . $term_slug;
        $output .="<option value='" . $term_slug . "'>" . $term_name . "</option>";
    }
    $output .="</select>";
    return $output;
}

function cc_get_currency_symbol() {
    $currency_code = get_option('currency_code');
    global $wpdb, $currency_tbl_name;
    $sql = "SELECT c_symbol 
        FROM  `$currency_tbl_name` 
        WHERE  `c_code` =  '$currency_code'";
    $symbol = $wpdb->get_row($sql);
    return $symbol->c_symbol;
}

// Update the currency_symbol
$currency_symbol = cc_get_currency_symbol();
if ($currency_symbol):
    update_option('currency_symbol', $currency_symbol);
endif;

function cc_get_payment_optins($method) {
    global $wpdb;
    $paymentsql = "select * from $wpdb->options where option_name like 'pay_method_$method'";
    $paymentinfo = $wpdb->get_results($paymentsql);
    if ($paymentinfo) {
        foreach ($paymentinfo as $paymentinfoObj) {
            $option_value = unserialize($paymentinfoObj->option_value);
            $paymentOpts = $option_value['payOpts'];
            $optReturnarr = array();
            for ($i = 0; $i < count($paymentOpts); $i++) {
                $optReturnarr[$paymentOpts[$i]['fieldname']] = $paymentOpts[$i]['value'];
            }
            return $optReturnarr;
        }
    }
}

function cc_get_billingtime() {
    global $wpdb, $price_table_name;
    $sql = "SELECT rebill_time FROM $price_table_name WHERE package_type = 'pkg_recurring'";
    $billing_time = $wpdb->get_results($sql);
    return $billing_time;
}

function cc_get_author_info($authorid) {
    global $wpdb;
    $sql = "SELECT $wpdb->users.*
            FROM
            $wpdb->users
            INNER JOIN $wpdb->posts
            ON $wpdb->users.ID = $wpdb->posts.post_author where $wpdb->posts.post_author=$authorid";

    $returninfo = $wpdb->get_row($sql);
    return $returninfo;
}

if (!function_exists('cc_cat_menu_drop_down')) {

    function cc_cat_menu_drop_down($cols = 3, $subs = 0) {
        global $wpdb;

        // get any existing copy of our transient data
        //if (false === ($cc_cat_menu = get_transient('cp_cat_menu'))) {
        // put all options into vars so we don't have to waste resources by calling them each time from within the loops
        $cc_cat_parent_count = 0;
        $cc_cat_child_count = 1;
        $cc_cat_hide_empty = 0;
        $cc_cat_orderby = get_option('cc_cat_orderby');
        $cc_cat_nocatstext = get_option('cc_cat_strip_nocatstext');

        // get all cats for the taxonomy ad_cat
        $cats = get_terms(CUSTOM_CAT_TYPE, 'hide_empty=0&hierarchical=1&pad_counts=1&show_count=1&order=ASC');
        $subcats = array();

        if (count($cats) > 0) {
            //remove all sub cats from the array and create new array with sub cats
            foreach ($cats as $key => $value)
                if ($value->parent != 0) {
                    $subcats[$key] = $cats[$key];
                    unset($cats[$key]);
                }


            $i = 0;
            $cat_cols = $cols; // change this to add/remove columns
            $total_main_cats = count($cats); // total number of parent cats
            //$cats_per_col = round($total_main_cats / $cat_cols); // items per column
            $cats_per_col = ceil($total_main_cats / $cat_cols); // items per column
            // loop through all the sub
			$cc_cat_menu = '';
			$show_count = '';
            foreach ($cats as $cat) :

                if (($i == 0) || ($i == $cats_per_col) || ($i == ($cats_per_col * 2)) || ($i == ($cats_per_col * 3))) {
                    if ($i == 0)
                        $first = ' first';
                    $cc_cat_menu .= '<div class="catcol ' . $first . '">';
                }
               $cat_children = get_term_children($cat->term_id, CUSTOM_CAT_TYPE);
               
               if($cat_children){
                $add_sign = '<img src="'.get_template_directory_uri().'/images/add.png"/>';
               }else{
                   $add_sign = '';
               }
               
                // only show the total count if option is set
                if ($cc_cat_parent_count == 1)
                    $show_count = '(' . $cat->count . ')';
                

                $cc_cat_menu .= '<ul class="main">';
                $cc_cat_menu .= '<li class="maincat cat-item-' . $cat->term_id . '"><a id="'.$cat->term_id.'" onclick="show_child(this);" href="javascript:void(0);" onclick="return false;" title="' . esc_attr($cat->description) . '">' . $cat->name .$add_sign.'</a> ' . $show_count . '</li>';



                // don't show any sub cats
                if ($subs <> 999) :
                    $cat_children = get_term_children($cat->term_id, CUSTOM_CAT_TYPE);
                    $subs_count = 0;
                    if (count($subcats) > 0) {    
                        $cc_cat_menu .= '<ul style="display:none;" id="sub_child'.$cat->term_id.'" class="sub_child">';
                        foreach ($subcats as $subcat) {
                            // skip sub cats from other cats
                            if ($subcat->parent != $cat->term_id && !in_array($subcat->parent, $cat_children))
                                continue;
                            // hide empty sub cats if option is set
                            if ($cc_cat_hide_empty == 1 && $subcat->count == 0)
                                continue;
                            // limit quantity of sub cats as user set
                            if ($subs_count >= $subs && $subs != 0)
                                continue;

                            // only show the total count if option is set
                            if ($cc_cat_child_count == 1)
                                $show_child_count = '(' . $subcat->count . ')';
                            else
                                $show_child_count = '';
                            

                            $cc_cat_menu .= '<li class="cat-item cat-item-' . $subcat->term_id . '"><a href="' . get_term_link($subcat, CUSTOM_CAT_TYPE) . '" title="' . esc_attr($subcat->description) . '">' . $subcat->name . '</a> ' . $show_child_count . '</li>';
                            $subs_count++;
                        }
                        $cc_cat_menu .= '</ul>';
                    }
                    if ($cc_cat_nocatstext != '' && $subs_count == 0)
                        $cc_cat_menu .= '<li>' . __('No categories', THEME_SLUG) . '</li>';

                endif;

                $cc_cat_menu .= '</ul>';

                if (($i == ($cats_per_col - 1)) || ($i == (($cats_per_col * 2) - 1)) || ($i == (($cats_per_col * 3) - 1)) || ($i == ($total_main_cats - 1)))
                    $cc_cat_menu .= '</div><!-- /catcol -->';

                $i++;

            endforeach;

            return $cc_cat_menu;

            // set transient
        }// end if count cats 
        //} 
    }

}

// function to display number of posts.
function cc_getPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 " . Vista;
    }
    return $count . ' ' . Visitas;
}

// function to count views.
function cc_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

// Add it to a column in WP-Admin
add_filter('manage_posts_columns', 'cc_posts_column_views');
add_action('manage_posts_custom_column', 'cc_posts_custom_column_views', 5, 2);

function cc_posts_column_views($defaults) {
    $defaults['post_views'] = __('Views');
    return $defaults;
}

function cc_posts_custom_column_views($column_name, $id) {
    if ($column_name === 'post_views') {
        echo cc_getPostViews(get_the_ID());
    }
}
function cc_get_cate_dropdown($taxonomies, $args) {
    $myterms = get_terms($taxonomies, $args);
    $output = "<select id='cat'>";
    foreach ($myterms as $term) {
        $root_url = get_bloginfo('url');
        $term_taxonomy = $term->taxonomy;
        $term_slug = $term->slug;
        $term_name = $term->name;
        $link = $root_url . '/' . $term_taxonomy . '/' . $term_slug;
        $output .="<option value='" . $term_slug . "'>" . $term_name . "</option>";
    }
    $output .="</select>";
    return $output;
}

function cc_custom_search($s_for, $s_cat, $s_to, $limit = null) {
    global $wpdb;
    $cc_post_status = "publish";
    $cc_post_type = POST_TYPE;
    $cc_meta_address = "cc_address";
    $cc_meta_city = "cc_city";
    $cc_meta_zipcode = "cc_zipcode";

    //Search from title,content,category,address,zipcode,city
    if ($s_for !== '' && $s_cat !== '' && $s_to !== '') {
        $query = "SELECT *
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->term_relationships
            ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
            LEFT JOIN $wpdb->postmeta 
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id
            WHERE
            $wpdb->posts.post_status = '$cc_post_status'
            AND $wpdb->posts.post_type = '$cc_post_type'
            AND ($wpdb->posts.post_title LIKE '%$s_for%'
            OR $wpdb->posts.post_content LIKE '%$s_for%')
            AND (($wpdb->postmeta.meta_key = '$cc_meta_address' AND $wpdb->postmeta.meta_value LIKE '%$s_to%')
            OR ($wpdb->postmeta.meta_key = '$cc_meta_city' AND $wpdb->postmeta.meta_value = '$s_to')
            OR ($wpdb->postmeta.meta_key = '$cc_meta_zipcode' AND $wpdb->postmeta.meta_value = '$s_to'))
            AND $wpdb->term_relationships.term_taxonomy_id = {$s_cat}
            GROUP BY ID {$limit}";
        //Search from title,content
    } elseif ($s_for !== '' && $s_cat == '' && $s_to == '') {
        $query = "SELECT *
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->term_relationships
            ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
            INNER JOIN $wpdb->terms
            ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
            WHERE
            $wpdb->posts.post_status = '$cc_post_status' AND post_type = '$cc_post_type' 
            AND ($wpdb->terms.name = '$s_for' or post_title like '%$s_for%' or post_content like '%$s_for%') 
            GROUP BY ID {$limit}";
        //Search from title,content and category
    } elseif ($s_for !== '' && $s_cat !== '' && $s_to == '') {
        $query = "SELECT $wpdb->posts.*
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->term_relationships
            ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
            WHERE
            $wpdb->posts.post_type = '$cc_post_type'
            AND 
            $wpdb->posts.post_status = '$cc_post_status'
            AND
            ($wpdb->posts.post_title LIKE '%$s_for%' OR $wpdb->posts.post_content LIKE '%$s_for%')
            AND
            ($wpdb->term_relationships.term_taxonomy_id = {$s_cat})
            GROUP BY ID {$limit}";
        //Search from category,zip,address and city
    } elseif ($s_for == '' && $s_cat !== '' && $s_to !== '') {
        $query = "SELECT $wpdb->posts.*
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->postmeta
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id
            INNER JOIN $wpdb->term_relationships
            ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
            WHERE
            $wpdb->posts.post_type = '$cc_post_type'
            AND $wpdb->posts.post_status = '$cc_post_status'
            AND
            ($wpdb->term_relationships.term_taxonomy_id = {$s_cat})
            AND (($wpdb->postmeta.meta_key ='$cc_meta_address' AND $wpdb->postmeta.meta_value LIKE '%$s_to%')
            OR ($wpdb->postmeta.meta_key ='$cc_meta_city' AND $wpdb->postmeta.meta_value = '$s_to')
            OR ($wpdb->postmeta.meta_key ='$cc_meta_zipcode' AND $wpdb->postmeta.meta_value = '$s_to'))
            GROUP BY ID {$limit}";
        //Search from category
    } elseif ($s_for == '' && $s_cat !== '' && $s_to == '') {
        $query = "SELECT $wpdb->posts.*
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->term_relationships
            ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
            WHERE
            $wpdb->posts.post_type = '$cc_post_type'
            AND $wpdb->posts.post_status = '$cc_post_status'
            AND
            ($wpdb->term_relationships.term_taxonomy_id = {$s_cat})
            GROUP BY ID {$limit}";
        //Search from address,city and zipcode
    } elseif ($s_for == '' && $s_cat == '' && $s_to !== '') {
        $query = "SELECT $wpdb->posts.*
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->postmeta
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id
            WHERE
            $wpdb->posts.post_type = '$cc_post_type'
            AND $wpdb->posts.post_status = '$cc_post_status'                                  
            AND (($wpdb->postmeta.meta_key ='$cc_meta_address' AND $wpdb->postmeta.meta_value LIKE '%$s_to%')
            OR ($wpdb->postmeta.meta_key ='$cc_meta_city' AND $wpdb->postmeta.meta_value = '$s_to')
            OR ($wpdb->postmeta.meta_key ='$cc_meta_zipcode' AND $wpdb->postmeta.meta_value = '$s_to'))
            GROUP BY ID {$limit}";
        //Search from tags,title,content and zipcode,city,address
    } elseif ($s_for !== '' && $s_cat == '' && $s_to !== '') {
        $query = "SELECT *
            FROM
            $wpdb->posts
            INNER JOIN $wpdb->postmeta
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id
            INNER JOIN $wpdb->term_relationships
            ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
            INNER JOIN $wpdb->terms
            ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
            WHERE
            $wpdb->posts.post_type = '$cc_post_type'
            AND $wpdb->posts.post_status = '$cc_post_status'
            AND ($wpdb->terms.name = '$s_for'
            OR 
            $wpdb->posts.post_title LIKE '%$s_for%'  
            OR $wpdb->posts.post_content LIKE '%$s_for%')
            AND ($wpdb->postmeta.meta_key = '$cc_meta_address'
            AND $wpdb->postmeta.meta_value LIKE '%$s_to%'
            OR $wpdb->postmeta.meta_key = '$cc_meta_city'
            AND $wpdb->postmeta.meta_value = '$s_to'
            OR $wpdb->postmeta.meta_key = '$cc_meta_zipcode'
            AND $wpdb->postmeta.meta_value = '$s_to')
            GROUP BY
            $wpdb->posts.ID {$limit}";
    }
    $classifields = array();
    $classifields['query'] = $wpdb->query($query);
    $classifields['result'] = $wpdb->get_results($query);
    return $classifields;
}

function cc_upgrade_listing($pid, $total_amount) {
    $post_id = $pid;
    //Updating listing type
    if ($total_amount > 0) {
        update_post_meta($post_id, 'cc_add_type', 'pro');
    } else {
        update_post_meta($post_id, 'cc_add_type', 'free');
    }
    $featured_home = 'on';
    $featured_cate = 'on';
    $listing_meta = array(
        'cc_f_checkbox1' => esc_attr($featured_home),
        'cc_f_checkbox1' => esc_attr($featured_cate)
    );
    if ($listing_meta) {
        foreach ($listing_meta as $key => $meta):
            update_post_meta($post_id, $key, $meta);
        endforeach;
    }
}

/**
 * Function Name: cc_get_post_date_gmt()
 * Description: returns post date gmt by post id
 * @global type $wpdb
 * @param type $post_id
 * @return type date
 */
function cc_get_post_date_gmt($post_id) {
    global $wpdb, $expiry_tbl_name;
    $sql = "SELECT " . $expiry_tbl_name . ".listing_date FROM " . $expiry_tbl_name . " WHERE  " . $expiry_tbl_name . ".pid = $post_id";
    $query = $wpdb->get_row($sql);
    $last_post_date = $query->listing_date;
    return $last_post_date;
}

/**
 * Function: cc_get_recurring_period()
 * Description: Returns total days of recurring period
 * @global type $wpdb
 * @global type $price_table_name
 * @return type 
 */
function cc_get_recurring_period() {
    global $wpdb, $price_table_name;
    $pricesql = "SELECT * FROM $price_table_name WHERE status=1";
    $priceinfo = $wpdb->get_results($pricesql);
    foreach ($priceinfo as $priceinfoObj) {
        if ($priceinfoObj->package_type == 'pkg_recurring') {
            $first_billing_per = $priceinfoObj->first_billing_per;
            $first_billing_cycle = $priceinfoObj->first_billing_cycle;
            $second_billing_per = $priceinfoObj->second_billing_per;
            $second_billing_cycle = $priceinfoObj->second_billing_cycle;

            if (($priceinfoObj->first_billing_per != "" || $priceinfoObj->first_billing_per != 0)) {
                $recurring_period = get_billing_period($first_billing_per, $first_billing_cycle, $second_billing_per, $second_billing_cycle);
            }
        }
    }
    return $recurring_period;
}

/**
 * Function: cc_get_day_difference($start_date, $end_date)
 * Description: Returns total days of start date to end date
 * @param type $start_date
 * @param type $end_date
 * @return type 
 */
function cc_get_day_difference($start_date, $end_date) {
    list($date, $time) = explode(' ', $start_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $startdate = explode("-", $date);
    $starttime = explode(":", $time);

    list($date, $time) = explode(' ', $end_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $enddate = explode("-", $date);
    $endtime = explode(":", $time);

    $secons_dif = mktime($endtime[0], $endtime[1], $endtime[2], $enddate[1], $enddate[2], $enddate[0]) -
            mktime($starttime[0], $starttime[1], $starttime[2], $startdate[1], $startdate[2], $startdate[0]);

    //Different can be returned in many formats
    //In Minutes: floor($secons_dif/60);
    //In Hours: floor($secons_dif/60/60);
    //In days: floor($secons_dif/60/60/24);
    //In weeks: floor($secons_dif/60/60/24/7;
    //In Months: floor($secons_dif/60/60/24/7/4);
    //In years: floor($secons_dif/365/60/24);
    //We will return it in hours
    $difference = floor($secons_dif / 60 / 60 / 24);

    return $difference;
}

/**
 * Function: cc_get_minute_difference($start_date, $end_date)
 * Description: Returns total days of start date to end date
 * @param type $start_date
 * @param type $end_date
 * @return type 
 */
function cc_get_minute_difference($start_date, $end_date) {
    list($date, $time) = explode(' ', $start_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $startdate = explode("-", $date);
    $starttime = explode(":", $time);

    list($date, $time) = explode(' ', $end_date);
    if ($time == NULL) {
        $time = '00:00:00';
    }
    $enddate = explode("-", $date);
    $endtime = explode(":", $time);

    $secons_dif = mktime($endtime[0], $endtime[1], $endtime[2], $enddate[1], $enddate[2], $enddate[0]) -
            mktime($starttime[0], $starttime[1], $starttime[2], $startdate[1], $startdate[2], $startdate[0]);

    //Different can be returned in many formats
    //In Minutes: floor($secons_dif/60);
    //In Hours: floor($secons_dif/60/60);
    //In days: floor($secons_dif/60/60/24);
    //In weeks: floor($secons_dif/60/60/24/7;
    //In Months: floor($secons_dif/60/60/24/7/4);
    //In years: floor($secons_dif/365/60/24);
    //We will return it in hours
    $difference_minute = floor($secons_dif / 60);

    return $difference_minute;
}

// Define what post types to search
function cc_searchAll($query) {
    $post_type = POST_TYPE;
    if ($query->is_search) {
        $query->set('post_type', array('post', 'page', 'feed', $post_type));
    }
    return $query;
}

// The hook needed to search ALL content
add_filter('the_search_query', 'cc_searchAll');

/**
 * Function Name: cc_delete_dummy_data()
 * Description: To delete the dummy data
 * 
 * @global type $wpdb 
 */
function cc_delete_dummy_data() {
    global $wpdb;
    $productArray = array();
    $pids_sql = "SELECT $wpdb->postmeta.post_id , $wpdb->postmeta.meta_id , $wpdb->postmeta.meta_key FROM  $wpdb->postmeta
                WHERE
                $wpdb->postmeta.meta_key = 'cc_dummy_content'
                AND $wpdb->postmeta.meta_value = 1";
    $pids_info = $wpdb->get_results($pids_sql);
    foreach ($pids_info as $pids_info_obj) {
        wp_delete_post($pids_info_obj->post_id);
    }
}

/**
 * Function Name: cc_dummydata_notify()
 * Description: To insert and delete dummy data
 * 
 * @global type $wpdb 
 */
function cc_dummydata_notify() {
    global $wpdb;
    if (strstr($_SERVER['REQUEST_URI'], 'themes.php') && $_REQUEST['template'] == '' && $_GET['page'] == '') {

        if ($_REQUEST['dummy'] == 'delete') {
            cc_delete_dummy_data();
            $dummy_deleted = '<p><b>' . DATA_REMOVE_NOTIFY . '</b></p>';
        }
        if ($_REQUEST['dummy_insert']) {
            include_once (CONTROLPATH . 'install_data.php'); //Install dummy data
        }
        if ($_REQUEST['activated'] == 'true') {
            $theme_activate_success = '<p class="message">' . ACTIVATE_NOTIFY . '</p>';
        }
        $post_counts = $wpdb->get_var("SELECT count($wpdb->postmeta.post_id) FROM $wpdb->postmeta
                                        WHERE
                                        $wpdb->postmeta.meta_key = 'cc_dummy_content'
                                        AND $wpdb->postmeta.meta_value = 1");
        if ($post_counts > 0) {
            $dummy_data_notify = '<p> <b>' . SAMPLE_DATA_NOTIFY . '</b><p>';
            $button = '<a class="btn_delete" href="' . admin_url('/themes.php?dummy=delete') . '">' . DELETE_PLS . '</a>';
        } else {
            $dummy_data_notify = '<p> <b>' . POPULATE_DT . '</b></p>';
            $button = '<a class="btn_insert" href="' . admin_url('/themes.php?dummy_insert=1') . '">' . INSRT_DT . '</a>';
        }
        $dummy_msg = "<div class='btn'>$button</div>";
        ?>
        <style type="text/css">
            .dummy_data_notify{
                background: #f1f1f1;
                border:1px solid #089bd0;
                margin-top: 20px;
                width:700px;
                padding-left: 20px;
                color: #282829;
                height:100px;
                position:relative;
                margin-bottom:25px;
            }
            .dummy_data_notify .btn{
                background: url('<?php echo TEMPLATEURL . '/images/dummy_msg.png'; ?>') no-repeat; 
                width:276px;
                height:58px;
                position:absolute;
                bottom:-13px;
                text-align:center;
                right:15px;
            }
            .dummy_data_notify .btn a{
                color: #000;
                display:block;
                text-decoration:none;
                margin-top:22px;
                font-size:22px;
                text-shadow:0 1px 0 #3fc6f3;
            }
        </style>
        <?php
        echo '<div class="dummy_data_notify"> ' . $theme_activate_success . $dummy_deleted . $dummy_data_notify . $dummy_msg . '</div>';
    }
}

add_action('admin_notices', 'cc_dummydata_notify');

// checks if a user is logged in, if not redirect them to the login page
function auth_redirect_login() {
    $user = wp_get_current_user();
    if ($user->ID == 0) {
        nocache_headers();
        wp_redirect(get_option('siteurl') . '/wp-login.php?redirect_to=' . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}

function gc_renewal_periond() {
    global $wpdb, $price_table_name;
    $sql = "SELECT * FROM $price_table_name";
    $QUERY = $wpdb->get_results($sql);
    foreach ($QUERY as $q) {
        if ($q->package_type == "pkg_free") {
            if ($q->renewal_cycle == "M")
                return $q->renewal_per * 30;
            if ($q->renewal_cycle == "Y")
                return $q->renewal_per * 365;
            if ($q->renewal_cycle == "D")
                return $q->renewal_per;
        }
    }
}

function cc_set_default_expiry($post_id) {
    global $wpdb, $price_table_name;
    $sql = "SELECT * FROM $price_table_name WHERE package_type = 'pkg_free'";
    $QUERY = $wpdb->get_results($sql);
    foreach ($QUERY as $q) {
        $ad_length = $q->validity;
        if ($q->validity_per == 'D') {
            
        } elseif ($q->validity_per == 'M') {
            $ad_length = $ad_length * 30;
        } elseif ($q->validity_per == 'Y') {
            $ad_length = $ad_length * 365;
        }
    }
    if ($ad_length > 0) {
        $admin_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' days'));
        add_post_meta($post_id, 'cc_listing_duration', $admin_ad_duration, true);
    }
}

function cc_set_expiry($post_id, $pkg_type = '') {
    global $wpdb, $price_table_name;
    $sql = "SELECT * FROM $price_table_name";
    $QUERY = $wpdb->get_results($sql);
    foreach ($QUERY as $q) {
        $ad_length = $q->validity;
        if ($q->validity_per == 'D') {
            
        } elseif ($q->validity_per == 'M') {
            $ad_length = $ad_length * 30;
        } elseif ($q->validity_per == 'Y') {
            $ad_length = $ad_length * 365;
        }
        if ($q->package_type == 'pkg_free' && $pkg_type == 'pkg_free') {
            $free_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' días'));
            add_post_meta($post_id, 'cc_listing_duration', $free_ad_duration, true);
        } elseif ($q->package_type == 'pkg_one_time' && $pkg_type == 'pkg_one_time') {
            $onetime_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' días'));
            add_post_meta($post_id, 'cc_listing_duration', $onetime_ad_duration, true);
        } elseif ($q->package_type == 'pkg_recurring' && $pkg_type == 'pkg_recurring') {
            //Calculate first billing period
            $first_billing_cycle = $q->first_billing_per;
            if ($q->first_billing_cycle == 'M') {
                $first_billing_cycle = $q->first_billing_per * 30;
            } elseif ($q->first_billing_cycle == 'Y') {
                $first_billing_cycle = $q->first_billing_per * 365;
            } else {
                $first_billing_cycle = $q->first_billing_per;
            }
            //Calculate second billing period
            $second_billing_cycle = $q->second_billing_per;
            if ($q->second_billing_cycle == 'M') {
                $second_billing_cycle = $q->second_billing_per * 30;
            } elseif ($q->second_billing_cycle == 'Y') {
                $second_billing_cycle = $q->second_billing_per * 365;
            } else {
                $second_billing_cycle = $q->second_billing_per;
            }
            $ad_length = $second_billing_cycle + $first_billing_cycle;
            $recurring_ad_duration = date_i18n('m/d/Y H:i:s', strtotime('+' . $ad_length . ' días'));
            add_post_meta($post_id, 'cc_listing_duration', $recurring_ad_duration, true);
        }
    }
}

// change ad to draft if it's expired
function cc_has_ad_expired($post_id) {
    global $wpdb;

    $expire_date = get_post_meta($post_id, 'cc_listing_duration', true);

    // debugging variables
    // echo date_i18n('m/d/Y H:i:s') . ' <-- current date/time GMT<br/>';
    // echo $expire_date . ' <-- expires date/time<br/>';
    // if current date is past the expires date, change post status to draft
    if ($expire_date) {
        if (strtotime(date('Y-m-d H:i:s')) > (strtotime($expire_date))) :
            $my_post = array();
            $my_post['ID'] = $post_id;
            $my_post['post_status'] = 'draft';
            wp_update_post($my_post);
            //After expired, listing will be set premium to free listing
            $listing_type = get_post_meta($post_id, 'cc_add_type', true);
            if ($listing_type == "pro") {
                update_post_meta($post_id, 'cc_add_type', 'libre');
            }
            return true;
        endif;
    }
}

// shows how much time is left before the ad expires
function cc_timeleft($theTime) {
    $now = strtotime("now");
    $timeLeft = $theTime - $now;

    $days_label = __('days', THEME_SLUG);
    $day_label = __('day', THEME_SLUG);
    $hours_label = __('horas', THEME_SLUG);
    $hour_label = __('horas', THEME_SLUG);
    $mins_label = __('mins', THEME_SLUG);
    $min_label = __('min', THEME_SLUG);
    $secs_label = __('segs', THEME_SLUG);
    $r_label = __('restante', THEME_SLUG);
    $expired_label = __('Este anuncio ha caducado', THEME_SLUG);

    if ($timeLeft > 0) {
        $days = floor($timeLeft / 60 / 60 / 24);
        $hours = $timeLeft / 60 / 60 % 24;
        $mins = $timeLeft / 60 % 60;
        $secs = $timeLeft % 60;

        if ($days == 01) {
            $d_label = $day_label;
        } else {
            $d_label = $days_label;
        }
        if ($hours == 01) {
            $h_label = $hour_label;
        } else {
            $h_label = $hours_label;
        }
        if ($mins == 01) {
            $m_label = $min_label;
        } else {
            $m_label = $mins_label;
        }

        if ($days) {
            $theText = $days . " " . $d_label;
            if ($hours) {
                $theText .= ", " . $hours . " " . $h_label . " left";
            }
        } elseif ($hours) {
            $theText = $hours . " " . $h_label;
            if ($mins) {
                $theText .= ", " . $mins . " " . $m_label . " left";
            }
        } elseif ($mins) {
            $theText = $mins . " " . $m_label;
            if ($secs) {
                $theText .= ", " . $secs . " " . $secs_label . " left";
            }
        } elseif ($secs) {
            $theText = $secs . " " . $secs_label . " left";
        }
    } else {
        $theText = $expired_label;
    }
    return $theText;
}

function gc_renew_listing($listing_id) {
    $renewal_period = gc_renewal_periond();

    if ($renewal_period > 0) {
        // set the ad ad expiration date
        $ad_expire_date = date_i18n('m/d/Y H:i:s', strtotime('+' . $renewal_period . ' days'));
        //now update the expiration date on the ad
        update_post_meta($listing_id, 'cc_listing_duration', $ad_expire_date);
        wp_update_post(array('ID' => $listing_id, 'post_date' => date('Y-m-d H:i:s'), 'edit_date' => true, 'post_status' => 'publish'));
        return true;
    }
    //attempt to relist a paid ad
    else {
        return false;
    }
}

function cc_set_listing($post_id) {
    if ($post_id != ''):
        global $wpdb;
        $post_status = cc_get_option('cc_paidad');
        if ($post_status == 'pending'):
            $post_status = 'pending';
        elseif ($post_status == 'publish'):
            $post_status = 'publish';
        elseif ($post_status == ''):
            $post_status = 'publish';
        endif;
        $my_post = array(
            'ID' => $post_id,
            'post_status' => $post_status
        );
        wp_update_post($my_post);
    endif;
}
//Set transaction feids after receving payments 
function cc_set_transaction($request) {
    // Globliazation tables variables 
    global $wpdb,$current_user, $transection_table_name;
    $txn_type = '';
    if ($request['txn_type'] == 'subscr_payment') {
        $txn_type = 'Recurring';
    } else {
        $txn_type = 'One Time';
    }
    // Select statement 
    $sql = "SELECT * FROM $transection_table_name WHERE paypal_transection_id='{$request['txn_id']}'";
    $sql_stat = $wpdb->get_row($sql);
    //checking transaction empty or not
    if (empty($sql_stat)):
        $current_user = wp_get_current_user();
        $wpdb->insert($transection_table_name, array(
            "user_id" => $current_user->ID,
            "post_id" => $request['post_id'],
            "post_title" => $request['transaction_subject'],
            "status" => 1,
            "payment_method" => 'Paypal',
            "payable_amt" => $request['mc_gross'],
            "payment_date" => current_time('mysql'),
            "txn_type" => $txn_type,
            "paypal_transection_id" => $request['txn_id'],
            "user_name" => $current_user->user_login,
            "pay_email" => $request['payer_email'],
            "billing_name" => $current_user->user_login,
            "billing_add" => $request['residence_country']
        ));
    endif;
}

//Expire listing when payment canceled or denied
function cc_listing_expire($post_id) {
    $my_post = array();
    $my_post['ID'] = $post_id;
    $my_post['post_status'] = 'draft';
    wp_update_post($my_post);
}
function inkthemes_captcha1() {
    return rand(0, 9);
}

function inkthemes_captcha2() {
    return rand(0, 9);
}