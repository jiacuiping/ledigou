<?php 

namespace app\index\controller;

use think\Session;
use think\request;
use app\index\controller\LoginBase;
use app\admin\model\Task;
use app\admin\model\User;
use app\admin\model\OrderItem;
use app\admin\model\Order as OrderModel;

/**
 * 前台基础方法（Justin：2019-03-12）
 */

class Order extends LoginBase
{
	public $Task;
	public $User;
	public $Order;
	public $OrderItem;

	public function __construct()
	{
		parent::__construct();
		$this->Task = new Task();
		$this->User = new User();
		$this->Order = new OrderModel();
		$this->OrderItem = new OrderItem();
	}

	//接单大厅
	public function index($type=1)
	{
		$field = 'o.order_sn,t.create_time,t.task_pickupaddress,t.task_shippingaddress,t.task_price,t.task_id';
		$Mwhere = array('o.order_deliverymethod'=>1,'t.task_status'=>10,'t.task_ordersuser'=>0);
		$Twhere = array('o.order_deliverymethod'=>1,'task_status'=>20,'task_ordersuser'=>array('neq',0));
		$Mwhere['o.order_desc'] = $Twhere['o.order_desc'] = $type == 1 ? '商品购买' : '跑腿任务';

		$MissedOrder = $this->Order
				-> alias('o')
				-> field($field)
				-> join("__TASK__ t",'o.order_task = t.task_id')
				-> where($Mwhere)
				-> select();

		$TakeOrders = $this->Order
				-> alias('o')
				-> field($field)
				-> join("__TASK__ t",'o.order_task = t.task_id')
				-> where($Twhere)
				-> select();

		$this->assign('empty','<div class="empty">暂无订单</div>');
		$this->assign('MissedOrder',$MissedOrder);
		$this->assign('TakeOrders',$TakeOrders);
		$this->assign('type',$type);
		return view();
	}

	//个人订单
	public function list($schedule=10)
	{
		$data = $this->Order
				-> alias('o')
				-> field('o.order_sn,t.*')
				-> join("__TASK__ t",'o.order_task = t.task_id')
				-> where(array('task_ordersuser'=>session::get('user.user_id'),'task_schedule'=>$schedule))
				-> select();

		$this->assign('data',$data);
		$this->assign('schedule',$schedule);
		$this->assign('empty','<div class="empty">暂无订单</div>');
		return view();
	}


	//订单详情
	public function info($task_id=0)
	{
		if($task_id == 0) return array('code'=>0,'msg'=>'参数不正确');

		$task = $this->Task->GetOneDataById($task_id);

		$order = $this->Order->GetOneData(array('order_task'=>$task_id));

		$item = $this->OrderItem
				-> alias('o')
				-> field('g.goods_name,o.*')
				-> join('__GOODS__ g','o.item_goods = g.goods_id')
				-> where(array('o.item_order'=>$order['order_id']))
				-> select();

		$this->assign('user',$this->User->GetField(array('user_id'=>$task['task_user']),'user_name'));
		$this->assign('task',$task);
		$this->assign('item',$item);
		$this->assign('order',$order);
		return view();
	}

	//接单
	public function GrabTheOrder($task_id=0)
	{
		if($task_id == 0) return array('code'=>0,'msg'=>'参数不正确');

		$task = $this->Task->GetOneDataById($task_id);

		if(!$task) return array('code'=>0,'msg'=>'任务不存在');

		if($task['task_ordersuser'] != 0) return array('code'=>0,'msg'=>'该订单已被抢，下次手快点哦！');

		if($task['task_status'] == 20) return array('code'=>0,'msg'=>'该订单正在配送');

		if(session::get('user.user_status') != 1) return array('code'=>0,'msg'=>'该账号已被禁用，无法接单');

		if(session::get('user.user_type') != 2 && session::get('user.user_type') != 4) return array('code'=>0,'msg'=>'非骑手无法接单');

		if(session::get('user.user_review') != 1) return array('code'=>0,'msg'=>'您提交的信息暂未审核，无法接单');

		$result = $this->Task->UpdateData(array('task_id'=>$task_id,'task_ordersuser'=>session::get('user.user_id'),'task_status'=>20,'task_schedule'=>10,'task_ordertime'=>time()));

		return $result['code'] == 1 ? array('code'=>1,'msg'=>'抢单成功') : array('code'=>0,'msg'=>'抢单失败');
	}

	//更改订单进度
	public function changeSchedule($task_id=0,$schedule=0)
	{
		if($task_id == 0 || $schedule == 0) return array('code'=>0,'msg'=>'参数不正确');

		$task = $this->Task->GetOneDataById($task_id);

		if($task['task_schedule'] != $schedule) return array('code'=>0,'msg'=>'状态不正确，请刷新重试');

		if($task['task_ordersuser'] != session::get('user.user_id')) return array('code'=>0,'msg'=>'您不是接单用户，无法修改状态');

		//$status = $schedule == 20 ? 30 : 20;	,'task_status'=>$status 用户确认收货后才算已完成

		$changeschedule = $schedule == 30 ? 35 : $schedule + 10;

		$complete = $schedule == 20 ? time() : 0;

		$result = $this->Task->UpdateData(array('task_id'=>$task_id,'task_ordersuser'=>session::get('user.user_id'),'task_schedule'=>$changeschedule,'task_complete'=>$complete));

		return $result['code'] == 1 ? array('code'=>1,'msg'=>'操作成功') : array('code'=>0,'msg'=>'操作失败');
	}

}