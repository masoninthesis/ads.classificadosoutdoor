<?php
/**
 * This is core file for geocraft library
 * It includes all funtional and visual files  
 */
/**
 * This files contains all constaints 
 * variables.  
 */
if(file_exists(LIBRARYPATH . 'constants.php'))
{
	include_once(LIBRARYPATH.'constants.php');
}
/**
 * Include textdomain file for language translation
 * This file contains all textdomain variables.
 * These textdomains are used to global theme
 * Translation.
 */
if(file_exists(LIBRARYPATH . 'textdomain.php'))
{
	include_once(LIBRARYPATH.'textdomain.php');
}
/**
 * Include files from model folder that
 * Intereacts with databases 
 */
if(file_exists(MODELPATH . 'db_main.php'))
{
	include_once(MODELPATH.'db_main.php');
}


/**
 * Include all files from control folder. 
 * These files are used to control and handle functionality 
 * 
 */
if(file_exists(CONTROLPATH . 'ctrl_main.php'))
{
	include_once(CONTROLPATH.'ctrl_main.php');
}
/**
 * Include widget files from widget folder
 *  
 */
if(file_exists(WIDGETPATH . 'widget_main.php'))
{
	include_once(WIDGETPATH.'widget_main.php');
}
/**
 * Include gateway files from paypal folder
 */
if(file_exists(GATEWAYPATH . 'paypal/paypal-ipn.php'))
{
	include_once(GATEWAYPATH.'paypal/paypal-ipn.php');
}