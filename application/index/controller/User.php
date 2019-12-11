<?php 

namespace app\index\controller;

use think\Session;
use think\request;
use app\index\controller\LoginBase;
use app\admin\model\User as UserModel;
use app\admin\model\School;
use app\admin\model\Task;

/**
 * 前台基础方法（Justin：2019-03-12）
 */

class User extends LoginBase
{
	public $User;
	public $Task;
	public $School;

	public function __construct()
	{
		parent::__construct();
		$this->User = new UserModel();
		$this->Task = new Task();
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
}