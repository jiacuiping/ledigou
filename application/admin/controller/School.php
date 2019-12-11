<?php 

namespace app\admin\controller;

use app\admin\model\School as SchoolModel;
/**
 * 后台主页（Justin：2019-03-12）

 *	ControllerList
 */

class School extends LoginBase
{
    private $School;

	public function __construct()
	{
		parent::__construct();
        $this->School = new SchoolModel;
	}

	//用户列表
	public function index()
	{
        $map = [];

        $condition['name'] = $condition['campus'] = '';

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['school_name'] = ['like',"%" . $name . "%"];
        }

        $campus = input('param.campus');
        if($campus && $campus != ''){
        	$condition['campus'] = $campus;
            $map['school_campus'] = ['like',"%" . $campus . "%"];
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->School->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->School->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            
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
        $this->assign('province',db('area')->where('pid',0)->select());
        return request()->isPost() ? $this->School->CreateData(input('post.')) : view();
    }

    //修改数据
    public function update($id = 0)
    {
        $this->assign('cityinfo',$this->GetSchoolPCA($id));
        $this->assign('data',$this->School->GetOneDataById($id));
        return request()->isPost() ? $this->School->UpdateData(input('post.')) : view();
    }

    //删除数据
    public function delete($id)
    {
        return $this->School->DeleteData($id);
    }

    //获取下级城市列表
    public function selectCity($adcode)
    {
        $thisinfo = db('area')->where('id',$adcode)->find();

        if(!$thisinfo) return json_encode(array('code'=>0,'msg'=>'地区不存在'));

        $citys = db('area')->where('pid',$thisinfo['id'])->select();

        return array('code'=>1,'msg'=>'获取成功','citys'=>$citys,'level'=>$thisinfo['level']);
    }

    //获取学校省市区
    public function GetSchoolPCA($id)
    {
        $school = $this->School->GetOneDataById($id);

        $result['province'] = db('area')->where('pid',0)->select();

        $citys = $this->selectCity($school['school_province']);

        $result['citys'] = $citys['code'] == 1 ? $citys['citys'] : array();

        $areas = $this->selectCity($school['school_city']);

        $result['areas'] = $areas['code'] == 1 ? $areas['citys'] : array();

        return $result;
    }
}