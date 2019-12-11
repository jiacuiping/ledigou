<?php 

namespace app\admin\controller;

use think\Session;
use app\admin\model\Coupon as CouponModel;
/**
 * 优惠券 管理

 *	ControllerList
 */

class Coupon extends LoginBase
{
    private $Model;

    /**
     * 构造函数
     **/
	public function __construct()
	{
		parent::__construct();
        $this->Model = new CouponModel;
        $this->assign('modeltext','优惠券');
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
        	switch ($value['coupon_type']) {
        		case '2':
        			$data[$key]['coupon_type'] = '折扣优惠券';
        			break;
        		case '3':
        			$data[$key]['coupon_type'] = '配送费优惠券';
        			break;
        		default:
        			$data[$key]['coupon_type'] = '满减优惠券';
        			break;
        	}

        	$data[$key]['coupon_repeat'] = $value['coupon_repeat'] == 1 ? '允许' : '不允许';
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


    /**
     * 添加数据
     **/
    public function create()
    {
        return request()->isPost() ? $this->Model->CreateData(input('post.')) : view();
    }


    /**
     * 修改数据
     * @param int   $id     主键
     **/
    public function update($id = 0)
    {
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

        $update['coupon_id'] = $id;
        $update['coupon_status'] = $data['coupon_status'] == 1 ? 0 : 1;

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
}