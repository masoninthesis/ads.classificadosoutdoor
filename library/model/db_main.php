<?php
/**
 * Include all db files 
 */
$file_name = array(
    'db_function',
    'db_create'
);
foreach($file_name as $files):
    if(file_exists(MODELPATH.$files.'.php')):
        require_once(MODELPATH.$files.'.php');
    endif;
endforeach;
