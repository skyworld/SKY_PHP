<?php
/**
 * 全局数据类，用于存储一些需要全局使用的变量，比如配置等
 * @author SkyWorld<pgg200@qq.com>
 * @since 2013-12-16
 * @package lib
 */
class Registry
{
	private static $global = array();
	
	public static function set($key, $content)
	{
		self::$global[$key] = $content;
	}
	
	public static function get($key)
	{
		return self::$global[$key];
	}
	
	public static function del($key)
	{
		unset(self::$global[$key]);
	}
	
	public static function get_all_register()
	{
		return self::$global;
	}
}

// end of script