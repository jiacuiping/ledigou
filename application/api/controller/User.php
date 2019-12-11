<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\api\controller\Pay;

use app\admin\model\User as UserModel;
use app\admin\model\Goods as GoodsModel;
use app\admin\model\Order as OrderModel;
use app\admin\model\Coupon as CouponModel;
use app\admin\model\School as SchoolModel;
use app\admin\model\Address as AddressModel;
use app\admin\model\Limited as LimitedModel;
use app\admin\model\Warehouse as WarehouseModel;
use app\admin\model\OrderItem as OrderItemModel;
use app\admin\model\UserCoupon as UserCouponModel;
use app\admin\model\Shoppingcar as ShoppingcarModel;

/**
 * 接口主页方法
 */

class User extends Base
{
	private $User;
	private $Goods;
	private $Order;
	private $Coupon;
	private $School;
	private $Address;
	private $Limited;
	private $Warehouse;
	private $OrderItem;
	private $UserCoupon;
	private $Shoppingcar;

	//构造函数
	public function __construct()
	{
		parent::__construct();

		$this->User = new UserModel();
		$this->Goods = new GoodsModel();
		$this->Order = new OrderModel();
		$this->Coupon = new CouponModel();
		$this->School = new SchoolModel();
		$this->Address = new AddressModel();
		$this->Limited = new LimitedModel();
		$this->Warehouse = new WarehouseModel();
		$this->OrderItem = new OrderItemModel();
		$this->UserCoupon = new UserCouponModel();
		$this->Shoppingcar = new ShoppingcarModel();
	}

	//添加用户
	public function CreateUser()
	{
		$data = input('post.');

		$user = array(
			'user_name'		=> $data['data']['nickName'],
			'user_avatar'	=> $data['data']['avatarUrl'],
			'user_wxid'		=> $data['openid'],
			'user_unionid'	=> $data['unionid']
		);

		$this->User->CreateData($user);
	}


