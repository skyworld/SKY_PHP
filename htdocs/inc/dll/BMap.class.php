<?php

class BMap{

	public static function getLngLat($city, $locationName){
		$url = "http://api.map.baidu.com/geocoder/v2/?ak=4T2uM4k0OcyRGMY35xG46Wjg&output=json&address={$locationName}&city={$city}";
		$raw = file_get_contents($url);
		$ret = json_decode($raw);
		$hotPlaceObj = new HotPlace();
		if($ret->status === 0){
		
			$data = array(
					'city' => $city,
					'place' => $locationName,
					'lng' => $ret->result->location->lng,
					'lat' => $ret->result->location->lat
				);
			return $data;
		}
		return false;
	}
}

