<?php

$file_name = array(
    'admin-functions',
    'admin-interface',
    'theme-options',
    'install',
    'cc_functions',
    'dynamic-image'
);
foreach ($file_name as $files):
    if (file_exists(ADMINPATH . $files . '.php')):
        require_once(ADMINPATH . $files . '.php');
    endif;
endforeach;
?>
