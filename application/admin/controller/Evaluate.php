<?php 

namespace app\admin\controller;

use think\Session;
use app\admin\model\User;
use app\admin\model\Goods;
use app\admin\model\OrderItem;
use app\admin\model\Evaluate as EvaluateModel;
/**
 * 评价 管理

 *	ControllerList
 */

class Evaluate extends LoginBase
{
    private $User;
    private $Model;
    private $Goods;
    private $OrderItem;

    /**
     * 构造函数
     **/
	public function __construct()
	{
		parent::__construct();
        $this->User = new User;
        $this->Goods = new Goods;
        $this->OrderItem = new OrderItem;
        $this->Model = new EvaluateModel;
        $this->assign('modeltext','评价');
	}

    /**
     * 数据列表
     * @param int   $page           页数
     * @param int   $limit          条数
     **/
	public function index()
	{
        $map = [];

        //数据筛选
        $condition['name'] = '';
        $condition['status'] = -1;

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['coupon_name'] = ['like',"%" . $name . "%"];
        }

        $status = input('param.status');
        if(isset($status) && $status != "-1"){
        	$condition['status'] = $status;
            $map['coupon_status'] = $status;
        }

        //查询数据
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Model->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Model->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            //循环操作
            $data[$key] = $this->GetData($value);
        }

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition]
            );
        }
        $this->assign('condition',$condition);
        return $this->fetch();	
	}

    //查看评价
    public function show($id)
    {
        //评价信息
        $evaluate = $this->Model->GetOneDataById($id);
        //订单商品信息
        $item = $this->OrderItem->GetOneDataById($evaluate['eval_item']);
        //用户信息
        $user = $this->User->GetOneDataById($evaluate['eval_user']);
        //商品信息
        $goods = $this->Goods->GetOneDataById($item['item_goods']);

        $this->assign('item',$item);
        $this->assign('user',$user);
        $this->assign('goods',$goods);
        $this->assign('evaluate',$evaluate);
        return view();
    }

    /**
     * 删除数据
     * @param int   $id     主键
     **/
    public function delete($id)
    {
        return $this->Model->DeleteData($id);
    }

    //获取信息
    public function GetData($value)
    {
        $name = $this->User->GetField(array('user_id'=>$value['eval_user']),'user_name');
        $value['eval_user'] = $name ? $name : '已注销';
        $goodsId = $this->OrderItem->GetField(array('item_id'=>$value['eval_item']),'item_goods');
        if($goodsId){
            $goods = $this->Goods->GetField(array('goods_id'=>$goodsId),'goods_name');
             $value['eval_item'] = $goods ? $goods : '已下架';
        }else
            $value['eval_item'] = '已下架';
        $value['eval_is_incognito'] = $value['eval_is_incognito'] == 0 ? '匿名' : '未匿名';
        return $value;
    }

}