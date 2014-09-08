<?php
ob_start();
//Theme Setup
define('TEMPLATE_PATH', get_template_directory()); //Template directory path
define('TEMPLATEURL', get_template_directory_uri());
define('ADMINPATH', get_template_directory() . '/admin/');
define('ADMINURL', get_template_directory_uri() . '/admin/');
define('LIBRARYPATH', TEMPLATE_PATH . '/library/');
define('LIBRARYURL', get_template_directory_uri() . '/library/');
/**
 * These files build out the options interface.  
 * Likely won't need to edit these. 
 */
if (file_exists(ADMINPATH . 'admin_main.php')) {
    include_once (ADMINPATH . 'admin_main.php'); // manage theme filters in the file
}
/**
 * Include core library file 
 */
if (file_exists(LIBRARYPATH . 'lib_main.php')) {
    include_once (LIBRARYPATH . 'lib_main.php'); // manage theme filters in the file
}
ob_clean();
