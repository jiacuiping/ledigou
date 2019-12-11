<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\School as SchoolModel;

/**
 * 接口主页方法
 */

class School extends Base
{
	private $School;

	//构造函数
	public function __construct()
	{
		$this->School = new SchoolModel();

		parent::__construct();
	}

	//获取学校列表
	public function GetSchoolList($where=array())
	{
		$where['school_status'] = 1;

		$data = $this->School->GetDataList($where);

		return json_encode(array('code'=>1,'msg'=>'获取成功','school'=>$data));
	}
}