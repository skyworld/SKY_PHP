<?php
/**
 * 网站配置类
 * 
 */
class config
{
	private static $_default_db_adapter = null;
	/**
	 * 获取数据库的配置
	 */
	public static function dbConfig($adapter)
	{
		$dbConfig = Registry::get('db_conf');
		if(isset($dbConfig[$adapter]))
		{
			return $dbConfig[$adapter];
		}
		else
		{
			die("Error<sup>[1]</sup>: adapter not found for db config.");
		}
	}
	
	public static function getDefaultAdapter()
	{
		if(empty(self::$_default_db_adapter))
		{
			$dbConfig = Registry::get('db_conf');
			if(count($dbConfig) == 1)
			{
				foreach($dbConfig as $key => $val)
				{
					self::$_default_db_adapter = $key;
					return $key;
				}
			}
			else
			{
				foreach($dbConfig as $key => $val)
				{
					if($val['is_default'])
					{		
						self::$_default_db_adapter = $key;
						return $key;
					}
				}				
			}
		}
		return self::$_default_db_adapter;
	}

	public static function get($key){
		$conf = Registry::get('global_conf');
		if(isset($conf[$key]))
		{
			return $conf[$key];
		}
		return '';
	}
}

// end of script