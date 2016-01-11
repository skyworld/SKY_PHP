<?php
class GlobalController extends BaseController{

	/**
	 * 城市列表
	 */
	public function cityAction(){
		$this->msg = new Msg(0, '获取城市列表成功', Map::$city);
	}

	/**
	 * 职业描述
	 */
	public function jobAction(){
		$this->msg = new Msg(0, '获取职业列表成功', Map::$job);
	}

	/**
	 * 学校分类
	 */
	public function schoolAction(){
		$db = DB::getInstance();
		$sql = "SELECT a.name province, b.name school FROM t_school a, t_school b WHERE b.cid = a.sid ORDER BY a.sid";
		$schools = $db->get_all($sql);
		$ret = array();
		foreach ($schools as $school) {
			$ret[$school['province']][] = $school['school'];
		}
		$this->msg = new Msg(0, '获取学校列表成功', $ret);
	}

	public function getlocationAction(){
		$city = request::get('city');
		$place = request::get('place');
		$locationInfo = BMap::getLngLat($city, $place);
		
		if(empty($locationInfo)){
			$this->msg = new Msg(-1, '获取坐标失败');
		}else{
			$this->msg = new Msg(0, '获取坐标成功', $locationInfo);
		}
	}
}