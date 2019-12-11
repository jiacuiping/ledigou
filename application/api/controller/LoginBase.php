<?php 

namespace app\api\controller;

use think\Controller;
use think\Session;
use think\request;
use app\admin\model\Config;

/**
 * 接口基础方法
 */

class LoginBase extends Controller
{
	//构造函数
	public function __construct()
	{
		parent::__construct();

		//检测配置文件
		if(!session::has('config')) 
		{
			$config = new Config();
			session::set('config',$config->GetConfig());
		}

		//检测是否授权登陆
		if(!session::has('user'))
			$this->redirect('WechatLogin/Login');
	}
}

