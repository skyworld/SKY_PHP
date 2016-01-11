<?php
/*
 * @application shu_manage System
 * @author SkyWorld
 * @copyright SkyWorld
 * @version 1.0.0.0
 * Created on 2010-11-22
 */

class vcode {

	/*
	 * [ch]私有成员属性
	 * [ch]验证码图片的宽度
	 * [ch]验证码图片的高度
	 * [ch]验证码字符的个数
	 * [ch]验证码的内容
	 * [ch]验证码的画布
	 */
	private $width;
	private $height;
	private $codeNum;
	private $checkCode;
	private $image;

	/*
	 * [ch]构造函数,需要参数：图像宽度，图像长度，验证码内容长度
	 */
    function __construct($width=60,$height=20,$codeNum=4){
    	$this->width = $width;
    	$this->height = $height;
    	$this->codeNum = $codeNum;
    	$this->checkCode = $this->createCheckCode();
    }

	/*
	 * [ch]外部通过访问该函数输出验证码图像
	 */
    public function showImage(){
    	$this->getCreateImage();
    	$this->outputText();
    	$this->setDisturbColor();
    	$this->outputImage();
    }

    /*
     * [ch]外部通过访问该函数获取随机创建的验证码内容
     */
    public function getCheckCode(){
    	return $this->checkCode;
    }

    /*
     * [ch]用来创建图像资源，并初始化背景
     */
    private function getCreateImage(){
    	$this->image = imageCreate($this->width , $this->height);
    	$background = imageColorAllocate($this->image,255,255,255);
    	$border = imageColorAllocate($this->image,0,0,0);
    	imageRectangle($this->image,0,0,$this->width-1,$tihs->height-1,$border);
    }

    /*
     * [ch]随机生成指定个数的字符串内容
     */
    private function createCheckCode(){
    	for($i=0 ; $i<$this->codeNum ; $i++){
    		$number = rand(0,2);
    		switch($number){
    			case 0:
    				$randNumber = rand(48,57);  //[ch]ASCII码中对应的数字
    				break;
    			case 1:
    				$randNumber = rand(65,90);  //[ch]ASCII码中对应的大写字母
    				break;
    			case 2:
    				$randNumber = rand(97,122); //[ch]ASCII码中对应的小写字母
    				break;
    		}
    		$ascii = sprintf("%c",$randNumber); //[ch]将整型的数字转化ASCII对的字符
    		$code = $code.$ascii;
    	}
    	return $code;
    }

    /*
     * [ch]该函数用来在图片上绘制100个不同颜色的干扰像素，
     */
    private function setDisturbColor(){
    	for($i=0 ; $i<100 ;$i++){
    		$randColor = imageColorAllocate($this->image,rand(0,255),rand(0,255),rand(0,255));
    		imageSetPixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2),$randColor);
    	}
    }

    /*
     * [ch]随机颜色，随机字符串，随机摆放在图像画布中输出
     */
    private function outputText(){
    	for($i=0 ; $i<$this->codeNum ; $i++){
    		$randColor = imageColorAllocate($this->image,rand(0,150),rand(0,150),rand(0,150));
    		$x = floor($this->width/$this->codeNum)*$i+3;
    		$y = rand(0,$this->height-15);
    		imageChar($this->image,5,$x,$y,$this->checkCode[$i],$randColor);
    	}
    }

	/*
	 *[ch]自动检测GD支持的图像类型，并输出图像
	 */
    private function outputImage(){
    	if(imageTypes()&IMG_GIF){
    		header("Content-type:image/gif");
    		imagegif($this->image);
    	}elseif(imageTypes()&IMG_JPG){
    		header("Content-type:image/jpeg");
    		imagejpeg($this->image);
    	}elseif(imageTypes()&IMG_PNG){
    		header("Content-type:image/png");
    		imagepng($this->image);
    	}elseif(imageTypes()&IMG_WBMP){
    		header("Content-type:image/vnd.wap.wbmp");
    		imagewbmp($this->image);
    	}else{
    		die("the GD is closed");
    	}
    }

	/*
	 * [ch]析构函数，在对象结束之前销毁图像资源释放内存
	 */
    function __destruct(){
    	imagedestroy($this->image);
    }
}
?>