<?php
/**
 * redis 客户端处理类
 * @author skyword<pgg200@qq.com>
 * @since v1.1 2014-3-23
 */
class redis
{
	private static $_instance;
	private static $_r;
	
	/**
	 * 单例模型，获取实例的方法
	 * @return [Object] [redis操作对象的一个实例，该实例已经完成了连接操作]
	 */
	public static function getInstance()
	{
		if(!(self::$_instance instanceof self))
		{
			$conf = Registry::get('global_conf');
			self::$_instance = new redis();
			self::$_r = new Credis_Client($conf['redis_server_url'],$conf['redis_server_port']);
		}
		return self::$_instance;
	}
	
	/**
	 * [__call 万能的call方法，可以完成动态方法的调用]
	 * @param  [Sting] $name [方法名称]
	 * @param  [Array] $args [方法参数]
	 * @return [Mix]       [对应方法返回的结果]
	 */
	public function __call($name, $args)
	{
		return self::$_r->__call($name, $args);
	}
}