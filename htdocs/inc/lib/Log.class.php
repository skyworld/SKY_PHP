<?php
/**
 * Log服务类，记录日常的一些文本Log
 * @author SkyWorld<pgg200@qq.com>
 * @since 2013-12-16
 * @package lib
 */
class Log
{
	/**
	 * Log文件路径
	 */
	public static $log_path = '';
	
	public static $log_file = '';
	
	/**
	 * 是否为调试模式
	 */
	public static $debug = false;
	
	/**
	 * 初始化，初始化Log的目录
	 * @parma String $log_name log的名称
	 */
	public static function init($log_name)
	{
		if(empty(self::$log_path))
		{
			$global_conf = Registry::get('global_conf');
			self::$log_path = $global_conf['log_path'];
			self::$debug = $global_conf['debug'];
		}
		self::$log_file = SKY_ROOT.self::$log_path.'/'.$log_name.'.log';
	}
	
	/**
	 * 写log的方法
	 * @param String $msg log的内容
	 * @param String $msg_type log的类型  INFO 信息， WARN 警告， ERROR 错误 
	 */
	public static function write($msg, $msg_type = 'INFO')
	{
		// 当处于debug模式或者出现严重错误的时候，写文本Log
		//if(self::$debug  || $msg_type == 'ERROR')
		//{
			$log = fopen(self::$log_file, 'a+');
			$datetime = date('Y-m-d H:i:s');
			$ip = $_SERVER["REMOTE_ADDR"];
			$content = $datetime."[{$ip}][{$msg_type}]:".$msg."\r\n";
			fwrite($log, $content);
			fclose($log);
		//}

		// 出现错误，程序终止运行
		if($msg_type == 'ERROR')
		{
			die('ERROR');
		}
	}

	/**
	 * 写调试信息的方法
	 * @param String $msg log的内容
	 */	
	public static function debug($msg)
	{
		if(self::$debug)
		{
			self::write($msg, 'DEBUG');
		}
	}
}
// end off script