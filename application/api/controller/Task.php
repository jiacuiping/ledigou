<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;
use app\api\controller\Pay;

use app\admin\model\User;

/**
 * 接口主页方法
 */

class Task extends Base
{
	private $User;
	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->User = new User();
	}

	//提交跑腿任务
	public function SubmitTask()
	{
		$data = input('post.')['param'];
		$unionid = input('post.')['unionid'];

		//字段验证
		if($data['task_title'] == '') return json_encode(array('code'=>0,'msg'=>'标题不能为空'));
		if($data['task_pickupaddress'] == '') return json_encode(array('code'=>0,'msg'=>'取货地址不能为空'));
		if($data['task_pickupmobile'] == '') return json_encode(array('code'=>0,'msg'=>'取货联系电话不能为空'));
		if($data['task_shippingaddress'] == '') return json_encode(array('code'=>0,'msg'=>'收货地址不能为空'));
		if($data['task_shippinguser'] == '') return json_encode(array('code'=>0,'msg'=>'收货联系人不能为空'));
		if($data['task_shippingmobile'] == '') return json_encode(array('code'=>0,'msg'=>'收货联系电话不能为空'));
		if(!CheckMobile($data['task_pickupmobile']) || !CheckMobile($data['task_shippingmobile'])) return  json_encode(array('code'=>0,'msg'=>'请输入正确的手机号码'));
		if($data['task_price'] == '') return json_encode(array('code'=>0,'msg'=>'您不给点小费吗？'));
		if($data['task_price'] < 1) return json_encode(array('code'=>0,'msg'=>'佣金最少一元'));

		//补全数据
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));
		$data['user'] = $user['user_id'];
		$data['school'] = $user['user_school'];

		$pay = new Pay();

		return json_encode($pay->CreateTask($data));	
	}
}