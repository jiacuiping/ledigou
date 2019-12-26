<?php 

namespace app\index\controller;

use think\Controller;
use think\Cookie;
use think\Session;
use think\Request;

use app\admin\model\Config;

/**
 * 前台基础方法（Justin：2019-03-12）
 */

class LoginBase extends Controller
{
	public function __construct()
	{
		parent::__construct();

		//检测配置文件
		if(!session::has('config')) 
		{
			$config = new Config();
			session::set('config',$config->GetConfig());
		}

		//检测设备类型
		if(!session::has('isMobile'))
			session::set('ismobile',ismobile() ? true : false);

		//检测登陆信息
        $type = input('param.type');
        $head = input('param.head');
        Cookie::set('type',$type);
        Cookie::set('head',$head);
		if(!session::has('user'))
			$this->redirect('Wechat/Login');
	}
}