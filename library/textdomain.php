<?php

/**
 * Include all text domain files for translation  
 */
$file_name = array(
    'lib_path',
    'admin_path',
    'ad_type_lang',
    'template_path',
    'pricing_path',
    'model_path',
    'widget_path',
    'js_path',
    'revisions'
);
foreach ($file_name as $files):
    if (file_exists(TEXTDOMAINPATH . $files . '.php')):
        require_once(TEXTDOMAINPATH . $files . '.php');
    endif;
endforeach;
?>
