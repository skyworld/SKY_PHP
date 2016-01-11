<?php
/**
 * 数据库配置文件
 * @author skyworld<pgg200@qq.com>
 * @package config
 */
return array(
	'db1' => array(
		'host'       => 'localhost',
		'user'       => 'root',
		'password'   => '',
		'dbname'     => 'test',
		'port'       => 3306,
		'charset'    => 'utf8',
		'is_default' => true
	),
	'db2' => array(
		'host'       => 'localhost',
		'user'       => 'root',
		'password'   => '',
		'dbname'     => 'wx',
		'port'       => 3306,
		'charset'    => 'utf8',		
		'is_default' => false
	)
);
