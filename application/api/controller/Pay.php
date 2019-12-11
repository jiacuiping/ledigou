<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;
use app\admin\model\Task;
use app\admin\model\Goods;
use app\admin\model\Order;
use app\admin\model\Address;
use app\admin\model\Limited;
use app\admin\model\OrderItem;
use app\admin\model\Shoppingcar;

use app\api\controller\User;
use EasyWeChat\Factory;

/**
 * 接口主页方法
 */

class Pay extends Base
{
	private $Item;
	private $Task;
	private $User;
    private $Order;
    private $Goods;
    private $Address;
    private $Limited;
    private $Shoppingcar;
    private $config = array(
	    // 必要配置
	    'app_id'             => 'wxf8d6a2af2002f997',
	    'mch_id'             => '1545804761',
	    'key'                => '78901fd7992fb7e5bc3323b4cb48ef8c',   // API 密钥
	    // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
	    //'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
	    //'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
	    'notify_url'         => 'http://dxc.gqwlcm.com/api/pay/Callback',     // 你也可以在下单时单独设置来想覆盖它
    );


	//构造函数
	public function __construct()
	{
		parent::__construct();

        $this->User = new User();
        $this->Task = new Task();
        $this->Order = new Order();
        $this->Goods = new Goods();
        $this->Item = new OrderItem();
        $this->Address = new Address();
        $this->Limited = new Limited();
        $this->Shoppingcar = new Shoppingcar();
	}

	//创建商品订单
	public function CreateOrder($user=0, $is_car='', $is_share=0, $coupon=0, $goods=array())
	{
		if($user == 0) return json_encode(array('code'=>0,'msg'=>'用户不存在'));

		if(empty($goods)) return json_encode(array('code'=>0,'msg'=>'请至少选择一种商品'));

		//创建订单
		$order = array(
			'order_sn'				=> $this->CreateOrderSn($user),
			'order_user'			=> $user,
			'order_money'			=> 0,
			'order_is_car'			=> $is_car,
			'order_is_share'		=> $is_share,
			'order_desc'			=> '商品购买',
			'order_ispay'			=> 0,
			'order_coupon'			=> $coupon,
			'order_paymoney'		=> 0,
			'order_paytime'			=> 0,
			'order_deliverymethod'	=> 0,
			'order_task'			=> 0,
			'order_schedule'		=> 0,
			'order_address'			=> $this->Address->GetField(array('address_user'=>$user,'address_default'=>1),'address_id'),
			'order_time'			=> time()
		);

		$orderResult = $this->Order->CreateData($order);

		foreach ($goods as $key => $value) {
			
			$goodsinfo = $this->Goods->GetOneDataById($value['goods_id']);

			if($value['is_offer'] == 0)
				$summoney = $goodsinfo['goods_price'] * $value['number'];
			else
				$summoney = $this->Limited->GetField(array('limited_id'=>$value['is_offer']),'limited_money') * $value['number'];

			$item[] = array(
				'item_order'		=> $orderResult['id'],
				'item_goods'		=> $value['goods_id'],
				'item_number'		=> $value['number'],
				'item_head'			=> $is_share,
				'item_is_offer'		=> $value['is_offer'],
				'item_money'		=> $summoney,
				'item_commission'	=> $goodsinfo['goods_brokerage'] * $value['number']
			);
		}

		$itemResult =  $this->Item->insertAll($item);

		$this->Order->UpdateData(array('order_id'=>$orderResult['id'],'order_money'=>$this->Item->where('item_order',$orderResult['id'])->sum('item_money')+session::get('config.website_freight')));

		//如果是来自购物车  则删除相应商品
		if($is_car != '') $this->Shoppingcar->where('car_id','in',$is_car)->delete();

		return $orderResult && $itemResult ? array('code'=>1,'msg'=>'订单创建成功','order_sn'=>$order['order_sn']) : array('code'=>0,'msg'=>'订单创建失败');
	}


	//创建跑腿任务
	public function CreateTask($data)
	{
		$order = array(
			'order_sn'				=> $this->CreateOrderSn($data['user']),
			'order_user'			=> $data['user'],
			'order_money'			=> $data['task_price'],
			'order_is_car'			=> '',
			'order_is_share'		=> 0,
			'order_desc'			=> '跑腿任务',
			'order_ispay'			=> 0,
			'order_coupon'			=> 0,
			'order_paymoney'		=> 0,
			'order_paytime'			=> 0,
			'order_deliverymethod'	=> 1,
			'order_task'			=> 0,
			'order_schedule'		=> 0,
			'order_time'			=> time()
		);

		$orderResult = $this->Order->CreateData($order);

		$task = array(
			'task_user'				=> $data['user'],
			'task_title'			=> $data['task_title'],
			'task_price'			=> $data['task_price'],
			'task_desc'				=> $data['task_desc'],
			'task_pickupaddress'	=> $data['task_pickupaddress'],
			'task_pickupmobile' 	=> $data['task_pickupmobile'],
			'task_shippingaddress'	=> $data['task_shippingaddress'],
			'task_shippinguser'		=> $data['task_shippinguser'],
			'task_shippingmobile'	=> $data['task_shippingmobile'],
			'task_school'			=> $data['school'],
		);

		$taskResult = $this->Task->CreateData($task);

		$this->Order->UpdateData(array('order_id'=>$orderResult['id'],'order_task'=>$taskResult['id']));

		return $orderResult['code'] == 1 && $taskResult['code'] == 1 ? array('code'=>1,'msg'=>'订单创建成功','order_sn'=>$order['order_sn']) : array('code'=>0,'msg'=>'订单创建失败');
	}

	//公众号调起支付
	public function Pay($order_sn)
	{
		$app = Factory::payment($this->config);
        //dump($this->config);die;

		$result = $app->order->unify([
		    'body' => '辣条1',
		    'out_trade_no' => 'LDG156403248000002',
		    'total_fee' => 88,
		    'trade_type' => 'JSAPI',
		    'openid' => 'oEtgvwHAmvdAk4f83FoD6o7r4seI',
		]);

		return $result;
		
	}

	//支付回调
	public function Callback()
	{

	}

	//生成订单编号
	public function CreateOrderSn($user=0)
	{
		$param = $user == 0 ? session::get('user.user_id') : $user;

		return 'LDG'.time().str_pad($param,5,0,STR_PAD_LEFT);
	}
}