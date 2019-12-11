<?php 

namespace app\admin\controller;

use think\Session;
use app\admin\model\Prize as PrizeModel;
use app\admin\model\Coupon as CouponModel;
/**
 * 大转盘奖品 管理

 *	ControllerList
 */

class Prize extends LoginBase
{
    private $Model;

    /**
     * 构造函数
     **/
	public function __construct()
	{
		parent::__construct();
        $this->Model = new PrizeModel;
        $this->Coupon = new CouponModel;
        $this->assign('modeltext','奖品');
        $this->assign('freight',session::get('config.website_freight'));
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
        $condition['coupon'] = 0;

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['prize_name'] = ['like',"%" . $name . "%"];
        }

        $coupon = input('param.coupon');
        if(isset($coupon) && $coupon != 0){
        	$condition['coupon'] = $coupon;
            $map['prize_coupon'] = $coupon;
        }

        //查询数据
        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Model->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Model->GetListByPage($map,$Nowpage,$limits);

        // 关联的优惠券
        $coupon     = $this->Coupon->GetDataList(array('coupon_status'=>1));

        foreach ($data as $key => $value) {
            $data[$key]['prize_coupon'] = $this->Coupon->GetField(array('coupon_id'=>$value['prize_coupon']),'coupon_name');
        }

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition, 'coupons'=>$coupon]
            );
        }

        $this->assign('coupons',$coupon);
        $this->assign('condition',$condition);
        return $this->fetch();	
	}


    /**
     * 添加数据
     **/
    public function create()
    {
    	$this->assign('coupons',$this->Coupon->GetDataList(array('coupon_status'=>1)));
        return request()->isPost() ? $this->Model->CreateData(input('post.')) : view();
    }


    /**
     * 修改数据
     * @param int   $id     主键
     **/
    public function update($id = 0)
    {
    	$this->assign('coupons',$this->Coupon->GetDataList(array('coupon_status'=>1)));
        $this->assign('data',$this->Model->GetOneDataById($id));
        return request()->isPost() ? $this->Model->UpdateData(input('post.')) : view();
    }


    /**
     * 更改状态
     * @param int   $id     主键
     **/
    public function change($id)
    {
        $data = $this->Model->GetOneDataById($id);

        if(!$data) return array('code'=>0,'msg'=>'数据不存在');

        $update['prize_id'] = $id;
        $update['prize_status'] = $data['prize_status'] == 1 ? 0 : 1;

        return $this->Model->UpdateData($update);
    }


    /**
     * 删除数据
     * @param int   $id     主键
     **/
    public function delete($id)
    {
        return $this->Model->DeleteData($id);
    }

	/**
     * 获取除当前奖品以外所有效奖品的中奖概率总和
     * @param int   $id     主键
     **/
    public function getSumProbability($id)
    {
    	$where['prize_id'] = array('neq',$id);
        $sum = $this->Model->GetPrizeSum($where);
        return array('code'=>1,'msg'=>'获取成功', 'data'=>$sum);
    }
}