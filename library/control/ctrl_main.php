<?php
/**
 * Include all files for controls
 */
$file_name = array(
    'theme_functions',
    'post_type_main',
    'setting_main',
    'cc_submit',
    'cc_metabox_2',
    'cc_metabox',
    'cc_transaction',
    'user_auth',
    'cc_expiry'
);
foreach($file_name as $files):
    if(file_exists(CONTROLPATH.$files.'.php')):
        require_once(CONTROLPATH.$files.'.php');
    elseif(file_exists(POSTTYPEPATH.$files.'.php')):
        require_once(POSTTYPEPATH.$files.'.php');
    elseif(file_exists(SETTINGPATH.$files.'.php')):
        require_once(SETTINGPATH.$files.'.php');
    elseif(file_exists(METAPATH.$files.'.php')):
        require_once(METAPATH.$files.'.php');
    endif;
endforeach; 
