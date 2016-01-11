<?php
/**
 * 上传图片类,包括生成缩略图之类的
 * @author carlosguo
 * @package lib
 * @since 2013-12-18
 */


class UploadImg {
	static protected $a;
	protected $formName; //表单名称
	protected $directory; //文件上传至目录
	protected $maxSize; //最大文件上传大小
	protected $canUpload; //是否可以上传
	protected $doUpFile; //上传的文件名
	protected $sm_File; //缩略图名称
	protected $sm_directory;
	
	public function __construct($_formName = 'file', $_directory = './images/uploads/', $sm_directory = 'tmp/sm/', $_maxSize = 1048576) { //1024*1024=1M
		//初始化参数
		$this->formName = $_formName;
		$this->directory = $_directory;
		$this->sm_directory = $sm_directory;
		$this->maxSize = $_maxSize;
		$this->canUpload = true;
		$this->doUpFile = '';
		$this->sm_File = '';
	}
	
	//判断图片是否属于允许格式内
	static public function Type($_formName = 'file') {
		$_type = $_FILES[$_formName]['type'];
		switch ($_type) {
		case 'image/gif':
			if (self::$a == NULL)
				self::$a = new imgUpload($_formName);
			break;
		case 'image/pjpeg':
			if (self::$a == NULL)
				self::$a = new imgUpload($_formName);
			break;
		case 'image/x-png':
			if (self::$a == NULL)
				self::$a = new imgUpload($_formName);
			break;
		default:
			self::$a = false;
		}
		return self::$a;
	}
	
	//获取文件大小
	public function getSize($_format = 'K') {
		if ($this->canUpload) {
			if (0 == $_FILES[$this->formName]['size']) {
				$this->canUpload = false;
				return $this->canUpload;
				break;
			}
			switch ($_format) {
			case 'B':
				return $_FILES[$this->formName]['size'];
				break;
			case 'K':
				return round($_FILES[$this->formName]['size'] / 1024);
				break;
			case 'M':
				return round($_FILES[$this->formName]['size'] / (1024 * 1024), 2);
				break;
			}
		}
	}
	
	//获取文件类型
	public function getExt() {
		if ($this->canUpload) {
			$_name = $_FILES[$this->formName]['name'];
			$_nameArr = explode('.', $_name);
			$_count = count($_nameArr) - 1;
		}
		return $_nameArr[$_count];
	}
	
	//获取文件名称
	public function getName() {
		if ($this->canUpload) {
			return $_FILES[$this->formName]['name'];
		}
	}
	
	//新建文件名
	public function newName() {
		return date('YmdHis').rand(0, 9);
	}
	
	//上传文件
	public function upload() {
		if ($this->canUpload) {
			$_getSize = $this->getSize('B');
			Log::debug('size:'.$_getSize);
			if (!$_getSize) {
				return $_getSize;
				break;
			} else {
				$_newName = $this->newName();
				$_ext = $this->getExt();
				Log::debug('ext:'.$_ext);
				$_doUpload = move_uploaded_file($_FILES[$this->formName]['tmp_name'], $this->directory.$_newName.".".$_ext);
				Log::debug('upload:'.$_doUpload);
				if ($_doUpload) {
					$this->doUpFile = $_newName;
				}
				return $_doUpload;
			}
		}
	}
		
	public function roundThumb($width){
		if($this->canUpload && $this->doUpFile != ''){
			$_ext = $this->getExt();
			$_srcImage = SKY_ROOT.$this->sm_File;
			$_dstImage = SKY_ROOT.$this->sm_directory.$this->doUpFile."_round.".$_ext;
			$_dstImageRound = SKY_ROOT.$this->sm_directory.$this->doUpFile."_round.png";
			$ic=new ImgCrop($_srcImage, $_dstImage);
			$ic->Crop($width,$width,4);
			$ic->SaveImage();
			$ic->destory();

			$rounder = new RoundCorner($_dstImage,$width/2);
			$rounder->round_it($_dstImageRound);
			unlink($_dstImage);
		}
	}

