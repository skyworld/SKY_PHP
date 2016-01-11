<?php
/**
 * 抽象类，该抽象类继承了BaseController，并且在BaseController的基础之上
 * 增加了商店管理员登陆的校验
 * @author SkyWorld<pgg200@qq.com>
 * @package controller
 * @since 2013-12-21 
 */
abstract class AdminBaseController extends BaseController
{
	public function __construct()
	{
		parent::init();
		Log::write('This is a admin background action, needs login state');
		$this->init();
	}
	
	public function init()
	{
		$shop_manager = new ShopManager();
		// 检查商家是否登陆
		/*if(!$shop_manager->shop_login_state_check())
		{
			$msg = new Msg('-1000','you have not login');
			die($msg);
			// 没有登陆直接跳转到登陆页面
			//header("Location:/shop/enter");
		}
		*/
	}
	
	public function __destruct()
	{
		parent::destory();
	}
}
// end of script