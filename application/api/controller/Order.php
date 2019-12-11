<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\User;
use app\admin\model\Task;
use app\admin\model\Goods;
use app\admin\model\Wallet;
use app\admin\model\Address;
use app\admin\model\OrderItem;
use app\admin\model\Warehouse;
use app\admin\model\Order as OrderModel;

/**
 * 接口主页方法
 */

class Order extends Base
{
	private $User;
	private $Task;
	private $Goods;
	private $Order;
	private $Wallet;
	private $Address;
	private $Warehouse;
	private $OrderItem;

	//构造函数
	public function __construct()
	{
		$this->User = new User();
		$this->Task = new Task();
		$this->Goods = new Goods();
		$this->Wallet = new Wallet();
		$this->Address = new Address();
		$this->Order = new OrderModel();
		$this->Warehouse = new Warehouse();
		$this->OrderItem = new OrderItem();

		parent::__construct();
	}

	//获取订单列表
	public function GetOrderList($unionid,$type='goods',$status=-1,$page=1,$limit=10)
	{
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));

		if(!$user) return json_encode(array('code'=>0,'msg'=>'用户信息不存在'));

		$day = session::get('config.website_autoconfirm');

		//$this->Order->where(array('order_user'=>$user['user_id'],'order_time'=>array('LT',strtotime("- ".$day."day"))))->update(['order_status'=>35]);

		if($type == 'goods'){
			$where['order_desc'] = '商品购买';
			$where['order_user'] = $user['user_id'];
			$where['order_ishide'] = 0;
			if($status != -1)
				$where['order_status'] = array('in',$status);

			$order = $this->Order->GetListByPage($where,$page,$limit);

			foreach ($order as $key => $value) {
				$order[$key]['goods'] = $this->GetGoodsInfo($value['order_id']);
				$order[$key]['address'] = $this->GetAddressInfo($value,$unionid);
				$order[$key]['hitime'] = substr($value['order_time'],10,6);
				$order[$key] = $this->CodeConversionText($value);
			}
		}else{
			$where['o.order_desc'] = '跑腿任务';
			$where['o.order_user'] = $user['user_id'];
			$where['o.order_task'] = array('neq',0);
			$where['o.order_ishide'] = 0;
			if($status != -1)
				$where['t.task_status'] = array('in',$status);
			$order = $this->Order
						-> alias('o')
						-> join('__TASK__ t','o.order_task = t.task_id')
						-> where($where)
						-> select();

			foreach ($order as $key => $value) {
				$order[$key]['task'] = $this->Task->GetOneData(array('task_id'=>$value['order_task']));
				$order[$key]['task'] = $this->CodeConversionText($order[$key]['task'],'task');
				$order[$key]['hitime'] = substr($value['order_time'],10,5);
				$order[$key] = $this->CodeConversionText($value);
			}
		}

		return json_encode(array('code'=>1,'msg'=>'获取成功','order'=>$order));
	}


	//订单结算
	public function GoSettlement($order_sn='')
	{
		if($order_sn == '') return json_encode(array('code'=>0,'msg'=>'参数错误'));

		$info = $this->Order->GetOneData(array('order_sn'=>$order_sn));

		$info['goods'] = $this->OrderItem
							-> alias('oi')
							-> field('oi.item_order,oi.item_number,oi.item_is_offer,oi.item_money,g.goods_name,g.goods_image,g.goods_price,g.goods_spec,l.limited_money')
							-> join('__GOODS__ g','oi.item_goods = g.goods_id','left')
							-> join('__LIMITED__ l','oi.item_is_offer = l.limited_id','left')
							-> where('oi.item_order',$info['order_id'])
							-> select();

		$address = $this->Address->GetOneData(array('address_user'=>$info['order_user'],'address_default'=>1));

		$countNumber = array_sum(array_map(function($val){return $val['item_number'];}, $info['goods']));

		return json_encode(array('code'=>1,'msg'=>'获取成功','info'=>$info,'number'=>$countNumber,'address'=>$address,'freight'=>session::get('config.website_freight')));
	}

	//订单详情
	public function GetOrderInfo($order_id=0,$unionid='')
	{
		if($order_id == 0) return json_encode(array('code'=>0,'msg'=>'参数不正确'));

		$order = $this->Order->GetOneData(array('order_id'=>$order_id));
		if($order['order_desc'] == '商品购买'){
			$order['goods'] = $this->GetGoodsInfo($order['order_id']);
			$order['address'] = $this->GetAddressInfo($order,$unionid);
			$order = $this->CodeConversionText($order);
		}else{
			$order['task'] = $this->Task->GetOneData(array('task_id'=>$order['order_task']));
			if($order['task']['task_ordertime'] != 0)
				$order['task']['task_ordertime'] = date('Y-m-d H:i:s',$order['task']['task_ordertime']);
			$order['task'] = $this->CodeConversionText($order['task'],'task');
			if($order['task']['task_ordersuser'] != 0)
				$order['user'] = $this->User->GetOneData(array('user_id'=>$order['task']['task_ordersuser']));
			$order = $this->CodeConversionText($order);
		}
		$order['user'] = $this->User->GetOneData(array('user_unionid'=>$unionid));

		return json_encode(array('code'=>1,'msg'=>'获取成功','data'=>$order));
	}


	//确认收货 跑腿订单支付佣金 分享商品支付提成
	public function ConfirmReceipt($order_id)
	{
		$order = $this->Order->GetOneDataById($order_id);

		if(!$order) return json_encode(array('code'=>0,'msg'=>'订单不存在'));

		$this->Order->Update(array('order_id'=>$order_id,'order_status'=>35));

		if ($order['order_desc'] == '商品购买') {
			//如果是团长分享则分配提成
			if($order['order_is_share'] != 0){

				$sumprice = $this->OrderItem->where('item_order',$order_id)->sum('item_commission');

				if($sumprice > 0)
					$this->Wallet->DataSetInc(array('wallet_user'=>$order['order_is_share']),'wallet_money',$sumprice);
			}
		}else if($order['order_desc'] == '跑腿任务'){

			$task = $this->Task->GetOneDataById($order['order_task']);

			$this->Task->UpdateData(array('task_id'=>$task['task_id'],'task_status'=>30,'task_schedule'=>35));

			$this->Wallet->DataSetInc(array('wallet_user'=>$task['task_ordersuser']),'wallet_money',$task['task_price']);
		}

		return json_encode(array('code'=>1,'msg'=>'确认收货成功'));
	}

	//获取订单商品信息
	public function GetGoodsInfo($order_id)
	{
		return  $this->OrderItem
				-> alias('oi')
				-> join('__GOODS__ g','oi.item_goods = g.goods_id')
				-> where('oi.item_order',$order_id)
				-> select();
	}

	//获取订单任务信息
	public function GetTaskInfo($order_id)
	{
		return $this->Task->GetOneData(array('task_id'=>$value['order_task']));
	}

	//获取学校信息
	public function GetAddressInfo($data,$unionid)
	{
		return array(
			'outset'	=> $this->Warehouse->GetField(array('ware_id'=>$this->Goods->GetField(array('goods_id'=>$this->OrderItem->GetField(array('item_order'=>$data['order_id']),'item_goods')),'goods_warehouse')),'ware_address'),
			'finish'	=> $this->Address->GetField(array('address_id'=>$data['order_address']),'address_info')
		);
	}


	//取消订单
	public function cancelOrder($order_id)
	{
		$order = $this->Order->GetOneDataById($order_id);

		if(!$order) return array('code'=>0,'msg'=>'订单不存在');

		return json_encode($this->Order->UpdateData(array('order_id'=>$order_id,'order_ishide'=>1)));

	}

	//状态码转文字
	public function CodeConversionText($data,$type="order")
	{
		if($type == 'order'){
			switch ($data['order_schedule']) {
				case 0:
					$data['order_scheduleText'] = '未支付';
					break;
				case 10:
					$data['order_scheduleText'] = '已支付';
					break;
				case 20:
					$data['order_scheduleText'] = '已出库';
					break;
				case 30:
					$data['order_scheduleText'] = '已到驿站';
					break;
			}

			switch ($data['order_status']) {
				case 0:
					$data['order_statusText'] = '待付款';
					break;
				case 10:
					$data['order_statusText'] = '已支付';
					break;
				case 20:
					$data['order_statusText'] = '配送中';
					break;
				case 30:
					$data['order_statusText'] = '已送达';
					break;
				case 35:
					$data['order_statusText'] = '已完成';
					break;
				case 40:
					$data['order_statusText'] = '已评价';
					break;
				case 50:
					$data['order_statusText'] = '退款';
					break;
			}

			switch ($data['order_refund']) {
				case 0:
					$data['order_refundText'] = '未申请';
					break;
				case 10:
					$data['order_refundText'] = '已申请';
					break;
				case 20:
					$data['order_refundText'] = '审核中';
					break;
				case 30:
					$data['order_refundText'] = '通过审核';
					break;
				case -1:
					$data['order_refundText'] = '拒绝退款';
					break;
			}
		}else{

			switch ($data['task_status']) {
				case 0:
					$data['task_statusText'] = '待付款';
					break;
				case 10:
					$data['task_statusText'] = '已支付';
					break;
				case 20:
					$data['task_statusText'] = '进行中';
					break;
				case 30:
					$data['task_statusText'] = '已完成';
					break;
				case 40:
					$data['task_statusText'] = '已评价';
					break;
			}

			switch ($data['task_schedule']) {
				case 0:
					$data['task_scheduleText'] = '未接单';
					break;
				case 10:
					$data['task_scheduleText'] = '待取货';
					break;
				case 20:
					$data['task_scheduleText'] = '配送中';
					break;
				case 30:
					$data['task_scheduleText'] = '已送达';
					break;
				case 40:
					$data['task_scheduleText'] = '已评价';
					break;
			}
		}

		return $data;
	}
}