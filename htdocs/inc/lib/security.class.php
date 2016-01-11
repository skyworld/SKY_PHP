<?php

class security
{
	/**
	 * 加密与解密的密钥
	 */
	private static $key = null;

	/**
	 * 解密函数
	 */
	public static function decode($string)
	{
		self::init();
		$rst = self::authcode($string, 'DECODE', self::$key);
		return $rst;
	}
	
	/**
	 * 加密函数
	 */
	public static function encode($string, $expiry = 0)
	{
		self::init();
		$rst = self::authcode($string, 'ENCODE', self::$key, $expiry);
		return $rst;
	}
	
	/**
	 * 初始化函数，获取加密与解密的Key
	 */
	public static function init()
	{
		if(empty(self::$key))
		{
			$global_conf = Registry::get('global_conf');
			self::$key = $global_conf['key'];
		}
	}
	
	/**
	 * 用于基于时间戳的加密与解密函数
	 * @param String $string 要加密或者解密的字符串
	 * @param String $operation 加密或者界面的操作 ENCODE 加密 DECODE解密
	 * @param String $key 加密和解密的密钥
	 * @param Number $expiry 失效时间  0为永久有效, 默认3600秒
     * @reference http://wenku.baidu.com/view/e150b820dd36a32d737581f5.html
     * @return $string 加密或者解密后的字符串
     * @example
	 *  $a = code('abc', 'ENCODE', 'key');
	 *  $b = code($a, 'DECODE', 'key');  // $b(abc)
	 *
	 *  $a = code('abc', 'ENCODE', 'key', 3600);
	 *  $b = code('abc', 'DECODE', 'key');
     **/
	private static function  authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		$ckey_length = 4; //随机密钥的长度，0~32位，取值为0时，不产生随机密钥，取值越大，密文变动越大

		$key = md5($key ? $key : 'skyworld'); //默认的key，可以自己定义
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
					return '';
				}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}

	}	
}

// end of script