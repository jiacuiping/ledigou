<?php 

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\admin\model\Task as TaskModel;
use app\admin\model\Order as OrderModel;
use app\admin\model\School as SchoolModel;
use app\admin\model\OrderItem as OrderItemModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Order extends LoginBase
{
    private $User;
    private $Task;
    private $Order;
    private $School;
    private $OrderItem;

	public function __construct()
	{
		parent::__construct();
        $this->Task = new TaskModel;
        $this->User = new UserModel;
        $this->Order = new OrderModel;
        $this->School = new SchoolModel;
        $this->OrderItem = new OrderItemModel;
	}

	//订单列表
	public function index()
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

        $user = input('param.user');
        if($user && $user !== ""){
            $condition['user'] = $user;
            $map['order_user'] = $user;
        }

        $ispay = input('param.ispay');
        if($ispay && $ispay !== ""){
            $condition['ispay'] = $ispay;
            $map['order_ispay'] = $ispay;
        }

        $time = input('param.time');
        if($time && $time !== ""){
            $condition['time'] = $time;
            $time = explode(' , ',$time);
            $map['order_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

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

    //退款订单
    public function refund()
    {
        $map = [];
        $map['order_refund'] = ['<>', 0];

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

        $user = input('param.user');
        if($user && $user !== ""){
            $condition['user'] = $user;
            $map['order_user'] = $user;
        }

        $time = input('param.time');
        if($time && $time !== ""){
            $condition['time'] = $time;
            $time = explode(' , ',$time);
            $map['order_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Order->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Order->GetListByPage($map,$Nowpage,$limits);
        $users      = $this->User->GetDataList(array('user_status'=>1));

        foreach ($data as $key => $value) {
            $data[$key]['order_refund'] = $this->Order->getRefundText($value['order_refund']);
            $data[$key]['order_user'] = $this->User->GetField(array('user_id'=>$value['order_user']),'user_name');
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

    // 修改退款审核进度
    public function refundAudit()
    {
        // 判断订单是否存在
        $orderId = input('post.order_id');
        $orderRefund = input('post.order_refund');
        $order = $this->Order->GetOneDataById($orderId);

        if(!$order) return ['code'=>0,'msg'=>'订单不存在'];

        $res = $this->Order->updateOrderStatus($orderId, $orderRefund, $order);
        if($res) {
            return ['code'=>0,'msg'=>'修改成功'];
        } else {
            return ['code'=>1,'msg'=>'修改失败'];
        }
    }

    //查看订单
    public function update($id = 0)
    {
        $order = $this->Order->GetOneDataById($id);

        if($order['order_desc'] == '商品购买'){
            $otherinfo = $this->OrderItem
                 -> alias('oi')
                 -> field('oi.*,g.goods_name')
                 -> join('goods g','oi.item_goods = g.goods_id')
                 -> where('oi.item_order',$id)
                 -> select();
        }else{
            $otherinfo = $this->Task->GetOneDataById($order['order_task']);

            $otherinfo['task_username'] = $this->User->GetField(array('user_id'=>$otherinfo['task_ordersuser']),'user_name');
            $otherinfo['task_school'] = $this->School->GetField(array('school_id'=>$otherinfo['task_school']),'school_name');
        }

        $order['order_user'] = $this->User->GetField(array('user_id'=>$order['order_user']),'user_name');

        $this->assign('data',$this->TransformationData($order));
        $this->assign('otherinfo',$otherinfo);
        return view();
    }

    //转换数据
    public function TransformationData($data)
    {

        switch ($data['order_schedule']) {
            case 10:
                $data['order_schedule'] = '已支付';
                break;
            case 20:
                $data['order_schedule'] = '已出库';
                break;
            case 30:
                $data['order_schedule'] = '已到驿站';
                break;
            default:
                $data['order_schedule'] = '未支付';
                break;
        }

        switch ($data['order_status']) {
            case 10:
                $data['order_status'] = '已支付';
                break;
            case 20:
                $data['order_status'] = '配送中';
                break;
            case 30:
                $data['order_status'] = '已送达';
                break;
            case 35:
                $data['order_status'] = '已完成';
                break;
            case 40:
                $data['order_status'] = '已评价';
                break;
            case 50:
                $data['order_status'] = '退款';
                break;
            default:
                $data['order_status'] = '已下单';
                break;
        }

        switch ($data['order_refund']) {
            case 10:
                $data['order_refund'] = '已申请';
                break;
            case 20:
                $data['order_refund'] = '审核中';
                break;
            case 30:
                $data['order_refund'] = '通过审核';
                break;
            case -1:
                $data['order_refund'] = '拒绝退款';
                break;
            default:
                $data['order_refund'] = '未申请';
                break;
        }

        return $data;

    }

    //删除数据
    public function delete($id)
    {
        return $this->Order->DeleteData($id);
    }
}