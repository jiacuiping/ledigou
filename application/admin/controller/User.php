<?php 

namespace app\admin\controller;

use think\Config;
use app\admin\model\Task;
use app\admin\model\Order;
use app\admin\model\Wallet;
use app\admin\model\School;
use app\admin\model\Address;
use app\admin\model\OrderItem;
use app\admin\model\GoodsShare;
use app\admin\model\User as UserModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class User extends LoginBase
{
    private $User;
    private $School;
    private $Address;

	public function __construct()
	{
		parent::__construct();
        $this->School = new School;
        $this->User = new UserModel;
        $this->Address = new Address;
	}


    public function UserEntrance()
    {
        $this->redirect('index', ['type' => 1]);
    }


    public function RiderEntrance()
    {
        $this->redirect('index', ['type' => 2]);
    }

    public function HeadEntrance()
    {
        $this->redirect('index', ['type' => 3]);
    }

	//用户列表
	public function index($type)
	{
        $map['user_type'] = $type == 1 ? 1 : array('in',[(int)$type,4]);

        $condition['name'] = $condition['mobile'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['user_name'] = ['like',"%" . $name . "%"];
        }

        $mobile = input('param.mobile');
        if($mobile && $mobile != ''){
        	$condition['mobile'] = $mobile;
            $map['user_mobile'] = ['like',"%" . $mobile . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->User->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->User->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {

            if($type != 1){
                if($value['user_review'] == 0)
                    $data[$key]['user_review'] = "<button type='button' class='layui-btn layui-btn-normal review' data-id=".$value['user_id'].">未审核</button>";
                else
                    $data[$key]['user_review'] = $value['user_review'] == 1 ? "<button type='button' class='layui-btn layui-btn-normal'>审核通过</button>" : "<button type='button' class='layui-btn layui-btn-danger'>审核未通过</button>";
            }

            $data[$key]['user_school'] = $this->School->GetField(array('school_id'=>$value['user_school']),'school_name');
            $data[$key]['user_avatar'] = "<a target='_blank' href=".$value['user_avatar']."><img src=".$value['user_avatar']." style='height:28px'></a>";
            $data[$key]['PurchaseRecord'] = "<button type='button' data-id=".$value['user_id']." class='layui-btn layui-btn-normal showrecord'>点击查看</button>";
            $data[$key]['OrderRecord'] = "<button type='button' data-id=".$value['user_id']." data-type='order' class='layui-btn layui-btn-normal showother'>点击查看</button>";
            $data[$key]['ShoreRecord'] = "<button type='button' data-id=".$value['user_id']." data-type='share' class='layui-btn layui-btn-normal showother'>点击查看</button>";
            $data[$key]['CommissionRecord'] = "<button type='button' data-id=".$value['user_id']." data-type='commission' class='layui-btn layui-btn-normal commissionrecord'>点击查看</button>";
            $data[$key]['PriceRecord'] = "<button type='button' data-id=".$value['user_id']." data-type='price' class='layui-btn layui-btn-normal pricerecord'>点击查看</button>";
            $data[$key]['user_parent'] = $value['user_parent'] == 0 ? '平台流量' : $this->User->GetField(array('user_id'=>$value['user_parent']),'user_name');
        }

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition,'type'=>$type]
            );
        }
        $this->assign('condition',$condition);
        $this->assign('type',$type);
        return $this->fetch();	
	}

    //添加数据
    public function create()
    {
        $this->assign('province',db('area')->where('pid',0)->select());
        return request()->isPost() ? $this->User->CreateData(input('post.')) : view();
    }

    //审核用户
    public function review()
    {
        $id = input('post.id');

        $status = input('post.status');

        $user = $this->User->GetOneDataById($id);

        if(!$user) return array('code'=>0,'msg'=>'用户不存在');

        if($user['user_type'] == 1) return array('code'=>0,'msg'=>'用户不需要审核');

        if($user['user_review'] != 0) return array('code'=>0,'msg'=>'用户已审核');

        return $this->User->UpdateData(array('user_id'=>$id,'user_review'=>$status));
    }

    //查看用户
    public function show($id)
    {
        $data = $this->User->GetOneDataById($id);

        $data['user_parent'] = $data['user_parent'] == 0 ? '平台流量' : $this->User->GetField(array('user_id'=>$data['user_parent']),'user_name');

        $address = $this->Address->GetDataList(array('address_user'=>$id));

        if($address){
            foreach ($address as $key => $value) {
                $address[$key]['address_province'] = db('area')->where('id',$value['address_province'])->value('name');
                $address[$key]['address_city'] = db('area')->where('id',$value['address_city'])->value('name');
                $address[$key]['address_area'] = db('area')->where('id',$value['address_area'])->value('name');
                $address[$key]['address_school'] = $this->School->GetField(array('school_id'=>$value['address_school']),'school_name');
            }
        }

        $rider = $head = array();

        if($data['user_type'] == 2 || $data['user_type'] == 4){

            $Task = new Task;
            $Wallet = new Wallet;

            $where = array('task_ordersuser'=>$data['user_id'],'task_status'=>array('GT',29),'task_schedule'=>array('GT',34));

            $rider = array(
                'rider_ordercount'      => $Task->GetCount(array('task_ordersuser'=>$data['user_id'])),
                'rider_ordercomplete'   => $Task->GetCount($where),
                'rider_moneycount'      => $Task->where($where)->sum('task_price'),
                'rider_moneyextract'    => 0,
                'rideer_money'          => $Wallet->GetMoney($data['user_id']),
            );
        }

        if($data['user_type'] == 3 || $data['user_type'] == 4){

            $Wallet = new Wallet;
            $OrderItem = new OrderItem;
            $GoodsShare = new GoodsShare;

            $head = array(
                'head_sharecount'   => $GoodsShare->GetCount(array('share_user'=>$data['user_id'])),
                'head_salescount'   => $OrderItem->where(array('item_head'=>$data['user_id']))->sum('item_number'),
                'head_userscount'   => $this->User->GetCount(array('user_parent'=>$data['user_id'])),
                'head_moneycount'   => $OrderItem
                                            -> alias('io')
                                            -> join('__ORDER__ o','o.order_id = io.item_order')
                                            -> where(array('io.item_head'=>$data['user_id'],'o.order_ispay'=>1,'o.order_status'=>array('neq',50)))
                                            -> sum('item_commission'),
                'head_withdcount'   => 0,
                'head_money'        => $Wallet->GetMoney($data['user_id']),
            );
        }

        $this->assign('data',$data);
        $this->assign('address',$address);
        $this->assign('statistics',array_merge($rider,$head));
        return view();
    }


    //查看订单记录
    public function showrecord($id=0, $ispay = -1, $time = "")
    {
        // 筛选条件
        $map = [];
        $map['order_user'] = $id;

        $condition = [];
        $condition['order_user'] = $id;
        $condition['order_ispay'] = -1;
        $condition['order_time'] = '';


        if($ispay != -1){
            $map['order_ispay'] = $ispay;
            $condition['order_ispay'] = $ispay;
        }

        if($time !== ""){
            $condition['order_time'] = $time;
            $time = explode(' , ',$time);
            $map['order_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        $order = new Order;
        $data = $order->GetDataList($map);

        $this->assign('condition',$condition);
        $this->assign('data',$data);
        $this->assign('user',$id);
        return view();
    }

    //查看其他记录
    public function showother($id,$type)
    {
        $order = new Order;
        $user = $this->User->GetOneDataById($id);

        if($type == 'order'){

            $task = new Task;

            $data = $task->GetDataList(array('task_ordersuser'=>$id));

            foreach ($data as $key => $value) {
                $data[$key]['order_id'] = $order->GetField(array('order_task'=>$value['task_id']),'order_id');
                $data[$key]['task_school'] = $this->School->GetField(array('school_id'=>$value['task_school']),'school_name');
                $data[$key] = $this->CodeConversionText($value,'task');
            }

        }else{

            $OrderItem = new OrderItem;
            $GoodsShare = new GoodsShare;

            $data = $GoodsShare->GetDataList(array('share_user'=>$id));

            foreach ($data as $key => $value) {

                $orderis = $order->where(array('order_is_share'=>$value['share_id'],'order_ispay'=>1))->column('order_id');
                $data[$key]['sales'] = $OrderItem->where(array('item_order'=>array('in',$orderis)))->sum('item_number');
                $data[$key]['money'] = $order->where(array('order_is_share'=>$value['share_id'],'order_ispay'=>1))->sum('order_money');
                $data[$key]['commi'] = $OrderItem->where(array('item_order'=>array('in',$orderis)))->sum('item_commission');
            }
        }
        $this->assign('type',$type);
        $this->assign('data',$data);
        return view();
    }

    // 团长查看佣金记录
    public function commissionrecord($id = 0)
    {
        // 根据团长id获取团长已完成的订单id
        $orderMap = [];
        $orderMap['order_user'] = $id;
        $orderMap['order_status'] = ['in', '35, 40'];
        $order = new Order;
        $orderIds = $order->GetOrderIds($orderMap, 'order_id');
    

        // 根据order_id查询佣金
        $orderItem = new OrderItem;
        $itemMap['item_order'] = ['in', $orderIds];
        $itemMap['item_head'] = $id;
        $data = $orderItem->GetDataList($itemMap);

        // 获取订单编号
        foreach ($data as $key => $value) {
            $data[$key]['order_sn'] = $order->GetField(array('order_id'=>$value['item_order']),'order_sn');
        }

        $this->assign('data',$data);
        return view();
    }

    // 骑手查看收益记录
    public function pricerecord($id = 0)
    {
        // 根据团长id获取团长已完成的订单id
        $taskMap = [];
        $taskMap['task_ordersuser'] = $id;
        $taskMap['task_status'] = ['in', '30, 40'];
        $task = new Task;
        $data = $task->GetDataList($taskMap);

        $this->assign('data',$data);
        return view();
    }

    //修改数据
    public function update($id = 0)
    {
        if(request()->isPost()){

            $data = input('post.');

            $this->User->UpdateData($data);
        }else{

            $data = $this->User->GetOneDataById($id);

            if($data['user_type'] == 1)
                $typename = '会员';
            else
                $typename = $data['user_type'] == 2 ? '骑手' : '团长';

            $this->assign('schools',$this->School->GetDataList(array('school_status'=>1)));
            $this->assign('typename',$typename);
            $this->assign('data',$data);

            return view();
        }
    }

    //删除数据
    public function delete($id)
    {
        return $this->User->DeleteData($id);
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
                case 35:
                    $data['task_scheduleText'] = '待评价';
                    break;
                case 40:
                    $data['task_scheduleText'] = '已评价';
                    break;
            }
        }
        return $data;
    }

    // 物流接口测试
    public function test()
    {
        $uName = Config::get('boudata.uName');
        $uToken = Config::get('boudata.uToken');
        $url = Config::get('boudata.url');
        $timestamp = date("Y-m-d H:i:s", time());
        $uSign = md5($uToken . $timestamp);

        $data = [];
        $data['uName'] = $uName;
        $data['uSign'] = $uSign;
        $data['timestamp'] = $timestamp;
        $data['params'] = '{"ccode":"Erp0000004","code":"Erp0000004","send_man":"张三","send_phone":"18767166222","send_province":"浙江省","send_city":"杭州市","send_district":"江干区","send_town":"","send_street_no":"11","receive_man":"李四","receive_phone":"18767166333","receive_province":"江苏省","receive_city":"扬州市","receive_district":"高邮市","receive_town":"","receive_street_no":"22","amount":1,"volume":10.00,"weight":10.00,"service_mode":"派送","insurance_limit":10.00,"pay_type":"寄付","cod":10.00,"if_visit":true,"if_fast":"","remark":"","settle_type":"1"}';

        $res = $this->curl($url, $data);
        dump($res);die;
    }

    //请求方法
    public function curl($url,$data=array())
    {
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL,$url);
        // 设置header头
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded; charset=utf-8'));

        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 自动设置Referer
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        //执行命令
        $json = curl_exec($curl);

        if (curl_errno($curl)) {
        return 'Errno' . curl_error($curl);
        }
        //关闭URL请求
        curl_close($curl);

        $data = json_decode($json,true);
        return $data;
    }
}