<?php



// 常量定义，for 可读性
define('LOGIN_TYPE_PHONE',1);
define('LOGIN_TYPE_WECHAT',2);
define('LOGIN_TYPE_QQ', 3);
define('LOGIN_TYPE_WEIBO',4);


/**
 * 用户服务类
 */
class UserService
{
	private $user = null;
	private $login = null;
	public function __construct()
	{
		$this->user = new User();
		$this->login = new Login();
	}

	/**
	 * 用户注册
	 * @param String $phone 手机号
	 * @param String $pwd  密码
	 */
	public function userReg($phone, $pwd){
		$user_data = array(
				'phone'   => $phone,
				'pwd'     => md5($pwd),
				'spacebg' => 'files/spacebg/default.jpg',	// 默认背景图片
				'regtime' => date('Y-m-d H:i:s')
			);
		$uid = $this->user->insert($user_data);
		return $uid;
	}

	/**
	 * 用户通过第三方登陆方式来注册
	 * @param String $openid 第三方提供的用户信息唯一标识，一般是保密的
	 * @param String $name 第三方提供的昵称信息
	 * @param Number $sex 第三方提供用户性别 0 男，1女
	 * @param String $avatar_url 第三方提供用户头像url
	 * @param String $sign 个性签名
	 * @param String $type 第三方类型， 2 微信，3 QQ ，4微博
	 * @return Number $uid 注册成功返回的Uid
	 */
	public function userRegThroughThird($openid, $name,  $sex, $avatar_url ,$sign, $type){
		$wechat_auth_str = $type == LOGIN_TYPE_WECHAT ? md5($openid) : '';
		$qq_auth_str = $type == LOGIN_TYPE_QQ ? md5($openid) : '';
		$weibo_auth_str = $type == LOGIN_TYPE_WEIBO ? md5($openid) : '';

		$user_data = array(
				'phone'           => 0,
				'name'            => $name,
				'pwd'             => '',
				'spacebg'         => 'files/spacebg/default.jpg',
				'sex'             => $sex,
				'sign'            => $sign,
				'wechat_auth_str' => $wechat_auth_str,
				'qq_auth_str'     => $qq_auth_str,
				'weibo_auth_str'  => $weibo_auth_str,
				'regtime'         => date('Y-m-d H:i:s')
			);
		$uid = $this->user->insert($user_data);

		// 这个地方图片头像需要想办法处理一下
		// 生成对应的文件夹
		$path = 'files/avatar/'.date('Ymd').'/';
		$ori_path = $path.'ori/';
		$sm_path = $path.'sm/';
		if(!is_dir($path)){
			mkdir($path);
			mkdir($ori_path);
			mkdir($sm_path);
		}

		// 头像的处理

		if(!empty($avatar_url)){
			$filename =  date('YmdHis').rand(0, 9);
			$ori_img_path = $ori_path.$filename.'.jpg';
			$sm_img_path = $sm_path.$filename.'.jpg';
			$round_img_path = $sm_path.$filename.'_round.png';
			$tmp_round_img_path =  $sm_path.$filename.'_round_tmp.jpg';
			try{
				// 原图片的存储
				Log::write('begin to download avatar');
				$img = http_get_data($avatar_url); 
				Log::write('download ok');
				file_put_contents(SKY_ROOT.$ori_img_path,$img);
				Log::write('save ok');
				// 缩略图的存储
				$ic=new ImgCrop(SKY_ROOT.$ori_img_path, SKY_ROOT.$sm_img_path);
				$ic->Crop(200,200,1);
				$ic->SaveImage();
				$ic->destory();

				// round小图的存储，好麻烦
				$ic=new ImgCrop(SKY_ROOT.$ori_img_path, SKY_ROOT.$tmp_round_img_path);
				$ic->Crop(100,100,4);
				$ic->SaveImage();
				$ic->destory();

				$rounder = new RoundCorner(SKY_ROOT.$tmp_round_img_path,50);
				$rounder->round_it(SKY_ROOT.$round_img_path);
				unlink(SKY_ROOT.$tmp_round_img_path);

				$imgs = new Imgs();
				$mid = $imgs->insert(array(
						'uid'         => $uid,
						'ori'         => $ori_img_path,
						'sm'          => $sm_img_path,
						'upload_time' => date('Y-m-d H:i:s')
					));	
				if(!empty($mid)){
					$user = new User();
					$user->update(array('avatar' => $mid),array('uid' => $uid));
				}
			}catch(Exception $e){
				Log::write('get remote avatar ERR');
			}			
		}
		return $uid;	// 注册成功，返回$uid
	}



	/**
	 * 用户信息是否完整，是否需要补全信息，用户名称和用户生日的为空的时候，需要补全信息
	 * @param Number $uid
	 * @return Number 1 需要补全  0 不需要补全
	 */
	public function needCompleteInfo($uid){
		$userinfo = $this->user->get($uid);
		if(empty($userinfo) || empty($userinfo['name']) || $userinfo['birthday'] == '0000-00-00'){
			return 1;
		}
		return 0;
	}

