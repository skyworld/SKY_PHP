<?php
/**
 * 单入口引导文件
 * @author skyworld<pgg200@qq.com>
 */
require_once 'inc/common.inc.php';


/**
 * 如果为bebug模式，则输出错误，否则屏蔽错误
 */
if($CONF['debug'])
{
	ini_set("display_errors","1"); 
	error_reporting(E_ALL & ~E_NOTICE);	
}
else
{
	ini_set("display_errors","0"); 
}

//ini_set('allow_url_fopen','On');
//ini_set('allow_url_include','On');


/**
 * 支持MVC的单入口方式
 * 访问方式为hostname/index.php?_c=controller_name&_a=action_name
 * 可以通过配置.htaccess文件配置成hotname/controller_name/action_name的方式
 */
$controller_name = request::get_controller_name();
$action_name = request::get_action_name();

/**
 * 如果没有输出控制器名和action名
 * 就从配置中读取默认的控制器名和action名
 */
if(empty($controller_name) || empty($action_name))
{
	$controller_name = $CONF['default_controller'];
	$action_name = $CONF['default_action'];
}

/**
 * 首字母大写处理
 */
$controller_name = ucwords($controller_name);
$action_name = ucwords($action_name);

/**
 * 检查控制器和Action是否存在
 */
$controller_file = SKY_CONTROLLER.$controller_name.'Controller.class.php';
if(!is_file($controller_file))
{
	die("Controller [{$controller_name}] is NOT exists!");
}
$controller_class = $controller_name.'Controller';
$action_function = $action_name.'Action';

if(!method_exists($controller_class, $action_function))
{
	die("Action [{$action_name}] is NOT exists!");
}

/**
 * 控制器名和Action存入全局区
 */
Registry::set('controller_name', $controller_name);
Registry::set('action_name', $action_name);

/**
 * 运行controller和action
 */
$controller = new $controller_class;
$controller->$action_function();