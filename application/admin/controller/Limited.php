<?php 

namespace app\admin\controller;

use app\admin\model\Limited as LimitedModel;
use app\admin\model\Goods;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Limited extends LoginBase
{
    private $Rotate;
  	private $Goods;

	public function __construct()
	{
		parent::__construct();
      	$this->Goods = new Goods;
        $this->Limited = new LimitedModel;
	}

	//数据列表
	public function index()
	{
        $map = [];

        $condition['name'] = $condition['mobile'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['member_name'] = ['like',"%" . $name . "%"];
        }

        $mobile = input('param.mobile');
        if($mobile && $mobile != 0){
        	$condition['mobile'] = $mobile;
            $map['member_mobile'] = ['like',"%" . $mobile . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Limited->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Limited->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['limited_goods'] = $this->Goods->GetField(array('goods_id'=>$value['limited_goods']),'goods_name');
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

    //添加数据
    public function create()
    {
      	if(request()->isPost()){
        	
          	$data = input('post.');
          
          	if($data['times'] == '') return array('code'=>0,'msg'=>'请选择优惠起止时间');
          
          	$data['limited_stime'] = strtotime(substr($data['times'],0,10));
          	$data['limited_etime'] = strtotime(substr($data['times'],13,23));
          
          	return $this->Limited->CreateData($data);
          
        }else{
          	$this->assign('goods',$this->Goods->GetDataList(array('goods_status'=>1)));
          	return view();
        }
    }

    //修改数据
    public function update($id = 0)
    {
      	if(request()->isPost()){
        	
          	$data = input('post.');
          
          	if($data['times'] == '') return array('code'=>0,'msg'=>'请选择优惠起止时间');
          
          	$data['limited_stime'] = strtotime(substr($data['times'],0,10));
          	$data['limited_etime'] = strtotime(substr($data['times'],13,23));
          
          	return $this->Limited->UpdateData($data);
          
        }else{
          	$this->assign('goods',$this->Goods->GetDataList(array('goods_status'=>1)));
          	$this->assign('data',$this->Limited->GetOneDataById($id));
          	return view();
        }
    }

    //删除数据
    public function delete($id)
    {
        return $this->Limited->DeleteData($id);
    }
}