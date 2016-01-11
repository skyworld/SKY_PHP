<?php
/**
 * If you are not authorized to modify this file, do NOT touch it.
 * @file ValidationCode.class.php
 * @author SkyWorld
 * @date 2010-12-2
 * @description The ValidationCode object represents the validation code
 */

if (!defined('IN_NCG')) exit('Access Denied.');

class ValidationCode {

	private $width;
	private $height;
	private $codeNum;
	private $checkCode;
	private $image;

	/**
	 * @name __construct
	 * @author Skyworld
	 * @date 2010-12-4
	 * @description  This is the constructor of validation code object
	 * @parameters
	 *    integer: $width is the width of the validation code
	 *    integer: $height is the height of the validation code
	 * 	  integer: $codeNum is the length fo the validation code
	 * @return void
	 */
    function __construct($width=60,$height=20,$codeNum=4){
    	$this->width = $width;
    	$this->height = $height;
    	$this->codeNum = $codeNum;
    	$this->checkCode = $this->CreateCheckCode();
    }

	/**
	 * @name ShowImage
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a public function to be used to show the image of validation code
	 * @parameters void
	 * @return void
	 */
    public function ShowImage(){
    	$this->GetCreateImage();
    	$this->OutputText();
    	$this->SetDisturbColor();
    	$this->OutputImage();
    }

	/**
	 * @name GetCheckCode
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a public function to be used to get the validation code
	 * @parameters void
	 * @return string: the validation code
	 */
    public function GetCheckCode(){
    	return $this->checkCode;
    }

	/**
	 * @name GetCreateImage
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a private function to be used to get the image of validation code
	 * @parameters void
	 * @return void
	 */
    private function GetCreateImage(){
    	$this->image = imageCreate($this->width , $this->height);
    	$background = imageColorAllocate($this->image,255,255,255);
    	$border = imageColorAllocate($this->image,0,0,0);
    	imageRectangle($this->image,0,0,$this->width-1,$tihs->height-1,$border);
    }

	/**
	 * @name CreateCheckCode
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a private function to be used to create the validation code
	 * @parameters void
	 * @return string: the validation code
	 */
    private function CreateCheckCode(){
    	for($i=0 ; $i<$this->codeNum ; $i++){
    		$number = rand(0,2);
    		switch($number){
    			case 0:
    				$randNumber = rand(48,57);
    				break;
    			case 1:
    				$randNumber = rand(65,90);
    				break;
    			case 2:
    				$randNumber = rand(97,122);
    				break;
    		}
    		$ascii = sprintf("%c",$randNumber); //chage integers to chars
    		$code = $code.$ascii;
    	}
    	return $code;
    }

	/**
	 * @name SetDisturbColor
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a private function to be used to set 100 disturb color
	 * @parameters void
	 * @return void
	 */
    private function SetDisturbColor(){
    	for($i=0 ; $i<100 ;$i++){
    		$randColor = imageColorAllocate($this->image,rand(0,255),rand(0,255),rand(0,255));
    		imageSetPixel($this->image,rand(1,$this->width-2),rand(1,$this->height-2),$randColor);
    	}
    }

	/**
	 * @name OutputText
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a private function to be used input the validation code to the image
	 * @parameters void
	 * @return void
	 */
    private function OutputText(){
    	for($i=0 ; $i<$this->codeNum ; $i++){
    		$randColor = imageColorAllocate($this->image,rand(0,150),rand(0,150),rand(0,150));
    		$x = floor($this->width/$this->codeNum)*$i+3;
    		$y = rand(0,$this->height-15);
    		imageChar($this->image,5,$x,$y,$this->checkCode[$i],$randColor);
    	}
    }

	/**
	 * @name OutputImage
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  a private function to be used ouput image of validation code
	 * @parameters void
	 * @return void
	 */
    private function OutputImage(){
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
    		die("the GD library is closed");
    	}
    }

	/**
	 * @name OutputImage
	 * @author SkyWorld
	 * @date 2010-12-4
	 * @description  the destructor of ValidationCode object
	 * @parameters void
	 * @return void
	 */
    function __destruct(){
    	imagedestroy($this->image);
    }
}

