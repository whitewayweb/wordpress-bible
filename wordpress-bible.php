<?php
/*
Plugin Name: Wordpress Bible
Plugin URI: https://whitewayweb.com
Description: Wordpress Bible
Author: White Way Web
Author URI: https://whitewayweb.com
Text Domain: wordpress-bible
Domain Path: /lang/
Version: 1.0
*/

// Exit if accessed directly
ob_clean(); ob_start();
if ( !defined( 'ABSPATH' ) ) exit;
define("WP_BIBLE_VERSION", '1.0');
define('WPB_FOLDER', basename(dirname(__FILE__)));
define('WPB_DIR', plugin_dir_path(__FILE__));

require_once WPB_DIR . 'functions.php';

/* Load Shortcodes */
require_once WPB_DIR . 'shortcodes/bible.php';

require_once WPB_DIR . 'create-bible-posts.php';
register_activation_hook( __FILE__, 'create_bible_posts' );