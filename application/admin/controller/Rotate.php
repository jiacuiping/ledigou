<?php 

namespace app\admin\controller;

use app\admin\model\Rotate as RotateModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class Rotate extends LoginBase
{
    private $Rotate;

	public function __construct()
	{
		parent::__construct();
        $this->Rotate = new RotateModel;
	}

	//用户列表
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
        $count 		= $this->Rotate->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Rotate->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['rotate_img'] = "<img src=".$value['rotate_img']." class='imglist'>";
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
        return request()->isPost() ? $this->Rotate->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('data',$this->Rotate->GetOneDataById($id));
        return request()->isPost() ? $this->Rotate->UpdateData(input('post.')) : view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->Rotate->DeleteData($id);
    }
}