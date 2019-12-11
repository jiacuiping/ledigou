<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\User;
use app\admin\model\Warehouse;
use app\admin\model\Limited as LimitedModel;

/**
 * 接口主页方法
 */

class Limited extends Base
{
	private $User;
	private $Limited;
	private $Warehouse;

	//构造函数
	public function __construct()
	{
		$this->User = new User();
		$this->Warehouse = new Warehouse();
		$this->Limited = new LimitedModel();

		parent::__construct();
	}

	//获取分类列表
	public function GetLimitedList($unionid,$limit=2)
	{
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));

		if(!$user) return json_encode(array('code'=>0,'msg'=>'用户信息不存在'));

		//if($user['user_school'] == '') return json_encode(array('code'=>0,'msg'=>'您还未填写学校信息'));

		$ware = $this->Warehouse->GetColumn(array('ware_school'=>$user['user_school']),'ware_id');

		$where['g.goods_warehouse'] = array('in',implode(',',$ware));
		$where['l.limited_stime'] = array('<',time());
		$where['l.limited_etime'] = array('>',time());

		$data = $this->Limited
				-> alias('l')
				-> join('__GOODS__ g','l.limited_goods = g.goods_id')
				-> where($where)
				-> limit(2)
				-> order('limited_time desc')
				-> select();

		//dump($this->Limited->getLastSql());die;


		foreach ($data as $key => $value) {
			$data[$key]['goods_image'] = session::get('config.website_indexurl').$value['goods_image'];
		}

		if(empty($data)) return json_encode(array('code'=>0,'msg'=>'当前暂无秒杀商品'));

		return json_encode(array('code'=>1,'msg'=>'获取成功','limited'=>$data));
	}
}