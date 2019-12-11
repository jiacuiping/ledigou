<?php
namespace app\index\controller;

use think\Session;
use app\index\controller\LoginBase;

use app\admin\model\Goods;
use app\admin\model\Order;
use app\admin\model\OrderItem;
use app\admin\model\GoodsShare;

//团长控制器

class Head extends LoginBase
{
	private $Item;
    private $Order;
    private $Goods;
	private $GoodsShare;

	public function __construct()
	{
		parent::__construct();

		if(session::get('user.user_type') != 3)
			$this->redirect('Login/Registered',array('type'=>3));

		if(session::get('user.user_review') != 1)
			$this->error('您的资料未通过审核，请稍后重试');

        $this->Order = new Order();
        $this->Goods = new Goods();
        $this->Item = new OrderItem();
		$this->GoodsShare = new GoodsShare();
	}

	//商品列表
    public function GoodsList()
    {
    	$this->assign('goods',$this->Goods->GetDataList(array('goods_status'=>1)));
        return view();
    }

    //发布分享
    public function release($ids='',$is_share=0)
    {
        $this->assign('goods',$this->Goods->GetDataList(array('goods_id'=>array('in',$ids))));
        $this->assign('ids',$ids);
    	return view();
    }

    //分享商品
    public function Share($ids,$type)
    {
    	if(session::get('user.user_type') != 3) return array('code'=>0,'msg'=>'您不是团长，无法分享商品');

    	$result = $this->GoodsShare->CreateData(array('share_user'=>session::get('user.user_id'),'share_goods'=>$ids,'share_type'=>$type));

    	return $result['code'] == 1 ? array('code'=>1,'msg'=>'分享成功','user_id'=>session::get('user.user_id')) : array('code'=>0,'msg'=>'分享失败');
    }

    //推广订单列表
    public function order()
    {
        $data = $this->Order->GetDataList(array('order_is_share'=>session::get('user.user_id'),'order_ispay'=>1,'order_desc'=>'商品购买'));

        foreach ($data as $key => $value) {
            $item = $this->Item->where('item_order',$value['order_id'])->select();
            $data[$key]['sumprice'] = array_sum(array_map(function($val){return $val['item_commission'];}, $item));
            if(count($item) > 1)
                $data[$key]['goods_names'] = $this->Goods->GetField(array('goods_id'=>$item[0]['item_goods']),'goods_name').'等'.count($item).'件商品';
            else
                $data[$key]['goods_names'] = $this->Goods->GetField(array('goods_id'=>$item[0]['item_goods']),'goods_name');
        }

        // $order = $this->GoodsShare->GetDataList(array('share_user'=>$));


        $this->assign('order',$data);
        return view();
    }

    //收益列表
    public function income($Interval=0,$order='')
    {
        $time2 = time();

        switch ($Interval) {
            case 1:
                $time1 = strtotime("-1 month");
                break;
            case 2:
                $time1 = strtotime("-1 week");
                break;
            default:
                $time1 = 0;
                break;
        } 

        $data = $this->Order
                -> alias('o')
                -> field('o.order_sn,o.order_paytime,sum(i.item_commission) as income')
                -> join('__ORDER_ITEM__ i','o.order_id = i.item_order')
                -> where(array('o.order_is_share'=>session::get('user.user_id'),'o.order_desc'=>'商品购买','o.order_ispay'=>1))
                -> where('o.order_time','between',"$time1,$time2")
                -> group('o.order_sn')
                -> select();

        $this->assign('order',$data);
        $this->assign('sum',array_sum(array_map(function($val){return $val['income'];}, $data)));
        return view();
    }
}
