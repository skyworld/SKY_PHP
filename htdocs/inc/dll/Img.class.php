<?php
/**
 * 图片处理类，主要用于完成图片的裁剪等处理
 * @author SkyWorld<pgg200@qq.com>
 * @package inc.dll
 * @since 2014-01-05
 */
class Img
{
	
	/**
	 * 图片的裁切
	 */
	public static function cut($conf)
	{
		try
		{
			$s_data = getimagesize($conf['src'], $info);
			$ori_w = $s_data[0];
			$ori_h = $s_data[1];
			$d_w = $conf['d_w'];
			$d_h = $conf['d_h'];
			$d_x = $conf['d_x'];
			$d_y = $conf['d_y'];
			$s_w = $conf['s_w'];
			$s_h = $conf['s_h'];
			$last_w = $conf['last_w'];
			$last_h = $conf['last_h'];
			Log::debug($s_w);
			Log::debug($s_h);
			Log::debug($d_w);
			Log::debug($d_h);
			Log::debug($d_x);
			Log::debug($d_y);
			$rate = $ori_w/$s_w;
			Log::debug("====");
			Log::debug($ori_w);
			Log::debug($ori_h);
			Log::debug("====");
			$d_x = round($d_x*$rate);
			$d_y = round($d_y*$rate);
			$d_w = round($d_w*$rate);
			$d_h = round($d_h*$rate);
			Log::debug($rate);
			Log::debug($d_x);
			Log::debug($d_y);
			Log::debug($d_w);
			Log::debug($d_h);
			switch($s_data[2])
			{
				case 1:
					$img_r = imagecreatefromgif($conf['src']);
					break;
				case 2:
					$img_r = imagecreatefromjpeg($conf['src']);
					break;
				case 3:
					$img_r = imagecreatefrompng($conf['src']);
					break;
			}
			Log::debug("====================");
			Log::debug($conf['last_w']);
			Log::debug($conf['last_h']);
			Log::debug("=========================");
			
			$dst_r = ImageCreateTrueColor($conf['last_w'],$conf['last_h']);
			imagecopyresampled($dst_r,$img_r,0,0,$d_x,$d_y, $conf['last_w'],$conf['last_h'], $d_w,$d_h);
			
			switch($s_data[2])
			{
				case 1:
					imagejpeg($dst_r, $conf['d_src'], 100);
				case 2:
				case 3:
					imagejpeg($dst_r, $conf['d_src'], 100);
			}
			return true;
		}
		catch(Exception $e)
		{
			return false;
		}
	}
	
}

