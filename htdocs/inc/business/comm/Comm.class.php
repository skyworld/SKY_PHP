<?php
class Comm {
	
	/**
	 * 验证是否登录，如果已经登录，返回登录的uid 和 phone 否则返回false
	 * @param  [type] $token [description]
	 * @return [type]        [description]
	 */
	public static function checkLogin($token){
		$uid = security::decode(base64_decode($token));
		if(empty($uid)) return false;
		$db = DB::getInstance();
		$sql = "select a.uid, b.enable from t_login a , t_user b where a.uid={$uid} and a.token = '{$token}' and a.uid = b.uid limit 1";
		$rst = $db->get_one($sql);
		if(empty($rst)) return false;
		return $rst;
	}

	public static function getCity($city){
		$city = strtolower($city);
		if(!empty(Map::$cityPyMap[$city])){
			return Map::$cityPyMap[$city];
		}
		return $city;
	}
}