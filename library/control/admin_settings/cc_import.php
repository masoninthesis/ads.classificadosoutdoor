<?php
global $wpdb, $current_user;
$dirinfo = wp_upload_dir();
$path = $dirinfo['path'];
$url = $dirinfo['url'];
$subdir = $dirinfo['subdir'];
$basedir = $dirinfo['basedir'];
$baseurl = $dirinfo['baseurl'];
$tmppath = "/csv/";
if (isset($_POST['submit_csv'])) {
    if ($_FILES['upload_csv']['name'] != '' && $_FILES['upload_csv']['error'] == '0') {
        $filename = $_FILES['upload_csv']['name'];
        $filenamearr = explode('.', $filename);
        $extensionarr = array('csv', 'CSV');

        if (in_array($filenamearr[count($filenamearr) - 1], $extensionarr)) {
            $destination_path = $basedir . $tmppath;
            if (!file_exists($destination_path)) {
                mkdir($destination_path, 0777);
            }
            $target_path = $destination_path . $filename;
            $csv_target_path = $target_path;
            if (move_uploaded_file($_FILES['upload_csv']['tmp_name'], $target_path)) {
                $fd = fopen($target_path, "rt");
                $rowcount = 0;
                $customKeyarray = array();
                while (!feof($fd)) {
                    $buffer = fgetcsv($fd, 4096);
                    if ($rowcount == 0) {
                        for ($k = 0; $k < count($buffer); $k++) {
                            $customKeyarray[$k] = $buffer[$k];
                        }
                        if ($customKeyarray[0] == '') {
                            $url = admin_url('/admin.php?page=import');
                            echo '<form action="' . $url . '#of-option-import" method="get" id="frm_bulk_upload" name="csv_upload">
                                                        <input type="hidden" value="import" name="page">
                                                        <input type="hidden" value="error" name="msg">
							</form>
							<script>document.csv_upload.submit();</script>';
                            exit;
                        }
                    } else {

                        $userid = trim($buffer[0]);
                        $post_title = trim($buffer[1]);
                        $post_content = addslashes($buffer[2]);
                        $post_excerpt = addslashes($buffer[3]);
                        $post_cat = array();
                        $catids_arr = array();
                        $post_cat = trim($buffer[4]);
                        $post_tags = trim($buffer[5]); // comma seperated tags                                    
                        $post_status = addslashes($buffer[6]);
                        $post_name = addslashes($buffer[7]);
                        $post_type = addslashes($buffer[8]);
                        //category initialize
                        if ($post_cat) {
                            $post_cat_arr = explode('&', $post_cat);
                            for ($c = 0; $c < count($post_cat_arr); $c++) {
                                $catid = trim($post_cat_arr[$c]);
                                if (get_cat_ID($catid)) {
                                    $catids_arr[] = get_cat_ID($catid);
                                }
                            }
                        }
                        if (!$catids_arr) {
                            $catids_arr[] = 1;
                        }
                        if ($post_tags) {
                            $tag_arr = explode('&', $post_tags);
                        }
                        if ($post_title != '') {
                            $my_post['post_title'] = $post_title;
                            $my_post['post_content'] = $post_content;
                            if ($userid) {
                                $my_post['post_author'] = $userid;
                            } else {
                                $my_post['post_author'] = $current_user->ID;
                            }

                            $my_post['post_status'] = $post_status;
                            $my_post['post_name'] = $post_name;
                            //wordpress 3.5 doesn't support post date
                            //$my_post['post_date'] = $post_date;                                
                            $my_post['post_excerpt'] = $post_excerpt;
                            $my_post['post_type'] = $post_type;
                            $my_post['post_category'] = $catids_arr;
                            $my_post['tags_input'] = $tag_arr;
                            $last_postid = wp_insert_post($my_post);
                            if ($post_type != 'post') {
                                if ($post_type == trim(POST_TYPE)) {
                                    wp_set_object_terms($last_postid, $post_cat_arr, CUSTOM_CAT_TYPE); //custom category
                                    wp_set_object_terms($last_postid, $tag_arr, CUSTOM_TAG_TYPE); //custom tags
                                }
                            }
                            $custom_meta = cc_get_custom_field();
                            update_post_meta($last_postid, 'cc_f_checkbox1', addslashes($buffer[9]));
                            update_post_meta($last_postid, 'cc_f_checkbox2', addslashes($buffer[10]));
                            update_post_meta($last_postid, 'cc_add_type', addslashes($buffer[11]));
                            $count = 12;
                            $address = array();
                            foreach ($custom_meta as $meta) {
                                update_post_meta($last_postid, $meta['htmlvar_name'], $buffer[$count]);
                                if ($meta['htmlvar_name'] == 'cc_street' && $buffer[$count] != '') {
                                    $address[] = $buffer[$count];
                                }
                                if ($meta['htmlvar_name'] == 'cc_city' && $buffer[$count] != '') {
                                    $address[] = $buffer[$count];
                                }
                                if ($meta['htmlvar_name'] == 'cc_zipcode' && $buffer[$count] != '') {
                                    $address[] = $buffer[$count];
                                }
                                if ($meta['htmlvar_name'] == 'cc_state' && $buffer[$count] != '') {
                                    $address[] = $buffer[$count];
                                }
                                if ($meta['htmlvar_name'] == 'cc_country' && $buffer[$count] != '') {
                                    $address[]= $buffer[$count];
                                }
                                $count++;
                            }
                            //Update address required field for showing map
                            $address = implode(',', $address);
                            $url = "http://maps.googleapis.com/maps/api/geocode/xml?address=" . $address . "&sensor=false";
                            //$getAddress = simplexml_load_file($url);
                            $address_latitude = $getAddress->result->geometry->location->lat;
                            $address_longitude = $getAddress->result->geometry->location->lng;
                            update_post_meta($last_postid, 'cc_latitude', $address_latitude);
                            update_post_meta($last_postid, 'cc_longitude',$address_longitude);
                            update_post_meta($last_postid, 'cc_address', $address);
                        }//End post title condition
                    }
                    $rowcount++;
                }
                @unlink($csv_target_path);
                $url = admin_url('/admin.php?page=import');
                echo '<form action="' . $url . '?page=import#of-option-import" method="get" id="csv_upload" name="csv_upload">				
                                <input type="hidden" value="import" name="page">
                                <input type="hidden" value="success" name="upload_msg">                            
				</form>
				<script>document.csv_upload.submit();</script>
				';
                exit;
            } else {
                $url = admin_url('/admin.php?page=import');
                echo '<form action="' . $url . '#of-option-import" method="get" id="csv_upload" name="csv_upload">			
                                <input type="hidden" value="import" name="page">
                                <input type="hidden" value="tmpfile" name="emsg">
				</form>
				<script>document.csv_upload.submit();</script>
				';
                exit;
            }
        } else {
            $url = admin_url('/admin.php?page=import');
            echo '<form action="' . $url . '#of-option-import" method="get" id="csv_upload" name="csv_upload">
                        <input type="hidden" value="import" name="page">
                        <input type="hidden" value="csvonly" name="emsg">
			</form>
			<script>document.csv_upload.submit();</script>
			';
            exit;
        }
    } else {
        $url = admin_url('admin.php?page=import');
        echo '<form action="' . $url . '#of-option-import" method="get" id="csv_upload" name="csv_upload">
                <input type="hidden" value="import" name="page">
                <input type="hidden" value="invalid_file" name="emsg">
		</form>
		<script>document.csv_upload.submit();</script>
		';
        exit;
    }
}
?>
<div class="wrap" id="of_container">
    <div id="of-popup-save" class="of-save-popup">
        <div class="of-save-save"></div>
    </div>
    <div id="of-popup-reset" class="of-save-popup">
        <div class="of-save-reset"></div>
    </div>
    <div id="header">
        <div class="logo">
            <h2><?php echo IMPRT_EXPRT; ?> <?php echo OPTIONS; ?></h2>
        </div>
        <a href="http://www.inkthemes.com" target="_new">
            <div class="icon-option"> </div>
        </a>
        <div class="clear"></div>
    </div>
    <div id="main">
        <div id="of-nav">
            <ul>
                <li> <a  class="pn-view-a" href="#of-option-import" title="Import Export"><?php echo IMPRT_EXPRT; ?></a></li> 
            </ul>
        </div>
        <div id="content">                                   
            <div class="group" id="of-option-import">
                <div class="section section-text ">
                    <br/>
                    <h3 class="heading"><?php echo "Export to CSV from ads"; ?></h3>
                    <div class="option">
                        <div class="controls">
                            <a class="button-primary" href="<?php echo SETTINGURL . 'cc_export_csv.php' ?>" title="Export to CSV"><?php echo EXPRT_CSV; ?></a>                      
                        </div>
                        <div class="explain"><p> </p></div>
                        <div class="clear"> </div>
                    </div>
                </div>
                <style type="text/css">
                    #submit_csv{
                        width:70px !important;
                    }
                </style>
                <div class="clear"></div>
                <br/><br/>
                <div class="section section-text ">
                    <h3 class="heading"><?php echo UPLD_CSV; ?></h3>
                    <div class="option">
                        <div class="controls">
                            <form action="<?php admin_url('wp-admin/admin.php?page=import'); ?>" method="post"  enctype="multipart/form-data">
                                <input type="file" name="upload_csv" id="upload_csv"/> 
                                <input type="submit" class="button-primary" id="submit_csv" name="submit_csv" value="<?php echo IMPRT; ?>"/>
                            </form>                    
                        </div>
                        <div class="explain"><p> </p></div>
                        <div class="clear"> </div>
                    </div>
                </div>
                <div class="section section-text ">              
                    <div class="option">
                        <div class="controls">
                            <?php
                            if (isset($_REQUEST['upload_msg'])) {
                                $upload_msg = $_REQUEST['upload_msg'];
                                if ($_REQUEST['upload_msg'] == 'success') {
                                    echo "<h3>" . IMPRT_SUCCESS . "</h3>";
                                }
                            } elseif (isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'error') {
                                echo "<h3>" . UPLD_ERR . "</h3>";
                            } elseif (isset($_REQUEST['emsg']) && $_REQUEST['emsg'] == 'invalid_file') {
                                echo "<h3>" . INV_FILE . "</h3>";
                            } elseif (isset($_REQUEST['emsg']) && $_REQUEST['emsg'] == 'csvonly') {
                                echo "<h3>" . ALW_CSV > "</h3>";
                            } elseif (isset($_REQUEST['emsg']) && $_REQUEST['emsg'] == 'tmpfile') {
                                echo "<h3>" . TMP_FILE . "</h3>";
                            }
                            ?>
                        </div>
                        <div class="explain"><p> </p></div>
                        <div class="clear"> </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div> 
        </div>
        <div class="clear"></div>
    </div>
    <div class="save_bar_top">
        <img style="display:none" src="<?php echo ADMINURL; ?>/admin/images/loading-bottom.gif" class="ajax-loading-img ajax-loading-img-bottom" alt="Working..." />
<!--            <input type="submit" id="submit" name="submit" value="<?php echo SAVE_ALL_CHNG; ?>" class="button-primary" />      -->


    </div>            
    <div style="clear:both;"></div>

</div>
<!--wrap-->