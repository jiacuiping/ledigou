<?php 

namespace app\index\controller;

use app\admin\model\Order;
use app\admin\model\OrderItem;
use think\Cookie;
use think\Session;
use think\request;
use app\index\controller\LoginBase;
use app\admin\model\User as UserModel;
use app\admin\model\School;
use app\admin\model\Task;
use app\admin\model\Cash;

/**
 * 前台基础方法（Justin：2019-03-12）
 */

class User extends LoginBase
{
	public $User;
	public $Task;
	public $School;
    private $Cash;

    public function __construct()
	{
		parent::__construct();
		$this->User = new UserModel();
		$this->Task = new Task();
		$this->Cash = new Cash();
		$this->School = new School();
	}

	//个人中心
	public function index()
	{
		return view();
	}

	//收入明细
	public function income($startdate='',$enddate='')
	{
		$date['startdate'] = $startdate == '' ? '开始日期' : $startdate;
		$date['enddate'] = $enddate == '' ? '结束日期' : $enddate;
		$time1 = $startdate != '' ? strtotime($startdate) : 0;
		$time2 = $enddate != '' ? strtotime($enddate) + 86399 : time();

		$data = $this->Task
				-> alias('t')
				-> field('t.task_complete,t.task_price,o.order_sn')
				-> join('__ORDER__ o','t.task_id = o.order_task')
				-> where('t.task_ordersuser',session::get('user.user_id'))
				-> where('t.task_status','>',20)
				-> where('t.task_complete','between',"$time1,$time2")
				-> select();


		// $data = db('order_item')
		// 		-> alias('oi')
		// 		-> field('oi.item_order,SUM(oi.item_commission) as commission,o.order_sn,o.order_paytime')
		// 		-> join('order o','oi.item_order = o.order_id')
		// 		-> where(array('oi.item_head'=>session::get('user.user_id'),'o.order_ispay'=>1))
		// 		-> where('o.order_paytime','between',"$time1,$time2")
		// 		-> group('oi.item_order')
		// 		-> select();

		$this->assign('sum',array_sum(array_map(function($val){return $val['task_price'];}, $data)));
		$this->assign('date',$date);
		$this->assign('data',$data);
		return view();
	}

	//订单列表
	public function order($startdate='',$enddate='')
	{
		$date['startdate'] = $startdate == '' ? '开始日期' : $startdate;
		$date['enddate'] = $enddate == '' ? '结束日期' : $enddate;
		$time1 = $startdate != '' ? strtotime($startdate) : 0;
		$time2 = $enddate != '' ? strtotime($enddate) + 86399 : time();


		$data = $this->Task
				-> alias('t')
				-> field('t.task_complete,t.task_price,o.order_sn')
				-> join('__ORDER__ o','t.task_id = o.order_task')
				-> where('t.task_ordersuser',session::get('user.user_id'))
				-> where('t.task_status','>',20)
				-> where('t.task_complete','between',"$time1,$time2")
				-> select();


		// $data = db('order_item')
		// 		-> alias('oi')
		// 		-> field('oi.item_order,SUM(oi.item_commission) as commission,o.order_sn,o.order_paytime')
		// 		-> join('order o','oi.item_order = o.order_id')
		// 		-> where(array('oi.item_head'=>session::get('user.user_id'),'o.order_ispay'=>1))
		// 		-> where('o.order_paytime','between',"$time1,$time2")
		// 		-> group('oi.item_order')
		// 		-> select();

		$this->assign('sum',array_sum(array_map(function($val){return $val['task_price'];}, $data)));
		$this->assign('date',$date);
		$this->assign('data',$data);
		return view();
	}

