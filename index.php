<?php
/*
 * ForceFlexing v0.5.3
 */

//Start the Session
session_start(); 
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Defines
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('APP_DIR', ROOT_DIR .'application/');
define('PREFIX', 'flex_');
define('SITEURL', 'http://force.imarkclients.com/');

// Includes
require(APP_DIR .'config/config.php');
require(ROOT_DIR .'system/model.php');
require(ROOT_DIR .'system/view.php');
require(ROOT_DIR .'system/controller.php');
require(ROOT_DIR .'system/backend.php');
require(ROOT_DIR .'system/force.php');

// Define base URL
global $config;
define('BASE_URL', $config['base_url']);
define('ABSPATH', dirname(__FILE__));
force();

 
?>
