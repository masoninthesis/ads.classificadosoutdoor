<?php
/**
 * Include all files for post type
 */
$file_name = array(
    'listing_type'
);
foreach($file_name as $files):
    if(file_exists(POSTTYPEPATH.$files.'.php')):
        require_once(POSTTYPEPATH.$files.'.php');
    endif;
endforeach; 
