<?php

class cc_custom_fields {

    /**
     * Function:  cc_add_custom_field()
     * Description: Add a custom field in theme
     */
    public function cc_add_custom_field() {
        if (isset($_POST['submit']) && isset($_POST['field_title']) && $_POST['field_title'] != '') {
            $custom_field = array();
            $custom_field['field_category'] = $_POST['category'];
            if ($custom_field['field_category']) {
                $custom_field['field_category'] = implode(',', $custom_field['field_category']);
            }
            $custom_field['field_type'] = esc_attr($_POST['feild_type']);
            $custom_field['option_value'] = esc_attr($_POST['option_value']);
            $custom_field['field_title'] = esc_attr($_POST['field_title']);
            $custom_field['field_var'] = esc_attr($_POST['htmlvar_name']);
            $custom_field['field_des'] = esc_attr($_POST['field_des']);
            $custom_field['defalut_value'] = esc_attr($_POST['default_value']);
            $custom_field['position_order'] = esc_attr($_POST['sort_order']);
            $custom_field['is_active'] = esc_attr($_POST['is_active']);
            $custom_field['is_require'] = esc_attr($_POST['is_require']);
            $custom_field['show_on_detail'] = esc_attr($_POST['show_on_detail']);
            $custom_field['show_free'] = esc_attr($_POST['show_free']);
            $custom_field['html_id'] = esc_attr($_POST['html_id']);
            global $wpdb, $cfield_tbl_name;
            $wpdb->insert($cfield_tbl_name, $custom_field);
        }
        if (file_exists(VIEWPATH . "custom_field/add_custom_field.php")):
            require_once(VIEWPATH . "custom_field/add_custom_field.php");
        endif;
    }

    /**
     * Function:  cc_edit_custom_field()
     * Description: Edit custom field
     */
    public function cc_edit_custom_field() {
        $id = $_REQUEST['fid'];
        if (isset($_POST['submit'])) {
            $custom_field = array();
            $custom_field['field_category'] = $_POST['category'];
            if ($custom_field['field_category']) {
                $custom_field['field_category'] = implode(',', $custom_field['field_category']);
            }
            $custom_field['field_type'] = esc_attr($_POST['feild_type']);
            $custom_field['option_value'] = esc_attr($_POST['option_value']);
            $custom_field['field_title'] = esc_attr($_POST['field_title']);
            $custom_field['field_var'] = esc_attr($_POST['htmlvar_name']);
            $custom_field['field_des'] = esc_attr($_POST['field_des']);
            $custom_field['defalut_value'] = esc_attr($_POST['field_default']);
            $custom_field['position_order'] = esc_attr($_POST['field_position']);
            $custom_field['is_active'] = esc_attr($_POST['is_active']);
            $custom_field['is_require'] = esc_attr($_POST['is_require']);
            $custom_field['show_on_detail'] = esc_attr($_POST['show_on_detail']);
            $custom_field['show_free'] = esc_attr($_POST['show_free']);
            $custom_field['html_id'] = esc_attr($_POST['html_id']);
            global $wpdb, $cfield_tbl_name;
            $wpdb->update($cfield_tbl_name, $custom_field, array('field_id' => $id));
        }

        global $wpdb, $cfield_tbl_name;
        $sql = "SELECT * FROM  $cfield_tbl_name WHERE field_id = $id";
        $cfields = $wpdb->get_row($sql);
        if (file_exists(VIEWPATH . "custom_field/edit_custom_field.php")):
            require_once(VIEWPATH . "custom_field/edit_custom_field.php");
        endif;
    }

    /**
     * Function: cc_list_custom_field()
     * Description: Shows and manages all custom fields.
     * @global type $wpdb
     * @global type $cfield_tbl_name
     */
    public function cc_list_custom_field() {
        if (!isset($_REQUEST['ref']) && isset($_REQUEST['action']) && $_REQUEST['action'] == 'del'):
            $id = $_REQUEST['fid'];
            global $wpdb, $cfield_tbl_name;
            $query = "DELETE FROM  $cfield_tbl_name WHERE field_id = $id";
            $wpdb->query($query);
        endif;

        global $wpdb, $cfield_tbl_name;
        $query = "SELECT * FROM $cfield_tbl_name";
        $cfields = $wpdb->get_results($query);
        if (file_exists(VIEWPATH . "custom_field/manage_custom_field.php")):
            require_once(VIEWPATH . "custom_field/manage_custom_field.php");
        endif;
    }

}

//End of class
