<?php 

namespace app\admin\controller;

use app\admin\model\Address;
use app\admin\model\Area;
use app\admin\model\User as UserModel;
use app\admin\model\Task as TaskModel;
use app\admin\model\Order as OrderModel;
use app\admin\model\School as SchoolModel;
use app\admin\model\OrderItem as OrderItemModel;
use think\Config;

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

    public function wuliu($id)
    {
        // 仓库列表
        $wareModel = new \app\admin\model\Warehouse();
        $data = $wareModel->GetDataList(['ware_status' => 1]);

        $this->assign('wareHouse',$data);
        $this->assign('orderId',$id);
        return $this->fetch();
    }

    // 物流
    public function addWuliu()
    {
        $info = input('post.');

        // 根据订单id获取订单资料
        $orderInfo = $this->Order->GetOneDataById($info['orderId']);

        // 订单详细资料
        $itemInfo = $this->OrderItem->GetOneDataByOrderId($orderInfo['order_id']);

        // 发货人资料
        $wareModel = new \app\admin\model\Warehouse();
        $wareInfo = $wareModel->GetOneDataById($info['wareId']);
        $schoolInfo = $this->School->GetOneDataById($wareInfo['ware_school']);

        // 下单用户资料
        $orderUser = $this->User->GetOneDataById($orderInfo['order_user']);

        // 收货人资料
        $addressModel = new Address();
        $receiveInfo = $addressModel->GetOneDataById($orderInfo['order_address']);

        $areaModel = new Area();
        $where['id'] = ['in', [$receiveInfo['address_province'], $receiveInfo['address_city'], $receiveInfo['address_area'], $schoolInfo['school_province'], $schoolInfo['school_city'], $schoolInfo['school_area']]];

        $areaInfo = $areaModel->GetDataList($where);
        $areaInfo = array_column($areaInfo, 'name', 'id');

        $wuliuData = [
            "ccode" => $orderInfo['order_sn'],
            "code" => $orderInfo['order_sn'],
            "send_man" => $info['send_man'],
            "send_phone" => $info['send_phone'],
            "send_province" => $areaInfo[$schoolInfo['school_province']],
            "send_city" => $areaInfo[$schoolInfo['school_city']],
            "send_district" => $areaInfo[$schoolInfo['school_area']],
            "send_town" => "",
            "send_street_no" => $wareInfo['ware_address'],
            "receive_man" => $this->User->GetField(['user_id' => $receiveInfo['address_user']], 'user_name'),
            "receive_phone" => $receiveInfo['address_mobile'],
            "receive_province" => $areaInfo[$receiveInfo['address_province']],
            "receive_city" => $areaInfo[$receiveInfo['address_city']],
            "receive_district" => $areaInfo[$receiveInfo['address_area']],
            "receive_town" => "",
            "receive_street_no" => $receiveInfo['address_info'],
            "amount" => $itemInfo['item_number'],
            "volume" => 10.00,
            "weight" => 10.00,
            "service_mode" => "派送",
            "insurance_limit" => 00.00,
//            "pay_type" => "寄付",
            "cod" => 00.00,
            "if_visit" => true,
            "if_fast" => "",
            "remark" => "",
            "settle_type" => "寄付"
        ];
        $uName = Config::get('boudata.uName');
        $uToken = Config::get('boudata.uToken');
        $url = Config::get('boudata.url');
        $timestamp = date("Y-m-d H:i:s", time());
        $uSign = md5($uToken . $timestamp);

        $data = [];
        $data['uName'] = $uName;
        $data['uSign'] = $uSign;
        $data['timestamp'] = $timestamp;
        $data['params'] = json_encode($wuliuData);


//        $data['params'] = '{"ccode":"Erp0000005","code":"Erp0000005","send_man":"张三","send_phone":"18767166222","send_province":"浙江省","send_city":"杭州市","send_district":"江干区","send_town":"","send_street_no":"11","receive_man":"李四","receive_phone":"18767166333","receive_province":"江苏省","receive_city":"扬州市","receive_district":"高邮市","receive_town":"","receive_street_no":"22","amount":1,"volume":10.00,"weight":10.00,"service_mode":"派送","insurance_limit":10.00,"pay_type":"寄付","cod":10.00,"if_visit":true,"if_fast":"","remark":"","settle_type":"1"}';

        $res = $this->curl($url, $data);
        if (isset($res['result']) && $res['result']) {
            // 修改订单状态和order_ocode
            $update = [
                'order_id' => $info['orderId'],
                'order_ocode' => $res['data'],
                'order_schedule' => 20
            ];
            $order = $this->Order->UpdateData($update);
            return ['code' => 1, 'msg' => '下单成功'];

        } else {
            return ['code' => 0, 'msg' => $res['errorMsg']];
        }
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