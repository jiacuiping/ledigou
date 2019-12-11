<?php 

namespace app\api\controller;

use think\Controller;
use think\Session;
use think\request;
use app\admin\model\Config;

/**
 * 接口基础方法
 */

class Base extends Controller
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
	}
}