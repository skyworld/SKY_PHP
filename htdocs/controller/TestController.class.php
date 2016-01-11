<?php



class TestController extends BaseController{

	/**
	 * [pushAction description]
	 * @return [type] [description]
	 */
	public function pushAction(){
		//var_dump(XingeApp::PushTokenIos(2200102180, "661fd033916fadbf4e0866a18978782a", "你好啊，我是彭庚庚", "334b890e1718a3616b306e7f7b8202365f04a86bd6d43dd586474f9eb5be7061",
		// XingeApp::IOSENV_DEV));
		//echo '123';
		
		$push = new XingeApp(2200102180,"661fd033916fadbf4e0866a18978782a");
		$mess = new MessageIOS();
		$mess->setAlert('你好嘛，我是庚庚');
		$mess->setCustom(array('aid'=>'3'));
		$ret = $push->PushSingleDevice('334b890e1718a3616b306e7f7b8202365f04a86bd6d43dd586474f9eb5be7061', $mess,XingeApp::IOSENV_DEV);
		var_dump($ret);

	}


	public function testAction(){
		$p = new Rong();
		$r = $p->getToken('11','22','33');
		print_r($r);
	}

	//public function 
}
