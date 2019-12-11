<?php 

namespace app\admin\controller;

use think\session;
use app\admin\model\Travelcard as TravelcardModel;
use app\admin\model\Agent as AgentModel;

/**
 * 后台主页（Justin：2019-03-12）
 */

class Agent extends LoginBase
{
	private $Travelcard;
	private $Agent;

	public function __construct()
	{
		parent::__construct();
		$this->Travelcard = new TravelcardModel();
		$this->Agent = new AgentModel();
	}

	public function index()
	{
        $map = [];

        $condition['name'] = $condition['mobile'] = '';
        $condition['status'] = -1;

        $name = input('param.name');
        if($name && $name !== ""){
        	$condition['name'] = $name;
            $map['agent_name'] = ['like',"%" . $name . "%"];
        }

        $mobile = input('param.mobile');
        if($mobile && $mobile != 0){
        	$condition['mobile'] = $mobile;
            $map['agent_mobile'] = ['like',"%" . $mobile . "%"];
        }

        $status = input('param.status');
        if(isset($status) && $status != -1){
        	$condition['status'] = $status;
           	$map['agent_status'] = $status;
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Agent->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Agent->GetListByPage($map,$Nowpage,$limits);

        foreach ($data as $key => $value) {
            $data[$key]['agent_time'] = date('Y-m-d H:i',$value['agent_time']);
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

    //创建代理商
    public function createagent()
    {
        if(request()->isPost()){
            
            $data = input('post.');

            $password = EncryptionPassword($data['agent_pass']);
            
            $data['agent_pass'] = $password['password'];
            $data['agent_pass_str'] = $password['login_encrypt_str'];
			$data['agent_time'] = time();
   
          	return db('agent')->insert($data) ? array('code'=>1,'msg'=>'代理商创建成功') : array('code'=>0,'msg'=>'代理商创建失败');
            //return $this->Agent->CreateData($data) ? array('code'=>1,'msg'=>'代理商创建成功') : array('code'=>0,'msg'=>'代理商创建失败');

        }else
            return view();
    }

    //编辑代理商
    public function update($id=0)
    {
        if(request()->isPost()){
            
            $data = input('post.');

            if($data['agent_pass'] != ''){

                $password = EncryptionPassword($data['agent_pass']);
                
                $data['agent_pass'] = $password['password'];
                $data['agent_pass_str'] = $password['login_encrypt_str'];

            }else
                unset($data['agent_pass']);

            return $this->Agent->UpdateData($data) ? array('code'=>1,'msg'=>'修改成功') : array('code'=>0,'msg'=>'修改失败');

        }else{

            $data = $this->Agent->GetOneData(array('agent_id'=>$id));
            $this->assign('info',$data);
            return view();

        }
    }

	//删除代理商
	public function delete($id)
	{
		return $this->Agent->DeleteData($id);
	}
}