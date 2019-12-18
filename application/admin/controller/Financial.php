<?php 

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\admin\model\Cash as CashModel;
use app\admin\model\Task as TaskModel;
use app\admin\model\Order as OrderModel;
use app\admin\model\Goods as GoodsModel;
use app\admin\model\School as SchoolModel;
use app\admin\model\OrderItem as OrderItemModel;
use app\admin\model\Wallet;

/**
 * 财务明细

 *	ControllerList
 */

class Financial extends LoginBase
{
    private $User;
    private $Cash;
    private $Task;
    private $Order;
    private $Goods;
    private $School;
    private $OrderItem;

	public function __construct()
	{
		parent::__construct();
        $this->Task = new TaskModel;
        $this->Cash = new CashModel;
        $this->User = new UserModel;
        $this->Order = new OrderModel;
        $this->Goods = new GoodsModel;
        $this->School = new SchoolModel;
        $this->OrderItem = new OrderItemModel;
	}

	//订单明细
	public function order()
	{
        $map = [];

        $condition['sn'] = $condition['type'] = $condition['user'] = '';

        $sn = input('param.sn');
        if($sn && $sn !== ""){
            $condition['sn'] = $sn;
            $map['order_sn'] = ['like',"%" . $sn . "%"];
        }

        $type = input('param.type');
        if($type && $type !== ""){
        	$condition['type'] = $type;
            $map['order_desc'] = $type;
        }

        $time = input('param.time');
        if($time && $time !== ""){
            $condition['time'] = $time;
            $time = explode(' , ',$time);
            $map['order_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        // 分页
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Order->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Order->GetListByPage($map,$Nowpage,$limits);
        $users      = $this->User->GetDataList(array('user_status'=>1));

        foreach ($data as $key => $value) {
            $data[$key]['order_paytime'] = $value['order_paytime'] == 0 ? '未支付' : date('Y-m-d H:i',$value['order_paytime']);
            $data[$key]['order_user'] = $this->User->GetField(array('user_id'=>$value['order_user']),'user_name');
        }

        $summary = [];
        $summary['order_num'] = $count;  // 订单总数
        $orderMoney = $this->Order->GetField(['order_status' => ['in', '10, 20, 30, 35, 40']], 'sum(order_money)'); // 订单总额
        $summary['order_money'] = round($orderMoney, 2); // 订单总额
        $summary['order_type1'] = $this->Order->GetCount(['order_desc' => '跑腿任务']);
        $summary['order_type2'] = $this->Order->GetCount(['order_desc' => '商品购买']);

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition,'users'=>$users]
            );
        }
        $this->assign('users',$users);
        $this->assign('condition',$condition);
        $this->assign('summary',$summary);
        return $this->fetch();
	}

	// 团长收入
    public function head()
    {
        $itemMap = [];
        $condition = [];

        $time = input('param.item_time');
        if($time && $time !== ""){
            $condition['item_time'] = $time;
            $time = explode(' , ',$time);
            $itemMap['item_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        // 获取团长已完成的订单id
        $orderMap = [];
        $orderMap['order_user'] = ['<>', 0];
        $orderMap['order_status'] = ['in', '35, 40'];
        $orderIds = $this->Order->GetOrderIds($orderMap);


        // 根据order_id查询佣金
        $itemMap['item_order'] = ['in', $orderIds];

        // 分页
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->OrderItem->GetCount($itemMap);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->OrderItem->GetListByPage($itemMap,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['order_sn'] = $this->Order->GetField(array('order_id'=>$value['item_order']),'order_sn');
            $data[$key]['order_goods'] = $this->Goods->GetField(array('goods_id'=>$value['item_goods']),'goods_name');
            $data[$key]['order_head'] = $this->User->GetField(array('user_id'=>$value['item_head']),'user_name');
            $data[$key]['item_is_offer'] = $value['item_head'] == 0 ? "否" : "是";
        }

        // 汇总
        $summary = [];
        $summary['money'] = array_sum(array_column($data, 'item_commission'));
        $summary['num'] = $count;

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'condition'=>$condition]
            );
        }


        $this->assign('data',$data);
        $this->assign('summary',$summary);
        return view();
    }

    // 骑手收入
    public function task()
    {
        $map = [];
        $condition = [];

        $time = input('param.time');
        if($time && $time !== ""){
            $condition['time'] = $time;
            $time = explode(' , ',$time);
            $map['create_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        // 分页
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Task->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Task->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['task_user'] = $this->User->GetField(array('user_id'=>$value['task_user']),'user_name');
            $data[$key]['task_ordersuser'] = $this->User->GetField(array('user_id'=>$value['task_ordersuser']),'user_name');
            $data[$key]['task_status'] = $this->Task->getStatusText($value['task_status']);
            $data[$key]['task_schedule'] = $this->Task->getScheduleText($value['task_schedule']);
        }

        // 汇总
        $summary = [];
        $summary['money'] = array_sum(array_column($data, 'task_price'));
        $summary['num'] = $count;

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition]
            );
        }

        $this->assign('data',$data);
        $this->assign('summary',$summary);
        return $this->fetch();
    }

    // 提现申请记录
    public function cashlist()
    {
        $map = [];
        $condition = [];

        $user = input('param.user');
        if($user && $user !== ""){
            $condition['user'] = $user;
            $map['cash_user'] = $user;
        }

        $cashType = input('param.cash_type');
        if($cashType != -1){
            $condition['cash_type'] = $cashType;
            $map['cash_type'] = $cashType;
        }

        $status = input('param.cash_status');
        if($status != -1){
            $condition['cash_status'] = $status;
            $map['cash_status'] = $status;
        }

        $cashTime = input('param.cash_time');
        if($cashTime && $cashTime !== ""){
            $condition['cash_time'] = $cashTime;
            $time = explode(' , ',$cashTime);
            $map['cash_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Cash->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Cash->GetListByPage($map,$Nowpage,$limits);
        $users      = $this->User->GetDataList(array('user_status'=>1));

        foreach ($data as $key => $value) {
            if ($value['cash_status'] == 0) {
                $data[$key]['check'] = "<button type='button' data-id=".$value['cash_id']." data-type='cash' class='layui-btn layui-btn-normal check'>通过审核</button>";
            } else {
                $data[$key]['check'] = "已通过审核";
            }
            $data[$key]['cash_status'] = $value['cash_status'] == 0 ? '未审核' : '审核通过';
            $data[$key]['cash_type'] = $value['cash_type'] == 0 ? '骑手' : '团长';
            $data[$key]['cash_pass_time'] = $value['cash_pass_time'] == 0 ? '--' : date('Y-m-d H:i',$value['cash_pass_time']);
            $data[$key]['cash_user'] = $this->User->GetField(array('user_id'=>$value['cash_user']),'user_name');

        }

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition,'users'=>$users]
            );
        }
        $this->assign('users',$users);
        $this->assign('condition',$condition);
        return $this->fetch();
    }

    // 修改审核状态
    public function changeStatus()
    {
        // 判断数据是否存在
        $cashId = input('post.cash_id');
        $cash = $this->Cash->GetOneDataById($cashId);

        if(!$cash) return ['code'=>0,'msg'=>'申请不存在'];

        $cashId = $cash['cash_id'];
        $cashMoney = $cash['cash_money'];
        $userId = $cash['cash_user'];

        // 查看用户钱包是否正常使用
        $wallet = new Wallet();
        $userWallet = $wallet->GetOneDataByUserId($userId);
        if(!$userWallet) {
            return ['code'=>0,'msg'=>'用户钱包已冻结'];
        }

        // 修改状态
        $update = array('cash_id'=>$cashId,'cash_status'=>1,'cash_pass_time'=>time());
        $cashRes = $this->Cash->UpdateData($update);
        if($cashRes['code'] == 0) {
            return ['code'=>0,'msg'=>'审核失败'];
        }

        // 提现到钱包
        $walletRes = $wallet->DataSetInc(['wallet_user' => $userId], 'wallet_money', $cashMoney);
        if($walletRes['code'] == 0) {
            return ['code'=>0,'msg'=>'存入钱包失败'];
        }

        return ['code'=>0,'msg'=>'修改成功'];
        /*$res = $this->Cash->passCheck($cash);
        if($res['code']) {
            return ['code'=>0,'msg'=>'修改成功'];
        } else {
            return ['code'=>1,'msg'=>'修改失败'];
        }*/
    }
}