<?php
/**
 * request处理器，用于解析request请求
 * @author SkyWorld<pgg200@qq.com>
 * @package lib
 * @since 2013-12-16
 */

class request
{
	/**
	 * @param String $url 请求的URL
	 */
	public static $url;
	
	/**
	 * @param Array $query_arr 请求参数的数组
	 */
	public static $query_arr;
	
	/**
	 * @param String $controller_name 控制器的名称
	 */
	public static $controller_name;
	
	/**
	 * @param String $action_name Action名称
	 */
	public static $action_name;
	
	
	/**
	 * 初始化
	 */
	private static function init()
	{
		if(empty(self::$url))
		{
			self::$url = trim($_SERVER['REQUEST_URI'],'/');
			$tmp = explode('?',self::$url);
			$params = explode('/',$tmp[0]);
			if(count($params)>=2)
			{
				for($i=2; $i<count($params); $i+=2)
				{
					if(isset($params[$i+1]))
					{
						self::$query_arr[$params[$i]] = removeEmoji($params[$i+1]);
					}
				}
				self::$controller_name = $params[0];
				self::$action_name = $params[1];
			}
			
			foreach($_REQUEST as $key => $val)
			{
				self::$query_arr[$key] = $val;
			}
		}
	}
	
	/**
	 * get方法，例如通过request::get('query_str')
	 * @param String $key 请求的字符串
	 * @return String 对应的结果
	 */
	public static function get($key)
	{
		 self::init();
                if(isset(self::$query_arr[$key])){
                        return sql_injection(removeEmoji(self::$query_arr[$key]));
                }
                return null;

	}
	
	public static function get_controller_name()
	{
		self::init();
		return self::$controller_name;
	}
	
	public static function get_action_name()
	{
		self::init();
		return self::$action_name;
	}	
} 

// end of script
