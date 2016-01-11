<?php

/**
 * 全局配置文件
 * @author SkyWorld<pgg200@qq.com>
 * @since v3.0 2014-03-23
 */

return array(
	# 默认的时区配置
	'timezome'           => 'Asia/Shanghai',
	
	# header 输出的编码类型
	'charset'            => 'utf-8',
	
	# 文本日志的路径
	'log_path'           => '../app_logs',
	
	# 加密解密的密钥
	'key'                => 'sky',
	
	# 是否为debug模式，如果debug模式，将会写文本log
	'debug'              => true,
	
	# 默认的controller和默认的action
	'default_controller' => 'index',
	'default_action'     => 'index',
	
	# 短信接口相关配置
	'msg_userid'         => '4444',
	'msg_account'        => 'Wangju520',
	'msg_pwd'            => 'Wangju520',
	'msg_sign'           => '合租吧',
	
	#redis URL配置和端口配置
	'redis_server_url'   => '127.0.0.1',
	'redis_server_port'  => 6379,
	
	#融云的相关的配置
	'rong_app_key'       => 'tdrvipksrg6u5',
	'rong_app_secret'    => 'NSTfLTnQpvT7',
	
	#domain
	'host'               => 'hezu.dev.shnow.cn',
	#img host
	'img_host'           => 'http://hezu.dev.shnow.cn/'
);