	// 小程序登陆之后的操作
	public function FindUser($unionid,$wxid)
	{
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));

		if(!isset($user['user_name'])) return 0;

		if($user['user_wxid'] == '') $this->User->UpdateData(array('user_id'=>$user['user_id'],'user_wxid'=>$wxid));

		return 1;
	}

    /**
     * 获取用户信息
     * @param string 	$param 	参数
     * @param string   	$by 	unionid || userid
     **/
	public function GetUserInfo($param='',$by='unionid')
	{
		$user = $this->CheckUser($param,$by);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		return json_encode(array('code'=>1,'msg'=>'获取成功','user'=>$user['user']));
	}

    /**
     * 获取用户钱包
     * @param string 	$param 	参数
     * @param string   	$by 	unionid || userid
     **/
	public function GetUserAvatar($param='',$by='unionid')
	{
		$user = $this->CheckUser($param,$by);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		$wallet = $this->Wallet->GetOneData(array('wallet_user'=>$user['user']['user_id']));

		if(!$wallet) return json_encode(array('code'=>0,'msg'=>'用户未开通钱包'));
		
		if($wallet['wallet_status'] == 0) return json_encode(array('code'=>0,'msg'=>'该钱包已被冻结，请联系管理员'));

		return json_encode(array('code'=>1,'msg'=>'获取成功','wallet'=>$wallet));
	}

	//根据地址获取学校信息
	public function GetSchoolByAddress($address_id=0)
	{
		if($address_id == 0) return json_encode(array('code'=>0,'msg'=>'参数错误'));

		$school_id = $this->Address->GetField(array('address_id'=>$address_id),'address_school');

		if(!$school_id) return json_encode(array('code'=>0,'msg'=>'地址不存在'));

		$data = $this->School->GetOneData(array('school_id'=>$school_id));

		return json_encode(array('code'=>1,'msg'=>'获取成功','school'=>$data));
	}

    /**
     * 获取用户优惠券
     * @param string 	$param 	参数
     * @param string   	$by 	unionid || userid
     **/
	public function GetUserCoupon($param='',$by='unionid')
	{
		$user = $this->CheckUser($param,$by);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		$coupon = $this->UserCoupon
					-> alias('uc')
					-> join('coupon c','uc.uc_coupon = c.coupon_id')
					-> where(array('uc.uc_user'=>$user['user']['user_id'],'uc.uc_type'=>0))
					-> select();

		return json_encode(array('code'=>1,'msg'=>'获取成功','coupon'=>$coupon));
	}

	
    /**
     * 获取地址列表
     * @param string 	$param 	参数
     * @param string   	$by 	unionid || userid
     **/
	public function GetAddressList($param='',$by='unionid')
	{
		$user = $this->CheckUser($param,$by);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		$address = $this->Address
					-> alias('a')
					-> field('a.*,s.school_name')
					-> join('__SCHOOL__ s','a.address_school = s.school_id')
					-> where('a.address_user',$user['user']['user_id'])
					-> order('a.address_default desc a.address_time desc')
					-> select();

		return json_encode(array('code'=>1,'msg'=>'获取成功','address'=>$address));
	}	

    /**
     * 获取地址信息
     * @param string 	$address_id 	地址ID
     **/
	public function GetAddressInfo($address_id=0)
	{
		if($address_id == 0) return json_encode(array('code'=>0,'msg'=>'参数错误'));

		$address = $this->Address
					-> alias('a')
					-> field('a.*,s.school_name')
					-> join('__SCHOOL__ s','a.address_school = s.school_id')
					-> where('a.address_id',$address_id)
					-> find();

		return json_encode(array('code'=>1,'msg'=>'获取成功','address'=>$address));
	}

    /**
     * 添加收货地址
     * @param string 	$unionid 	用户unionid
     * @param string   	$param 	添加的地址信息
     **/
	public function CreateAddress($unionid,$param=array())
	{
		$user = $this->CheckUser($unionid);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		if(empty($param)) return json_encode(array('code'=>0,'msg'=>'参数错误'));

		if($param['address_school'] == 0) return json_encode(array('code'=>0,'msg'=>'请选择学校'));

		$schoolInfo = $this->School->GetOneData(array('school_id'=>$param['address_school']));

		if(!$schoolInfo) return json_encode(array('code'=>0,'msg'=>'学校信息不存在'));

		$param['address_user']		= $user['user']['user_id'];
		$param['address_province']	= $schoolInfo['school_province'];
		$param['address_city']		= $schoolInfo['school_city'];
		$param['address_area']		= $schoolInfo['school_area'];
		$param['address_default']   = empty($param['address_default']) ? 0 : 1;

		$addressCount = $this->Address->GetCount(array('address_user'=>$user['user']['user_id']));

		if($addressCount == 0)
			$param['address_default'] = 1;
		else
			if($param['address_default'] == 1) $this->Address->where('address_user',$user['user']['user_id'])->update(['address_default'=>0]);

		if($param['address_default'] == 1 || $addressCount == 0)
			$this->User->UpdateData(array('user_id'=>$user['user']['user_id'],'user_school'=>$param['address_school']));

		return json_encode($this->Address->CreateData($param));
	}

    /**
     * 修改收货地址
     * @param string 	$address_id 	地址主键
     * @param string   	$param 			修改的地址信息
     **/
	public function UpdateAddress($address_id=0,$param=array())
	{
		if($address_id == 0) return json_encode(array('code'=>0,'msg'=>'参数错误'));

		if(empty($param)) return json_encode(array('code'=>0,'msg'=>'参数错误'));

		if($param['address_school'] == 0) return json_encode(array('code'=>0,'msg'=>'请选择学校'));

		$schoolInfo = $this->School->GetOneData(array('school_id'=>$param['address_school']));

		if(!$schoolInfo) return json_encode(array('code'=>0,'msg'=>'学校信息不存在'));

		$param['address_province']	= $schoolInfo['school_province'];
		$param['address_city']		= $schoolInfo['school_city'];
		$param['address_area']		= $schoolInfo['school_area'];
		$param['address_id']		= $address_id;
		$param['address_default']   = empty($param['address_default']) ? 0 : 1;
		// dump($param);die;
		if($param['address_default'] == 1){
			$user = $this->Address->Getfield(array('address_id'=>$address_id),'address_user');
			$this->Address->where('address_user',$user)->update(['address_default'=>0]);
		}

		return json_encode($this->Address->UpdateData($param));
	}

    /**
     * 删除收货地址
     * @param string 	$address_id 	地址主键
     **/
	public function DeleteAddress($unionid,$address_id=0)
	{
		$user = $this->CheckUser($unionid);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		if($address_id == 0) return json_encode(array('code'=>0,'msg'=>'参数错误'));

		if($this->Address->GetCount(array('address_user'=>$user['user']['user_id'])) <= 1) return json_encode(array('code'=>0,'msg'=>'请至少保留一个地址'));

		return  json_encode($this->Address->DeleteData($address_id));
	}

    /**
     * 获取用户购物车
     * @param string 	$unionid 		用户unionid
     **/
	public function GetCars($unionid)
	{
		$user = $this->CheckUser($unionid);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		$this->ProcessOffer($user['user']['user_id']);

		$data = $this->Shoppingcar
					-> alias('s')
					-> join('__GOODS__ g','s.car_goods = g.goods_id')
					-> where(array('s.car_user'=>$user['user']['user_id'],'car_type'=>1))
					-> select();

		foreach ($data as $key => $value) {

			if($value['car_is_offer'] != 0)
				$data[$key]['offer_price'] = $this->Limited->GetField(array('limited_id'=>$value['car_is_offer']),'limited_money')*$value['car_number'];
			else
				$data[$key]['offer_price'] = $value['goods_price'] * $value['car_number'];

			$data[$key]['count_price'] = $value['goods_price'] * $value['car_number'];

			$data[$key]['goods_image'] = session::get('config.website_indexurl').$value['goods_image'];

			$data[$key]['warehouse'] = $this->Warehouse->GetField(array('ware_id'=>$value['goods_warehouse']),'ware_name');

		}

		$freight = session::get('config.website_freight');
																									//加运费
		$countPrice = empty($data) ? 0 : round(array_sum(array_map(function($val){return $val['offer_price'];}, $data))+$freight,2);

		$countNumber = empty($data) ? 0 : array_sum(array_map(function($val){return $val['car_number'];}, $data));

		return  json_encode(array('code'=>1,'msg'=>'获取成功','car'=>$data,'countPrice'=>$countPrice,'countNumber'=>$countNumber,'freight'=>$freight));
	}


    /**
     * 修改购物车
     * @param string 	$unionid 		用户unionid
     * @param int 		$car_id 	购物车ID
     * @param string 	$type 		inc 件数+1 dec 件数-1 del 删除
     **/
	public function ChangeCar($unionid='',$car_id=0,$type='inc')
	{
		if($unionid == '' || $car_id == 0) return json_encode(array('code'=>0,'msg'=>'参数不正确'));

		$user = $this->CheckUser($unionid);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		$Car = $this->Shoppingcar->GetOneData(array('car_id'=>$car_id,'car_type'=>1));

		if(!$Car) return json_encode(array('code'=>0,'msg'=>'购物车不存在'));

		if($type == 'dec' && $Car['car_number'] == 1) return json_encode(array('code'=>0,'msg'=>'件数不可小于一'));

		$carinfo = $this->Shoppingcar->GetOneData(array('car_id'=>$car_id));

		if($type == 'inc' && $carinfo['car_is_offer'] != 0){

			$ids = $this->Order->where(array('order_user'=>$user['user']['user_id'],'order_desc'=>'商品购买','order_ispay'=>1))->column('order_id');
			$count = $ids ? $this->OrderItem->where(array('item_order'=>array('in',$ids),'item_is_offer'=>$limiteddata['limited_id']))->sum('item_number') : 0;

			if($carinfo['car_number'] + $count = $this->Limited->GetField(array('limited_id'=>$carinfo['car_is_offer']),'limited_user_number'))
				return json_encode(array('code'=>0,'msg'=>'特价商品件数已达上限'));
		}

		$where['car_id'] = $car_id;

		if($type == 'inc' || $type == 'dec')
			$result = $type == 'inc' ? $this->Shoppingcar->DataSetInc($where,'car_number') : $this->Shoppingcar->DataSetDec($where,'car_number');
		else
			$result = $this->Shoppingcar->DeleteData($car_id);

		return json_encode($result);
	}

    /**
     * 处理已过期抢购商品
     * @param string 	$unionid 		用户unionid
     **/
	public function ProcessOffer($user)
	{
		$data = $this->Shoppingcar->GetDataList(array('car_user'=>$user,'car_type'=>1,'car_is_offer'=>array('neq',0)));

		foreach ($data as $key => $value) {
			$offer = $this->Limited->GetOneData(array('limited_id'=>$value['car_is_offer']));
				//优惠已删除			//优惠未开始						//优惠已结束
			if(empty($offer) || $offer['limited_stime'] > time() && $offer['limited_etime'] < time()){

				$notoffer = $this->Shoppingcar->GetOneData(array('car_goods'=>$value['car_goods'],'car_user'=>$user,'car_is_offer'=>0));

				if($notoffer){
					$this->Shoppingcar->DataSetInc(array('car_id'=>$notoffer['car_id']));
					$this->Shoppingcar->DeleteData($value['car_id']);
				}else{
					$this->Shoppingcar->UpdateData(array('car_id'=>$value['car_id'],'car_is_offer'=>0));
				}
			}
		}
	}

    /**
     * 检测用户
     * @param string 	$param 	参数
     * @param string   	$by 	unionid || userid
     **/
	public function CheckUser($param='',$by='unionid')
	{
		if(empty($param)) return array('code'=>0,'msg'=>'参数不正确');

		$where = $by == 'unionid' ? array('user_unionid'=>$param) : array('user_id'=>$param);

		$user = $this->User->GetOneData($where);

		if(!$user) return array('code'=>0,'msg'=>'用户不存在');

		if($user['user_status'] == 0) return json_encode(array('code'=>0,'msg'=>'该用户已被停用，请联系管理员'));

		return array('code'=>1,'user'=>$user);
	}

	//加入购物车
	public function JoinCar($unionid,$goods,$is_offer,$number=1)
	{
		$isAdd = true;
		$user = $user = $this->CheckUser($unionid);

		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		// $cardata = $this->Shoppingcar->GetOneData(array('car_user'=>$user['user']['user_id'],'car_goods'=>$goods,'car_type'=>1,'car_is_offer'=>$is_offer));
		// if($cardata){
		// 	$data = $this->Shoppingcar->DataSetInc(array('car_id'=>$cardata['car_id']),'car_number',$number);
		// }else{
		// 	$car = array(
		// 		'car_user'		=> $user['user']['user_id'],
		// 		'car_goods'		=> $goods,
		// 		'car_number'	=> $number,
		// 		'car_is_offer'	=> $is_offer,
		// 		'car_type'		=> 1,
		// 		'car_time'		=> time(),
		// 	);

		// 	$data = $this->Shoppingcar->CreateData($car);
		// }

		$cardata = $this->Shoppingcar->GetOneData(array('car_user'=>$user['user']['user_id'],'car_goods'=>$goods,'car_type'=>1,'car_is_offer'=>$is_offer));

		if($is_offer == 0){
			if($cardata){
				$isAdd = false;
				$data = $this->Shoppingcar->DataSetInc(array('car_id'=>$cardata['car_id']),'car_number',$number);
			}else{
				$car = array(
					'car_user'		=> $user['user']['user_id'],
					'car_goods'		=> $goods,
					'car_number'	=> $number,
					'car_is_offer'	=> $is_offer,
					'car_type'		=> 1,
					'car_time'		=> time(),
				);

				$data = $this->Shoppingcar->CreateData($car);
			}
		}else{

			$limiteddata = $this->Limited->GetOneData(array('limited_goods'=>$goods,'limited_stime'=>array('LT',time()),'limited_etime'=>array('GT',time()),'limited_user_number'=>array('neq',0)));
			$ids = $this->Order->where(array('order_user'=>$user['user']['user_id'],'order_desc'=>'商品购买','order_ispay'=>1))->column('order_id');
			$count = $ids ? $this->OrderItem->where(array('item_order'=>array('in',$ids),'item_is_offer'=>$limiteddata['limited_id']))->sum('item_number') : 0;
			if($count == $limiteddata['limited_user_number']){
				$car = array(
					'car_user'		=> $user['user']['user_id'],
					'car_goods'		=> $goods,
					'car_number'	=> $number,
					'car_is_offer'	=> 0,
					'car_type'		=> 1,
					'car_time'		=> time(),
				);
			}elseif($limiteddata['limited_user_number'] < $number + $count){
				$car = array(
					'car_user'		=> $user['user']['user_id'],
					'car_goods'		=> $goods,
					'car_number'	=> $limiteddata['limited_user_number'] - $count,
					'car_is_offer'	=> $is_offer,
					'car_type'		=> 1,
					'car_time'		=> time(),
				);

				$cartwo = array(
					'car_user'		=> $user['user']['user_id'],
					'car_goods'		=> $goods,
					'car_number'	=> $number - $car['car_number'],
					'car_is_offer'	=> 0,
					'car_type'		=> 1,
					'car_time'		=> time(),
				);

			}else{
				$car = array(
					'car_user'		=> $user['user']['user_id'],
					'car_goods'		=> $goods,
					'car_number'	=> $number,
					'car_is_offer'	=> $is_offer,
					'car_type'		=> 1,
					'car_time'		=> time(),
				);
			}
		}

		if($isAdd)
			$data = $this->Shoppingcar->CreateData($car);
		else
			$data['code'] == 1;
		if(isset($cartwo)) $this->Shoppingcar->insert($cartwo);
		return $data['code'] == 1 ? json_encode(array('code'=>1,'msg'=>'加入购物车成功')) : json_encode(array('code'=>0,'msg'=>'加入购物车失败'));
	}


	//创建订单
	public function CreateOrder($unionid='',$cars=array())
	{
		if($unionid == '') return json_encode(array('code'=>0,'msg'=>'用户不存在'));

		if(empty($cars)) return json_encode(array('code'=>0,'msg'=>'请至少选择一种商品'));

		$user = $user = $this->CheckUser($unionid);
		
		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		if(!$this->Address->GetOneData(array('address_user'=>$user['user']['user_id'],'address_default'=>1))) return json_encode(array('code'=>0,'msg'=>'您还没有设置默认收货地址'));
		
		$carids = implode(',',$cars);

		$carsInfo = $this->Shoppingcar->GetDataList(array('car_id'=>array('in',$carids)));
		
		//判断是否是一个仓库
		$goodsIds = array_column($carsInfo, 'car_goods');

		$warehouse = $this->Goods->where(array('goods_id'=>array('in',implode(',',$goodsIds))))->group('goods_warehouse')->column('goods_warehouse');
		
		if(count($warehouse) > 1) return json_encode(array('code'=>0,'msg'=>'请选择同一仓库商品进行结算'));

		//整理商品准备创建订单
		foreach ($carsInfo as $key => $value) {
			$goods[] = array(
				'goods_id'	=> $value['car_goods'],
				'number'	=> $value['car_number'],
				'is_offer'	=> $value['car_is_offer']
			);
		}

		$pay = new Pay();

		return json_encode($pay->CreateOrder($user['user']['user_id'],$carids,0,0,$goods));		
	}

	//立即购买
	public function BuyNow($unionid='',$goods_id=0,$is_offer=0,$number=1)
	{      
		if($goods_id == 0) return json_encode(array('code'=>0,'msg'=>'请至少选择一种商品'));

		$user = $this->CheckUser($unionid);
      
		if($user['code'] == 0) return json_encode(array('code'=>0,'msg'=>$user['msg']));

		if(!$this->Address->GetOneData(array('address_user'=>$user['user']['user_id'],'address_default'=>1))) return json_encode(array('code'=>0,'msg'=>'您还没有设置默认收货地址'));

		$limiteddata = $this->Limited->GetOneData(array('limited_goods'=>$goods_id,'limited_stime'=>array('LT',time()),'limited_etime'=>array('GT',time()),'limited_user_number'=>array('neq',0)));

		if($limiteddata){

			if($limiteddata['limited_number'] != 0 && $limiteddata['limited_sold'] == $limiteddata['limited_number']) return json_encode(array('code'=>0,'msg'=>'商品已被抢空，下次早点来哦'));

			$ids = $this->Order->where(array('order_user'=>$user['user']['user_id'],'order_desc'=>'商品购买','order_ispay'=>1))->column('order_id');
			$count = $ids ? $this->OrderItem->where(array('item_order'=>array('in',$ids),'item_is_offer'=>$limiteddata['limited_id']))->sum('item_number') : 0;
			if($count == $limiteddata['limited_user_number']){
				$goods[0] = array(
					'goods_id'	=> $goods_id,
					'number'	=> $number,
					'is_offer'	=> 0
				);
			}elseif($limiteddata['limited_user_number'] < $number + $count){
				$goods = array(
					0 => array(
						'goods_id'	=> $goods_id,
						'number'	=> $limiteddata['limited_user_number'] - $count,
						'is_offer'	=> $limiteddata['limited_id']
					),
					1 => array(
						'goods_id'	=> $goods_id,
						'number'	=> $number - ($limiteddata['limited_user_number'] - $count),
						'is_offer'	=> 0
					),
				);
			}else{
				$goods[0] = array(
					'goods_id'	=> $goods_id,
					'number'	=> $number,
					'is_offer'	=> $is_offer
				);
			}
		}else{
			$goods[0] = array(
				'goods_id'	=> $goods_id,
				'number'	=> $number,
				'is_offer'	=> $is_offer
			);
		}

		$pay = new Pay();

		return json_encode($pay->CreateOrder($user['user']['user_id'],'',0,0,$goods));		
	}
}