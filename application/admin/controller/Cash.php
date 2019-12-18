<?php 

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\admin\model\Cash as CashModel;

class Cash extends LoginBase
{
    private $User;
    private $Cash;

	public function __construct()
	{
		parent::__construct();
        $this->User = new UserModel;
        $this->Cash = new CashModel;
	}

    // 提现申请记录
    public function index()
    {
        $map = [];
        $condition = [];

        $user = input('param.user');
        if($user && $user !== ""){
            $condition['user'] = $user;
            $map['cash_user'] = $user;
        }

        $type = input('param.cash_type');
        if($type && $type !== -1){
            $condition['cash_type'] = $type;
            $map['cash_type'] = $type;
        }

        $status = input('param.cash_status');
        if($status && $status !== -1){
            $condition['cash_status'] = $status;
            $map['cash_status'] = $status;
        }

        $cashTime = input('param.cash_time');
        if($cashTime && $cashTime !== ""){
            $condition['cash_time'] = $cashTime;
            $time = explode(' , ',$cashTime);
            $map['cash_time'] = array('between',[strtotime($time[0]),strtotime($time[1])]);
        }

        $Nowpage 	= input('page') ? input('page') : 1;
        $limits  	= input('limit') ? input('limit') : 15;
        $count 		= $this->Cash->GetCount($map);
        $allpage 	= intval(ceil($count / $limits));
        $data 		= $this->Cash->GetListByPage($map,$Nowpage,$limits);
        $users      = $this->User->GetDataList(array('user_status'=>1));

        foreach ($data as $key => $value) {
            $data[$key]['cash_status'] = $value['cash_status'] == 0 ? '未审核' : '审核通过';
            $data[$key]['cash_pass_time'] = $value['cash_pass_time'] == 0 ? '未审核' : $value['cash_pass_time'];
            $data[$key]['cash_user'] = $this->User->GetField(array('user_id'=>$value['cash_user']),'user_name');
        }


        if(input('page'))
        {
            return json(
                ['code'=>0, 'msg'=>'', 'count'=>$count, 'data'=>$data,'datas'=>$map,'condition'=>$condition,'users'=>$users]
            );
        }
        $this->assign('users',$users);
        $this->assign('condition',$condition);
        return $this->fetch();
    }

}