	//创建正方形缩略图
	public function thumb($width = 280, $_dstChar = '') { //$_dstChar:_m , _s
		if($this->canUpload && $this->doUpFile != ''){
			$_ext = $this->getExt();
			$_srcImage = $this->directory.$this->doUpFile.".".$_ext;
			$this->sm_File = $_dstImage = $this->sm_directory.$this->doUpFile.$_dstChar.".".$_ext;
		
			$ic=new ImgCrop($_srcImage, $_dstImage);
			$ic->Crop($width,$width,1);
			$ic->SaveImage();
			$ic->destory();
			
		}

		/*
		if ($this->canUpload && $this->doUpFile != "") {
			$_ext = $this->getExt();


			$_srcImage = $this->directory.$this->doUpFile.".".$_ext;
			
			//得到图片信息数组
			$_date = getimagesize($_srcImage, $info);
			
			$src_w = $_date[0]; //源图片宽
			$src_h = $_date[1]; //源图片高
			$src_max_len = max($src_w, $src_h); //求得长边
			$src_min_len = min($src_w, $src_h); //求得短边
			$dst_w = ''; //目标图片宽
			$dst_h = ''; //目标图片高
			
			//宽高按比例缩放，最长边不大于$_max_len
			if ($src_max_len > $_max_len) {
				$percent = $src_min_len / $src_max_len;
				if ($src_w == $src_max_len) {
					$dst_w = $_max_len;
					$dst_h = $percent * $dst_w;
				} else {
					$dst_h = $_max_len;
					$dst_w = $percent * $dst_h;
				}
			} else {
				$dst_w = $src_w;
				$dst_h = $src_h;
			}
			
			//建立缩略图时,源图片的位置
			$src_x = 0;
			$src_y = 0;
			
			//判断如果缩略图用于logo，将对其进行裁减
			if ('s_' == $_dstChar) {
				$src_x = $src_w * 0.10;
				$src_y = $src_h * 0.10;
				$src_w *= 0.8;
				$src_h *= 0.8;
			}
			
			//判断图片类型并创建对应新图片
			switch ($_date[2]) {
			case 1:
				$src_im = imagecreatefromgif($_srcImage);
				break;
			case 2:
				$src_im = imagecreatefromjpeg($_srcImage);
				break;
			case 3:
				$src_im = imagecreatefrompng($_srcImage);
				break;
			case 8:
				$src_im = imagecreatefromwbmp($_srcImage);
				break;
			}
			
			//创建一幅新图像
			if ($_date[2] == 1) { //gif无法应用imagecreatetruecolor
				$dst_im = imagecreate($dst_w, $dst_h);
			} else {
				$dst_im = imagecreatetruecolor($dst_w, $dst_h);
			}
			
			//对这副图像进行缩略图copy
			//                        $bg = imagecolorallocate($dst_im,255,255,0);
			//$dst_im =imagecopyresized($src_im, $src_x, $src_y, $src_w, $src_h );
			imagecopyresized($dst_im, $src_im, 0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
			
			//对图片进行抗锯齿操作
			imageantialias($dst_im, true);
			
			switch ($_date[2]) {
			case 1:
				$cr = imagegif($dst_im, $this->sm_directory.$this->doUpFile.$_dstChar.".".$_ext, 100);
				break;
			case 2:
				$cr = imagejpeg($dst_im, $this->sm_directory.$this->doUpFile.$_dstChar.".".$_ext, 100);
				break;
			case 3: //imagepng有问题，所以在这里用imagejpg代替
				$cr = imagejpeg($dst_im, $this->sm_directory.$this->doUpFile.$_dstChar.".".$_ext, 100);
				break;
			}
			//                        $cr = imagejpeg($dst_im, $this->directory.$_dstChar.$this->doUpFile, 90);
			if ($cr) {
				$this->sm_File = $this->sm_directory.$this->doUpFile.$_dstChar.".".$_ext;
				
				return $this->sm_File;
			} else {
				return false;
			}
		}
		imagedestroy($dst_im);
		imagedestroy($cr);
		*/
	}
	
	//得到上传后的文件名
	public function getUpFile() {
		if ($this->doUpFile != '') {
			$_ext = $this->getExt();
			return $this->doUpFile.".".$_ext;
		} else {
			return false;
		}
	}
	
	//得到上传后的文件全路径
	public function getFilePath() {
		if ($this->doUpFile != '') {
			$_ext = $this->getExt();
			return $this->directory.$this->doUpFile.".".$_ext;
		} else {
			return false;
		}
	}
	
	//得到缩略图文件全路径
	public function getThumb() {
		if ($this->sm_File != '') {
			return $this->sm_File;
		} else {
			return false;
		}
	}
	//得到上传文件的路径
	public function getDirectory() {
		if ($this->directory != '') {
			return $this->directory;
		} else {
			return false;
		}
	}
}
// end off script
