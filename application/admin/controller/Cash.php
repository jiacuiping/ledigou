<?php 

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\admin\model\Task as TaskModel;
use app\admin\model\Order as OrderModel;
use app\admin\model\School as SchoolModel;
use app\admin\model\OrderItem as OrderItemModel;

class Cash extends LoginBase
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

	//提现申请列表
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

}