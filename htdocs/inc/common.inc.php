<?php
/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file common.inc.php
 * @author SkyWorld
 * @date 2010-9-12
 * @description The file which should be included by any application based on this framework.
 */

date_default_timezone_set('Asia/Shanghai');
//ob_start();
//session_start();

 /**
  * Script start form here, te following code is for start time record
  */
$mtime = explode(' ', microtime());
$sky_start_time = $mtime[1] + $mtime[0];
unset($mtime);


 /**
 * The following codes are for security issues.
 */

define('IN_SKY', true); //Preventing illegal access to framework files.

/**
 * The following definitions are for portability issues.
 */

define('SKY_ROOT',str_replace('\\','/',substr(dirname(__FILE__),0,-3)));
define('SKY_INC',SKY_ROOT.'inc/');
define('SKY_LIB', SKY_INC . 'lib/');
define('SKY_DLL', SKY_INC.'dll/');
define('SKY_PLUGIN', SKY_INC.'plugin/');
define('SKY_INSTALL', SKY_ROOT.'install/');
define('SKY_CONFIG', SKY_ROOT.'config/');
define('SKY_STYLE', SKY_ROOT.'style/');
define('SKY_CONTROLLER',SKY_ROOT.'controller/');
define('SKY_BUSINESS', SKY_INC.'business/');
define('SKY_BUSINESS_SERVICE', SKY_BUSINESS.'service/'); 
define('SKY_BUSINESS_COMM', SKY_BUSINESS.'comm/'); 
define('SKY_VIEW',SKY_ROOT.'view/');
define('SKY_MODEL',SKY_ROOT.'model/');

/**
 * The following definitions are for code readability.
 */

require_once SKY_LIB.'global.func.php';
require_once SKY_DLL.'verify.func.php';

spl_autoload_register('autoload');



/**
 * The following work are for security reason
 */
/*
set_magic_quotes_runtime(0);
if(!get_magic_quotes_gpc())
{
	$_POST = new_addslashes($_POST);
	$_GET = new_addslashes($_GET);
}
*/
$search_arr = array('/union/i','/select/i','/update/i','/outfile/i','/or/i');
$replace_arr = array('union', 'select', 'update', 'outfile', 'or');

$_POST = strip_sql($_POST);
$_GET  = strip_sql($_GET);
$_COOKIE = strip_sql($_COOKIE);

unset($search_arr,$replace_arr);

/**
 * The following definitions are for readability of code
 */

$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
$SCRIPT_FILENAME = str_replace('\\\\', '/', (isset($_SERVER['PATH_TRANSLATED']) ? $_SERVER['PATH_TRANSLATED'] : $_SERVER['SCRIPT_FILENAME']));

//@extract($_POST,EXTR_OVERWRITE);
//@extract($_GET,EXTR_OVERWRITE);


/**
 * Date time related initialization.
 */

$CONF = include(SKY_CONFIG.'global.conf.php');
$DB_CONF = include(SKY_CONFIG.'db.conf.php');
Registry::set('global_conf',$CONF);
Registry::set('db_conf',$DB_CONF);

if(function_exists('data_default_set')) date_default_timezone_set($CONF['timezone']);

/**
 * Set page charset
 */
//header('Content-type:text/html; charset='.$CONF['charset']);

