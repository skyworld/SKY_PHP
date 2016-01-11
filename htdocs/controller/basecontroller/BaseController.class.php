<?php
/**
 * 抽象类，Controller类的公用操作可以在这个类当中完成
 * @author SkyWorld<pgg200@qq.com>
 * @package controller
 * @since 2013-12-16 
 */
abstract class BaseController
{
	protected $auto_output = true;
	protected $msg;
	protected $check_login = false;

	public function __construct()
	{
		$this->init();
	}
	
	protected function init()
	{
		/**
		 * 记录脚本运行开始时间
		 */
		$mtime = explode(' ', microtime());
		Registry::set('script_start_time', $mtime[1] + $mtime[0]);
		
		/**
		 * 初始化日志路径
		 */
		$log_name = Registry::get('controller_name').'_'.Registry::get('action_name');
		Log::init($log_name);
		Log::write('begin action');
	}
	
	public function __destruct()
	{
		// 自动输出
		if($this->auto_output){
			echo $this->msg;
		}
		$this->destory();
	}
	
	protected function destory()
	{
		/**
		 * 记录脚本运行结束时间
		 */
		$mtime = explode(' ', microtime());
		Registry::set('script_end_time', $mtime[1] + $mtime[0]);
		Log::write('end action');
		
		// 显示脚本运行信息
		$this->show_run_time();
	}
	
	protected function show_run_time()
	{
		$script_run_time = round(Registry::get('script_end_time') - Registry::get('script_start_time'),3);
		$controller_name = Registry::get('controller_name');
		$action_name = Registry::get('action_name');
		$memory_usage = round(memory_get_usage()/1024/1024,3);
		Log::debug('===================================');
		Log::debug("controller_name:{$controller_name}");
		Log::debug("action_name:{$action_name}");
		Log::debug("script_run_time:{$script_run_time} s");
		Log::debug("memory_usage:{$memory_usage} M");
		Log::debug('===================================');
	}

	/**
	 * baseController里面提供登录校验的机制
	 */
	protected function checkLogin(){
		$token = request::get('token');
		$info = Comm::checkLogin($token);
		if($info === false){

			// 此处再去校验是不是管理员登陆，如果是管理员登陆的话那就可以是god模式
			$uid = request::get('uid');
			if(!empty($uid)){
				$this->checkAdminLogin();
				if(!is_object($this->msg)){
					return $uid;	// 返回前台传过来的UID
				}
			}

			$this->msg =  new Msg(-1,'对不起，请先登录');
			exit();
		}

		if(empty($info['enable'])){
			$this->msg =  new Msg(-2,'该账号已被封，请联系管理员');
			exit();
		}
		return $info['uid'];
	}

	protected function checkAdminLogin($type = 'json'){
		$token = cookie::get('adminLogin');
		$clientStr = security::decode($token);
		$hasLogin = false;
		if(!empty($clientStr) && !empty($token)){
			$realUsername = DConfig::get('adminUserName');
			$realPwd = DConfig::get('adminPwd');
			$str = md5($realUsername.$realPwd);
			if($str == $clientStr){
				$hasLogin = true;
			}
		}
		if(!$hasLogin){
			if($type == 'json'){
				$this->msg =  new Msg(-1,'对不起，请先登录');
				exit();
			}else{
				header("Location:/adminPage/login");
			}
		}
	}
}
// end off script
