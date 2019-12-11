<?php 

namespace app\admin\controller;

use app\admin\model\Warehouse as WarehouseModel;
use app\admin\model\School as SchoolModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Warehouse extends LoginBase
{
    private $Warehouse;
    private $School;

	public function __construct()
	{
		parent::__construct();
        $this->Warehouse = new WarehouseModel;
        $this->School = new SchoolModel;
	}

	//用户列表
	public function index()
	{
        $map = [];

        $condition['name'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['ware_name'] = ['like',"%" . $name . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Warehouse->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Warehouse->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['ware_school'] = $this->School->GetField(array('school_id'=>$value['ware_school']),'school_name');
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
        $this->assign('schools',$this->School->GetDataList(array('school_status'=>1)));
        return request()->isPost() ? $this->Warehouse->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('schools',$this->School->GetDataList(array('school_status'=>1)));
        $this->assign('data',$this->Warehouse->GetOneDataById($id));
        return request()->isPost() ? $this->Warehouse->UpdateData(input('post.')) : view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->Warehouse->DeleteData($id);
    }
}