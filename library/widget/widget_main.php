<?php
/**
 * Include all files from widget folder 
 */

$file_name = array(
    'category_widget',
    'google_map',
    'recent_add'
);
foreach($file_name as $files):
    if(file_exists(WIDGETPATH.$files.'.php')):
        require_once(WIDGETPATH.$files.'.php');
    endif;
endforeach; 
?>
