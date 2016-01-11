<?php
/**
 * 简单的消息体，供前后台传数据用
 * @author SkyWorld<pgg200@qq.com>
 * @package lib
 * @since 2013-12-18
 */

class Msg
{
	/**
	 * 消息状态码，一般0表示成功
	 */
	protected $code = 0;
	
	/**
	 * 消息的描述
	 */
	protected $msg = '';
	
	
	/**
	 * 消息中返回的数据
	 */
	protected $data = array();
	
	/**
	 * 构造函数
	 */
	public function __construct()
	{
		$arg_num = func_num_args();
		$args = func_get_args();
		if($arg_num == 1)
		{
			$this->success_msg($args[0]);
		}
		if($arg_num == 2)
		{
			$this->msg_without_data($args[0], $args[1]);
		}
		if($arg_num == 3)
		{
			$this->detial_msg($args[0], $args[1], $args[2]);
		}
	}
	
	private function success_msg($data)
	{
		$this->msg = 'success';
		$this->code = 0;
		$this->data = $data;
	}
	
	private function msg_without_data($code, $msg)
	{
		$this->code = $code;
		$this->msg = $msg;
	}
	
	private function detial_msg($code,$msg, $data)
	{
		$this->code = $code;
		$this->msg = $msg;
		$this->data = $data;
	}	
	
	public function __toString()
	{
		$ret = array(
			'code' => $this->code,
			'msg'  => $this->msg,
			'data' => $this->data
		);

		//if(empty($ret['data'])) unset($ret['data']);
		return json_encode($ret);
	}


	public function getCode(){
		return $this->code;
	}

	public function getMsg(){
		return $this->msg;
	}

	public function getData(){
		return $this->data;
	}
}
// end off script
