<?php

/**
 * Cookie操作管理类，包括Cookie的增删改
 * @author SkyWorld<pgg200@qq.com>
 * @since 2013-12-19
 * @package lib
 */
class cookie
{
	/**
	 * 设置cookie
	 * @param Array $content cookie的内容
	 * @param Number $expire 失效时间
	 * @return void
	 */
	public static function set($content, $expire = 86400)
	{
		$keys = array_keys($content);
		foreach($content as $key => $val)
		{
			setcookie($key, $val, time()+$expire, '/'); 
		}
	}
	
	/**
	 * 获取一个COOKIE值
	 * @param String $key cookie的键值
	 * @return String cookie的内容
	 */
	public static function get($key)
	{
		return $_COOKIE[$key];
	}
	
	/**
	 * 删除一个COOKIE值
	 * @param String $key cookie的键值
	 * @return String cookie的内容
	 */
	public static function del($key)
	{
		setcookie($key, $val, time() - 3600, '/'); 
	}
}

// end off script