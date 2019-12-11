<?php 

namespace app\admin\controller;

use app\admin\model\Sort as SortModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Sort extends LoginBase
{
    private $Sort;

	public function __construct()
	{
		parent::__construct();
        $this->Sort = new SortModel;
	}

	//用户列表
	public function index()
	{
        $map = [];

        $condition['name'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['sort_name'] = ['like',"%" . $name . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Sort->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Sort->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['sort_icon'] = "<a target='_blank' href=".$value['sort_icon']."><img src=".$value['sort_icon']." style='height:28px'></a>";
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
        return request()->isPost() ? $this->Sort->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('data',$this->Sort->GetOneDataById($id));
        return request()->isPost() ? $this->Sort->UpdateData(input('post.')) : view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->Sort->DeleteData($id);
    }
}