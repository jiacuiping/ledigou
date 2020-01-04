<?php 

namespace app\index\controller;

use think\Cookie;
use think\Session;
use think\request;
use app\index\controller\LoginBase;
use app\admin\model\User;
use app\admin\model\School;

/**
 * 前台基础方法（Justin：2019-03-12）
 */

class Login extends LoginBase
{
	public $User;
	public $School;

	public function __construct()
	{
		parent::__construct();
		$this->User = new User();
		$this->School = new School();
	}

	//申请骑手或团长
	public function Registered($type='2')
	{
        $type = input('param.type') ? input('param.type') : 2;
        Cookie::set('type',$type);
		if(request()->isPost()){

			$data = input('post.');

			//字段验证
			if($data['user_name'] == '') return array('code'=>0,'msg'=>'姓名不能为空');
			if($data['user_school'] == 0) return array('code'=>0,'msg'=>'请选择学校');
			if(!CheckMobile($data['user_mobile'])) return array('code'=>0,'msg'=>'请输入正确的手机号码');
			$mobileInfo = $this->User->GetOneData(array('user_mobile'=>$data['user_mobile']));
			if($mobileInfo['user_type'] == 4 || $mobileInfo['user_type'] == $type) return array('code'=>0,'msg'=>'该手机号已注册');
			if(!CheckIdCard($data['user_idcard'])) return array('code'=>0,'msg'=>'请输入正确的身份证号');
			$idCardInfo = $this->User->GetOneData(array('user_idcard'=>$data['user_idcard']));
			if($idCardInfo['user_type'] == 4 || $idCardInfo['user_type'] == $type) return array('code'=>0,'msg'=>'该身份证已注册');

			$userresult = $this->User->UpdateData($data);

			if($userresult['code']){

				if($data['address_info'] != ''){
					$address = array(
						'address_user'		=> $data['user_id'],
						'address_school'	=> $data['user_school'],
						'address_info'		=> $data['address_info'],
						'address_time'		=> time()
					);
					db('address')->insert($address);
				}
                $this->redirect(url('user/mess',['mess'=>"申请信息已提交，等待后台审核"]));
//				return array('code'=>1,'msg'=>'申请信息已提交，等待后台审核');
			}else
				return array('code'=>0,'msg'=>$userresult['msg']);
			
		}else{
			if($type != 2 && $type != 3) return json_encode(array('code'=>0,'msg'=>'参数错误'));

			$user = $this->User->GetOneData(array('user_openid'=>session::get('user.user_openid')));

            if(!$user) $this->redirect('Wechat/Login', ['type' => $type]);
			//if($user['user_type'] == $type) return json_encode(array('code'=>0,'msg'=>'您已提交申请'));
            session::set('user',$user);

            // 判断用户状态
            if($user['user_status'] != 1) {
                $this->redirect(url('user/mess',['mess'=>"您的账号被禁用，请联系管理员"]));
            }

            // 如果是骑手
            if (($user['user_type'] == 2 && $type == 2) || ($user['user_type'] == 4 && $type == 2)) {
                // 判断审核状态
                if($user['user_review'] != 1) {
                    $this->redirect(url('user/mess',['mess'=>"您的账号还未通过审核，请耐心等候"]));//分享商品
                }
                $this->redirect(url('user/index'));

            } else if (($user['user_type'] == 3 && $type == 3) || ($user['user_type'] == 4 && $type == 3)) { // 团长

                // 判断审核状态
                if($user['user_review_head'] != 1) {
                    $this->redirect(url('user/mess',['mess'=>"您的账号还未通过审核，请耐心等候"]));//分享商品
                }
                $this->redirect(url('head/goodslist'));

            } else {
                $this->assign('schools',$this->School->GetDataList(array('school_status'=>1)));
                $this->assign('name',$type == 2 ? '骑手' : '团长');
                $this->assign('type',$type);
                $this->assign('user_id',$user['user_id']);
                return $this->fetch('login/registered');
            }
		}
	}

    public function user($user, $type = 2) {
        // 判断用户状态
        if($user['user_status'] != 1) {
            $this->redirect(url('user/mess',['mess'=>"您的账号被禁用，请联系管理员"]));
        }

        // 如果是骑手
        if (($user['user_type'] == 2 && $type == 2) || ($user['user_type'] == 4 && $type == 2)) {
            // 判断审核状态
            if($user['user_review'] != 1) {
                $this->redirect(url('user/mess',['mess'=>"您的账号还未通过审核，请耐心等候"]));//分享商品
            }
            $this->redirect(url('user/index'));

        } else if (($user['user_type'] == 3 && $type == 3) || ($user['user_type'] == 4 && $type == 3)) { // 团长

            // 判断审核状态
            if($user['user_review_head'] != 1) {
                $this->redirect(url('user/mess',['mess'=>"您的账号还未通过审核，请耐心等候"]));//分享商品
            }
            $this->redirect(url('head/goodslist'));

        } else {
//            $this->redirect(url('user/mess',['mess'=>"错误账号"]));
            $this->redirect(url('login/Registered'));
        }
    }
}