	/**
	 * 用户登录相关的函数
	 * @param Array $loginInfo 登录信息
	 * @param Number $login_type 登录类型 1 手机 2 微信 3 QQ 4 微博
	 */
	public function login($loginInfo, $login_type){
		$phone = $loginInfo['phone'];
		$pwd = $loginInfo['pwd'];
		$openid = $loginInfo['openid'];
		$device = $loginInfo['device'];
		$device_no = $loginInfo['device_no'];
		$device_token = $loginInfo['device_token'];
		$device_type = $loginInfo['device_type'];
		$version = $loginInfo['version'];

		$auth_column = array(
				LOGIN_TYPE_PHONE  => 'pwd',
				LOGIN_TYPE_WECHAT => 'wechat_auth_str',
				LOGIN_TYPE_QQ     => 'qq_auth_str',
				LOGIN_TYPE_WEIBO  => 'weibo_auth_str'
 			);

		$ip = $_SERVER["REMOTE_ADDR"];
		$login_time = date('Y-m-d H:i:s');
		
		if($login_type == LOGIN_TYPE_PHONE){
			$exist_user = $this->user->fetchOne(
				array('phone'=>$phone, 'pwd'=>md5($pwd)), 
				array('uid','phone','enable')
			);
			if(empty($exist_user)){
				return new Msg(-1,'手机号和密码组合错误');
			}
		}else{
			$exist_user = $this->user->fetchOne(
				array($auth_column[$login_type]=>md5($openid)), 
				array('uid','phone','enable')
			);
			if(empty($exist_user)){
				return new Msg(-1,'第三方信息未曾关联过');
			}
		}		

		if(empty($exist_user['enable'])){
			return new Msg(-2,'该账号已被封，请联系管理员');
		}

		$token = base64_encode(security::encode($exist_user['uid'], 3600*10*24));
		$l_id = $this->login->insert(array(
				'uid'          => $exist_user['uid'],
				'phone'        => $phone,
				'ip'           => $ip,
				'token'        => $token,
				'device'       => $device,
				'device_no'    => $device_no,
				'device_token' => $device_token,
				'version'      => $version,
				'login_time'   => $login_time,
				'device_type'  => $device_type,
				'login_type'   => $login_type		
			)
		);
		if(empty($l_id)){
			return new Msg(-3,'登录失败，未知错误');
		}
		return new Msg(0, '登录成功', array(
			'token'=>$token,
			'uid'=> $exist_user['uid'],
			'needCompleteInfo'=>$this->needCompleteInfo($exist_user['uid'])		// 是否需要补全信息，如果是，那么需要跳转到资料填写页面
			)
		);
	}


	/**
	 * 
	 */
	public function updateUser($item, $newValue, $uid){
		$updateData = array(
				$item => $newValue
			);

		$this->update($updateData, "uid={$uid}");
	}

	public function getAvatar($mid){
		$imgs = new Imgs();
		$img = $imgs->get($mid);
		if(!empty($img)){
			$img['sm'] = config::get('img_host').$img['sm'];
			$img['ori'] = config::get('img_host').$img['ori'];
		}
		return $img;
	}

	public function getUserDetailInfo($uid){
		$user = new User();
		if(empty($uid)) return ;
		$user_info = $user->get($uid);
		if(empty($user_info)){
			return;
		}
		//unset($user_info['phone']);
		if(!empty($user_info['avatar'])){
			$imgs = new Imgs();
			$avatar = $imgs->get($user_info['avatar']);
			$user_info['avatar_path'] = config::get('img_host').$avatar['ori'];
			$user_info['avatar_path_sm'] = config::get('img_host').$avatar['sm'];
		}else{
			$user_info['avatar_path'] = '';
			$user_info['avatar_path_sm'] = '';
		}
		$user_info['spacebg'] = config::get('img_host').$user_info['spacebg'];
		$age = '-';
		if($user_info['birthday'] != '0000-00-00'){
			$age = getAge($user_info['birthday']);
		}
		$user_info['age'] = (string)$age;
		$user_info['articleNum'] = $this->getArticleNum($uid);
		$user_info['commentNum'] = $this->getCommentNum($uid);
		return $user_info;
	}

	private function getArticleNum($uid){
		$db = DB::getInstance();
		$sql = "select count(1) as num from t_article where uid={$uid} and enable=1";
		$rst = $db->get_one($sql);
		return $rst['num'];
	}
	private function getCommentNum($uid){
		$db = DB::getInstance();
		$sql = "select count(1) as num from t_comment where uid={$uid} and enable=1";
		$rst = $db->get_one($sql);
		return $rst['num'];
	}

}