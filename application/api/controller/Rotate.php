<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\Rotate as RotateModel;

/**
 * 接口主页方法
 */

class Rotate extends Base
{
	private $Rotate;

	//构造函数
	public function __construct()
	{
		$this->Rotate = new RotateModel();

		parent::__construct();
	}

	//获取轮播图
	public function GetRotateList($address='index',$limit=4)
	{
		$data = $this->Rotate->GetListByPage(array('rotate_address'=>$address),1,$limit);

		foreach ($data as $key => $value) {
			$data[$key]['rotate_img'] = session::get('config.website_indexurl').$value['rotate_img'];
		}

		if(empty($data)) return json_encode(array('code'=>0,'msg'=>'轮播图为空'));

		return json_encode(array('code'=>1,'msg'=>'获取成功','rotate'=>$data));
	}
}