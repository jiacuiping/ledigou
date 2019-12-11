<?php 

namespace app\admin\controller;

use app\admin\model\Goods as GoodsModel;
use app\admin\model\Sort as SortModel;
use app\admin\model\Warehouse as WarehouseModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Goods extends LoginBase
{
    private $Warehouse;
    private $Goods;
    private $Sort;

	public function __construct()
	{
		parent::__construct();
        $this->Warehouse = new WarehouseModel;
        $this->Goods = new GoodsModel;
        $this->Sort = new SortModel;
	}

	//用户列表
	public function index()
	{
        $map = [];

        $condition['name'] = '';
        $condition['sort'] = 0;

        $name = input('param.name');
        if($name && $name !== ""){
            $condition['name'] = $name;
            $map['goods_name'] = ['like',"%" . $name . "%"];
        }
        $sort = input('param.sort');
        if($sort && $sort != 0){
        	$condition['sort'] = $sort;
            $map['goods_sort'] = $sort;
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Goods->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Goods->GetListByPage($map,$Nowpage,$limits);
        $sort       = $this->Sort->GetDataList(array('sort_status'=>1));

        foreach ($data as $key => $value) {
            $data[$key]['goods_sort'] = $this->Sort->GetField(array('sort_id'=>$value['goods_sort']),'sort_name');
        }

        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition,'sorts'=>$sort]
            );
        }
        $this->assign('sorts',$sort);
        $this->assign('condition',$condition);
        return $this->fetch();	
	}

    //添加数据
    public function create()
    {
        $this->assign('sorts',$this->Sort->GetDataList(array('sort_status'=>1)));
        $this->assign('wares',$this->Warehouse->GetDataList(array('ware_status'=>1)));
        return request()->isPost() ? $this->Goods->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('sorts',$this->Sort->GetDataList(array('sort_status'=>1)));
        $this->assign('wares',$this->Warehouse->GetDataList(array('ware_status'=>1)));
        $this->assign('data',$this->Goods->GetOneDataById($id));
        return request()->isPost() ? $this->Goods->UpdateData(input('post.')) : view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->Goods->DeleteData($id);
    }
}