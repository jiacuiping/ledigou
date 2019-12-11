<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\User;
use app\admin\model\Order;
use app\admin\model\Warehouse;
use app\admin\model\Goods as GoodsModel;
use app\admin\model\Limited as LimitedModel;

/**
 * 接口主页方法
 */

class Goods extends Base
{
	private $User;
	private $Order;
	private $Goods;
	private $Limited;
	private $Warehouse;

	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->User = new User();
		$this->Order = new Order();
		$this->Goods = new GoodsModel();
		$this->Warehouse = new Warehouse();
		$this->Limited = new LimitedModel();
	}

	//获取商品列表
	public function GetGoodsList($unionid,$keyword='',$sort=0,$is_top=false,$ordername='complex',$ordertype='desc',$page=1,$limit=10)
	{
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));

		if(!$user) return json_encode(array('code'=>0,'msg'=>'用户信息不存在'));

		//if($user['user_school'] == '') return json_encode(array('code'=>0,'msg'=>'您还未填写学校信息'));

		$ware = $this->Warehouse->GetColumn(array('ware_school'=>$user['user_school']),'ware_id');

		$where['goods_warehouse'] = array('in',implode(',',$ware));

		if($keyword != '') $where['goods_name'] = array('like',"%$keyword%");

		if($sort != 0) $where['goods_sort'] = $sort;

		if($is_top != false) $where['goods_is_top'] = 1;

		if($ordername == 'complex')
			$order = 'goods_is_top '.$ordertype.',goods_rank '.$ordertype;
		elseif($ordername == 'price')
			$order = 'goods_price '.$ordertype;
		elseif($ordername == 'sales')
			$order = 'goods_sales '.$ordertype;

		$data = $this->Goods->GetListByPage($where,$page,$limit,$order);

		$Limitedwhere['limited_stime'] = array('<',time());
		$Limitedwhere['limited_etime'] = array('>',time());

		foreach ($data as $key => $value) {

			$Limitedwhere['limited_goods'] = $value['goods_id'];
			$offerInfo = $this->Limited->GetOneData($Limitedwhere);

			$data[$key]['is_offer'] = empty($offerInfo) ? 0 : $offerInfo['limited_id'];
			$data[$key]['offerInfo'] = empty($offerInfo) ? array() : $offerInfo;

			$data[$key]['goods_image'] = session::get('config.website_indexurl').$value['goods_image'];
		}

		if(empty($data)) return json_encode(array('code'=>0,'msg'=>'暂无商品信息'));

		return json_encode(array('code'=>1,'msg'=>'获取成功','goods'=>$data));
	}

	//获取商品详情
	public function GetGoodsInfo($goods_id=0)
	{
		if($goods_id == 0) return json_encode(array('code'=>0,'msg'=>'参数不正确'));

		$goods = $this->Goods->GetOneData(array('goods_id'=>$goods_id));


		if($goods){
			$Limitedwhere['limited_stime'] = array('<',time());
			$Limitedwhere['limited_etime'] = array('>',time());
			$Limitedwhere['limited_goods'] = $goods_id;
			$offerInfo = $this->Limited->GetOneData($Limitedwhere);

			$goods['is_offer'] = empty($offerInfo) ? 0 : $offerInfo['limited_id'];

			$goods['offerInfo'] = empty($offerInfo) ? array() : $offerInfo;

			$goods['goods_image'] = session::get('config.website_indexurl').$goods['goods_image'];
			return json_encode(array('code'=>1,'msg'=>'获取成功','goods'=>$goods));
		}else
			return json_encode(array('code'=>0,'msg'=>'商品不存在'));
	}

	// //获取优惠信息
	// public function GetOfferInfo($user_id,$goods_id=0)
	// {
	// 	$where['limited_stime'] = array('<',time());
	// 	$where['limited_etime'] = array('>',time());
	// 	$where['limited_goods'] = $goods_id;

	// 	$offer = $this->Limited->GetOneData($where);

	// 	if($data['limited_number'] != 0 && $data['limited_sold'] >= $data['limited_number'])
	// 		return array();	//有件数限制，已卖完

	// 	if($data['limited_user_number'] != 0){
	// 		//限制每人件数
	// 		$itemnumber = $this->Order
	// 						-> alias('o')
	// 						-> join('__ORDER_ITEM__ oi','oi.item_order = o.order_id')
	// 						-> where(array('order_user'=>$user_id,'oi.item_is_offer'=>$offer['limited_id']))
	// 						-> sum('oi.item_number');

	// 		$itemnumber = $itemnumber ? $itemnumber : 0;

	// 		if($itemnumber >= $offer['limited_user_number'])
	// 			return array();	//有用户购买件数限制，并且已超过件数
	// 	}

	// 	return $offer;
	// }
}