<?php
namespace app\index\controller;

use think\Session;
use app\index\controller\Base;
use app\api\controller\Pay;

use app\admin\model\Goods;
use app\admin\model\Order;
use app\admin\model\Address;
use app\admin\model\OrderItem;
use app\admin\model\GoodsShare;

//团长控制器

class Headshare extends LoginBase
{
    private $Item;
    private $Head;
    private $Order;
    private $Goods;
    private $Address;
    private $GoodsShare;

    public function __construct()
    {
        parent::__construct();
        $this->Head = 8;
        $this->Order = new Order();
        $this->Goods = new Goods();
        $this->Item = new OrderItem();
        $this->Address = new Address();
        $this->GoodsShare = new GoodsShare();
    }

    //分享商品列表
    public function list($head=0)
    {
        // 用户是否存在

        if($head != 0) $this->Head = $head;

    	$data = $this->GoodsShare->where('share_user',$head)->order('share_id desc')->find();

    	$goods = $this->Goods->GetDataList(array('goods_id'=>array('in',$data['share_goods'])));

    	$this->assign('goods',$goods);
    	return view();
    }

    //商品详情
    public function info($goods_id=0)
    {
        $this->assign('data',$this->Goods->GetOneData(array('goods_id'=>$goods_id)));

    	return view();
    }

    //创建订单
    public function CreateOrder($goods_id,$number)
    {
        $Pay = new Pay();
        $payRes = $Pay->CreateOrder(session::get('user.user_id'),0,$this->Head,0,array(0=>array('goods_id'=>$goods_id,'number'=>$number,'is_offer'=>0)));

        if ($payRes['code']) {
            return ['code' => 1, 'msg' => $payRes['msg'], 'order_sn' => $payRes['order_sn']];
        } else {
            return ['code' => 0, 'msg' => $payRes['msg']];
        }
    }


    //去支付
    public function paynow($order_sn)
    {
        $order = $this->Order->GetOneData(array('order_sn'=>$order_sn));
        $goods = $this->Item->GetDataList(array('item_order'=>$order['order_id']));
        $address = $this->Address->GetOneData(array('address_user'=>session::get('user.user_id'),'address_default'=>1));

        foreach ($goods as $key => $value) {

            $goodsInfo = $this->Goods->GetOneDataById($value['item_goods']);

            $goods[$key]['goods_name'] = $goodsInfo['goods_name'];
            $goods[$key]['offer_price'] = $goodsInfo['goods_offer_price'] * $value['item_number'];
            $goods[$key]['price'] = $goodsInfo['goods_price'] * $value['item_number'];
            $goods[$key]['goods_face'] = $goodsInfo['goods_image'];
        }

        $this->assign('freight',session::get('config.website_freight'));
        $this->assign('order',$order);
        $this->assign('goods',$goods);
        $this->assign('address',$address);
        return view();
    }

    //改变订单数量
    // public function changenumber($goods,$number,$type)
    // {
    //     $truenumber = $type == 'dec' ? $number - 1 : $number + 1;

    //     $data = $this->Goods->GetOneDataById($goods);

    //     $result['price'] = $data['goods_price'] * $truenumber;

    //     $result['offer_price'] = $data['goods_offer_price'] * $truenumber;


    // }
}