<?php
$file_name = array(
    'dashboard_function',
    'settings',
    'price_package',
    'payment_setting',
    'manage_currency',
);
foreach($file_name as $files):
    if(file_exists(SETTINGPATH.$files.'.php')):
        require_once(SETTINGPATH.$files.'.php');
    endif;
endforeach;