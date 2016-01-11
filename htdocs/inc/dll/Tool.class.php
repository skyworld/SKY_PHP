<?php

class Tool{

	/**
	 * 发送短信·
	 */
	public static function sendMsg($mobile, $content){
		$global_conf = Registry::get('global_conf');
		$userid = $global_conf['msg_userid'];
		$account = $global_conf['msg_account'];
		$password = $global_conf['msg_pwd'];
		$sign = $global_conf['msg_sign'];
		$content .= "【{$sign}】";
		$gateway = "http://sh.ipyy.com:8888/sms.aspx?action=send&userid={$userid}&account={$account}&password={$password}&mobile={$mobile}&content={$content}&sendTime=";
		$result = file_get_contents($gateway);
		$xml = simplexml_load_string($result);
		return $xml->successCounts ? true : false;
	}

}