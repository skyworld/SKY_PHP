<?php
/**
 * view 操作类，用于模板输出
 * @author SkyWorld<pgg200@qq.com>
 * @since 2013-12-16
 * @package lib
 */
 
class view
{
	/**
	 * @param Array $assign_content 用于输出到模板里的内容
	 */
	private static $assign_content = array();
	
	/**
	 * @param String $view_dir 视图文件的文件夹，默认为VIEW的根目录
	 */
	private static $view_dir = '';
	
	/**
	 * 设置view所在的文件夹，比如view::package('/admin/news/');
	 * @param String $package 文件夹路径
	 */
	public static function package($package)
	{
		self::$view_dir = $package;
	}
	
	/**
	 * 加载模板
	 * @param String $tpl_name 模板名称
	 */
	public static function tpl($tpl_name)
	{
		$tpl_path = SKY_VIEW.self::$view_dir.$tpl_name.'.tpl.php';
		if(is_file($tpl_path))
		{
			include($tpl_path);
		}
		else
		{
			die("template [{$tpl_name}] can NOT be found");
		}
	}
	
	/**
	 * 分配变量，用于输出到模板，改函数用了一点小技巧实现了函数的重载
	 * 
	 */
	public static function assign()
	{
		$arg_num = func_num_args();
		$args = func_get_args();
		if($arg_num == 1)
		{
			self::assign_array($args[0]);
		}
		if($arg_num == 2)
		{
			self::assign_value($args[0], $args[1]);
		}
	}
	
	private static function assign_value($key, $content)
	{
		self::$assign_content[$key] = $content;
	}
	
	private static function assign_array($array)
	{
		foreach($array as $key => $content)
		{
			self::$assign_content[$key] = $content;
		}
	}
	
	public static function get($key)
	{
		return self::$assign_content[$key];
	}
	
	public static function display($key)
	{
		echo self::$assign_content[$key];
	}
}
// end off script