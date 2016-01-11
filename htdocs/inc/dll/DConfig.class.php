<?php
class DConfig{

	/**
	 * 获取配置
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	public static function get($key){
		$db = DB::getInstance();
		$sql = "select value from t_config where `key`='{$key}' order by update_time desc limit 1";
		$rst = $db->get_one($sql);
		if(!empty($rst)){
			return $rst['value'];
		}
		return '';
	}

	/**
	 * 设置或者修改配置
	 * @param [type] $value [description]
	 */
	public static function set($key, $value){
		$db = DB::getInstance();
		$sql = "select id from t_config where `key`='{$key}' order by update_time desc limit 1";
		$rst = $db->get_one($sql);
		if(!empty($rst)){
			// update
			$sql = "update t_config set value='{$value}' where key='{$value}'";
			$db->query($sql);
		}
		else{
			$sql = "insert into t_config (`key`, `value`) value ('{$key}','{$value}')";
			$db->query($sql);
		}
	}

}