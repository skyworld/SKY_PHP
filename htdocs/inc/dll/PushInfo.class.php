<?php
class PushInfo{
	
	static function insert($content, $title, $to_uid, $send_user, $params=array()){

		$pushInfoModel = new PushInfoModel();

		$data = array(
			'content' => $content,
			'title' => $title,
			'to_user' => $to_uid,
			'send_user' => $send_user,
			'last_update_time' => date('Y-m-d H:i:s'),
			'params' => json_encode($params)
		);

		$id = $pushInfoModel->insert($data);
		if(!empty($id)) return $id;
		return false;
	}

	/**
	 * 批量发送消息
	 * @return [type] [description]
	 */
	static function send($sendId){
		$pushInfoModel = new PushInfoModel();
		$db = DB::getInstance();
		if(!empty($sendId)){
			$andSql = "and a.id={$sendId}";
		}
		$sql = "select b.device_token,b.device_type,b.token, a.params, a.id, a.content from t_push_info a, t_login b where a.to_user = b.uid and b.device_token != '' and a.is_send = 0 {$andSql} and b.token not like 'expired at%' group by device_token";
		$infos = $db->get_all($sql);
		if(count($infos > 0 )){
			foreach ($infos as $info) {
				if($info['device_type']){
					$push = new XingeApp(2200102180,"661fd033916fadbf4e0866a18978782a");
					$mess = new MessageIOS();
					$mess->setAlert($info['content']);
					$params = json_decode($info['params'],true);
					if(!empty($params['content']))$params['content'] = urldecode($params['content']);
					$mess->setCustom($params);
					$raw = $push->PushSingleDevice($info['device_token'], $mess,XingeApp::IOSENV_PROD);
					$ret = json_encode($raw,true);
					if($ret['ret_code'] == 0){
						$sql = "update t_push_info set is_send = 1 where id={$info['id']}";
						$db->query($sql);					
					}
				}else{	
// for android

					$push = new XingeApp(2100117084,"13391a7ec8a47d5e30525a04bd04befa");
                                        $mess = new Message();
                                        $mess->setTitle('合租吧');
                                        $mess->setContent($info['content']);
                                        $params = json_decode($info['params'],true);
                                        if(!empty($params['content']))$params['content'] = urldecode($params['content']);
                                        $mess->setCustom($params);
					$mess->setType(Message::TYPE_NOTIFICATION);
					$mess->setStyle(new Style(0, 1, 1, 0, 0));
					$action = new ClickAction();
					$action->setActionType(ClickAction::TYPE_ACTIVITY);
					$mess->setAction($action);
                                        $raw = $push->PushSingleDevice($info['device_token'],$mess);
					//$raw = XingeApp::PushTokenAndroid(2100117084, "13391a7ec8a47d5e30525a04bd04befa", "标题", "大家好!", $info['device_token']);
                                      var_dump($raw);  
					$ret = json_encode($raw,true);
                                        if($ret['ret_code'] == 0){
                                                $sql = "update t_push_info set is_send = 1 where id={$info['id']}";
                                                $db->query($sql);
                                        }
				}
			}
		}
	}
}
