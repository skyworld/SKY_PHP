<?php
/**
 * 所有相关的map配置文件
 */
class Map{

	/**
	 * 城市列表
	 * @var array
	 */
	public static $city = array(
		'core' => array(
			array('city'=>'上海', 'lat'=>'31.262992', 'lng'=>'121.487899'),
			array('city'=>'北京', 'lat'=>'39.923743', 'lng'=>'116.403874'),
			array('city'=>'广州', 'lat'=>'23.133875', 'lng'=>'113.30535'),
			array('city'=>'深圳', 'lat'=>'22.558871', 'lng'=>'114.025974')
		),	
		'important' => array(
			array('city'=>'杭州', 'lat'=>'30.278208', 'lng'=>'120.220525'),
			array('city'=>'武汉', 'lat'=>'30.593022', 'lng'=>'114.3139'),
			array('city'=>'青岛', 'lat'=>'36.124808', 'lng'=>'120.384428'),
			array('city'=>'厦门', 'lat'=>'24.506067', 'lng'=>'118.111935'),
			array('city'=>'南京', 'lat'=>'32.072903', 'lng'=>'118.772325'),
			array('city'=>'重庆', 'lat'=>'29.557676', 'lng'=>'106.529485'),
			array('city'=>'合肥', 'lat'=>'31.888529', 'lng'=>'117.286148'),
			array('city'=>'大连', 'lat'=>'38.963075', 'lng'=>'121.591178'),
			array('city'=>'福州', 'lat'=>'26.061666', 'lng'=>'119.33712'),
			array('city'=>'天津', 'lat'=>'39.159151', 'lng'=>'117.208513'),
			array('city'=>'成都', 'lat'=>'30.692862', 'lng'=>'104.070223'),
			array('city'=>'宁波', 'lat'=>'29.902292', 'lng'=>'121.581306'),
			array('city'=>'长沙', 'lat'=>'28.229774', 'lng'=>'112.981653'),
			array('city'=>'西安', 'lat'=>'34.295934', 'lng'=>'108.951948')
		)
	);

	
	public static $cityPyMap = array(
			'shanghai' => '上海',
			'beijing' => '北京',
			'guangzhou' => '广州',
			'shenzhen' =>'深圳',
			'hangzhou' => '杭州',
			'wuhan' => '武汉',
			'qingdao' => '青岛',
			'xiamen' => '厦门',
			'nanjing' => '南京',
			'chongqing' => '重庆',
			'hefei' => '合肥',
			'dalian' => '大连',
			'fuzhou' => '福州',
			'tianjin' => '天津',
			'chengdu' => '成都',
			'ningbo' => '宁波',
			'changsha' => '长沙',
			'xian' => '西安'
		); 

	/**
	 * 职业列表
	 */
	public static $job = array(
		'高新科技' => array('互联网', '电子商务', '电子游戏','计算机软件','计算机硬件'),
		'信息传媒' => array('出版业','电影录音','广播电视','通信'),
		'金融' => array('银行','资本投资','证券投资','保险','信贷','财务','审计'),
		'服务业' => array('法律','餐饮','酒店','旅游','广告','公关','景观','咨询分析','市场推广','人力资源','社工服务','养老服务')
	);
}