    // 提现列表
    public function cash($startdate='',$enddate='')
    {
        $userId = session::get('user.user_id');
        $type = Cookie::get('type');


        $map = [];
        $map['cash_user'] = $userId;
        $map['cash_type'] = $type == 2 ? 0 : 1;

        // 时间筛选
        $startDate = trim($startdate);
        $endDate = trim($enddate);
        $date['startDate'] = $startDate == '' ? '开始日期' : $startDate;
        $date['endDate'] = $endDate == '' ? '结束日期' : $endDate;
        $startTime = ($startDate == '' || $startDate == '开始日期') ? 0 : strtotime($startDate);
        $endTime = ($endDate == '' || $endDate == '结束日期') ? time() : strtotime($endDate);
        $map['cash_time'] = array('between',[$startTime,$endTime]);


        $data = $this->Cash->GetDataList($map);
        foreach ($data as $key => $value) {
            $data[$key]['cash_status_text'] = $value['cash_status'] == 0 ? "未审核" : "审核通过";
        }


        // 提现审核中金额
        $applyMoney = 0;
        // 已提现金额
        $cashMoney = 0;
        foreach ($data as $value) {
            if($value['cash_status'] == 0) {
                $applyMoney += $value['cash_money'];
            } else {
                $cashMoney += $value['cash_money'];
            }
        }


        $this->assign('date',$date);
        $this->assign('data',$data);
        $this->assign('applyMoney',$applyMoney);
        $this->assign('cashMoney',$cashMoney);
        return view();
    }

	// 申请提现页面
    public function apply_cash()
    {
        $userId = session::get('user.user_id');
        $type = Cookie::get('type') ? 0 : 1;

        // 总收入
        $sum = $this->getSumMoney($userId, $type);
        // 提现审核中金额
        $applyMoney = $this->getApplyMoney($userId, $type);
        // 已提现金额
        $cashMoney = $this->getApplyMoney($userId, $type, 1);
        // 可提现金额
        $ableMoney = $sum - $applyMoney - $cashMoney;

        // 视图
        $this->assign('cash_user',$userId);
        $this->assign('cash_type',$type);
        $this->assign('sum',$sum);
        $this->assign('cashMoney',$cashMoney);
        $this->assign('applyMoney',$applyMoney);
        $this->assign('ableMoney',$ableMoney);
        return view();
    }

    /**
     * @param $userId
     * @param $type
     * @return float|int
     */
    public function getSumMoney($userId, $type)
    {
        if($type == 0) {
            // 骑手收入
            $taskMap = [];
            $taskMap['task_ordersuser'] = $userId;
            $taskMap['task_status'] = ['in', '30, 40'];
            $task = new Task;
            $data = $task->GetDataList($taskMap);
            $sum = array_sum(array_column($data, 'task_price'));

        } else {
            // 团长收入

            // 根据团长id获取团长已完成的订单id
            $orderMap = [];
            $orderMap['order_user'] = $userId;
            $orderMap['order_status'] = ['in', '35, 40'];
            $order = new Order;
            $orderIds = $order->GetOrderIds($orderMap);

            // 根据order_id查询佣金
            $orderItem = new OrderItem;
            $itemMap['item_order'] = ['in', $orderIds];
            $itemMap['item_head'] = $userId;
            $data = $orderItem->GetDataList($itemMap);
            $sum = array_sum(array_column($data, 'item_commission'));
        }

        return $sum;
    }

    /**
     * 提现
     * @param $userId
     * @param $type
     * @param $cashStatus 0-审核中 1-审核通过
     * @return mixed
     */
    public function getApplyMoney($userId, $type = 0, $cashStatus = 0)
    {
        $cashMap = [];
        $cashMap['cash_user'] = $userId;
        $cashMap['cash_type'] = $type;
        $cashMap['cash_status'] = $cashStatus;
        $applyMoney = $this->Cash->GetField($cashMap, 'sum(cash_money)');
        return is_null($applyMoney) ? 0 : $applyMoney;

    }

    //提示信息页面
    public function mess()
    {
        $mess = input('param.mess');
        $this->assign('data',$mess);
        return view();
    }
}