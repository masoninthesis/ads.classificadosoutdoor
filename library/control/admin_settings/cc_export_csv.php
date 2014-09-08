<?php

$file = dirname(__FILE__);
$file = substr($file, 0, stripos($file, "wp-content"));
require($file . "/wp-load.php");

$filiename = POST_TYPE . "_report_" . strtotime(date('Y-m-d')) . ".csv";
header('Content-Description: File Transfer');
header("Content-type: application/force-download");
header('Content-Disposition: inline; filename="' . $filiename . '"');

global $wpdb;
$post_type = POST_TYPE;
$listing_query = "SELECT * FROM $wpdb->posts where post_type = '$post_type' and post_status = 'publish'";

$listing_info = $wpdb->get_results($listing_query);
$old_pattern = array("/[^a-zA-Z0-9-:;<>\/=.& ]/", "/_+/", "/_$/");
//$new_pattern = array("_", "_", "");
//dashed change.
$new_pattern = array("");
$listing_cat_type = CUSTOM_CAT_TYPE;
$listing_tag_type = CUSTOM_TAG_TYPE;

$heading = "post_author,post_title,post_content,post_excerpt,category,tags,post_status,post_name,post_type";
$custom_meta = cc_get_custom_field();
$heading .= ',' . 'cc_f_checkbox1';
$heading .= ',' . 'cc_f_checkbox2';
$heading .= ',' . 'cc_add_type';
foreach ($custom_meta as $value):
    if ($value['htmlvar_name'] !== 'cc_address' && $value['htmlvar_name'] !== 'cc_latitude' && $value['htmlvar_name'] !== 'cc_longitude') {
        $heading .= ',' . $value['htmlvar_name'];
    }
endforeach;
echo $heading . " \r\n";
if ($listing_info) {
    foreach ($listing_info as $info) {
        $author = $info->post_author;
        $post_title = preg_replace($old_pattern, $new_pattern, $info->post_title);
        $post_date = $info->post_date;
        $post_content = preg_replace($old_pattern, $new_pattern, $info->post_content);
        $post_excerpt = $info->post_excerpt;

        $category_array = wp_get_post_terms($info->ID, $taxonomy = $listing_cat_type, array('fields' => 'names'));
        $category = '';
        if ($category_array) {
            $category = implode('&', $category_array);
        }
        $tag_array = wp_get_post_terms($info->ID, $taxonomy = $listing_tag_type, array('fields' => 'names'));
        $tags = '';
        if ($tag_array) {
            $tags = implode('&', $tag_array);
        }
        $custom_meta = cc_get_custom_field();
        $fields = array();
        foreach ($custom_meta as $meta) {
            if ($meta['htmlvar_name'] !== 'cc_address' && $meta['htmlvar_name'] !== 'cc_latitude' && $meta['htmlvar_name'] !== 'cc_longitude') {
                $fields[] = get_post_meta($info->ID, $meta['htmlvar_name'], true);
            }
        }
        $feature_h = get_post_meta($info->ID, 'cc_f_checkbox1', true);
        $feature_c = get_post_meta($info->ID, 'cc_f_checkbox2', true);
        $package_type = get_post_meta($info->ID, 'cc_add_type', true);

        $export_content = "$author,$post_title,$post_content,$post_excerpt,$category,$tags,$info->post_status,$info->post_name,$info->post_type,";
        $export_content .= "$feature_h,$feature_c,$package_type,";
        foreach ($fields as $field) {
            $export_content .= str_replace(',', '&#44;', $field) . ',';
        }
        echo $export_content . " \r\n";
    }
}
?>
