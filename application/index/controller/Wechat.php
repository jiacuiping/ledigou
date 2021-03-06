<?php
namespace app\index\controller;

use think\Cookie;
use think\Session;
use think\request;
use app\index\controller\Base;
//用户表模型
use app\admin\model\User;
use app\admin\model\School;
/**
 * 授权登陆方法
 */

class Wechat extends Base
{
	private $appid;
	private $appsecret;
	private $User;
	private $School;
	private $is_share = 0;
	static $time = 0;

	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->User = new User();
		$this->School = new School();
		$this->appid = 'wxf8d6a2af2002f997';
		$this->appsecret = 'c92650db267d39ae5c4c0d603177883e';

	}


	//授权登陆
	public function Login($is_share=0)
	{
        $this->is_share = $is_share == 0 ? 0 : $is_share;
        $type = Cookie::has('type') ? Cookie::get('type') : 0;
        ob_start();//打开输出控制缓冲

        // 判断有没有code，有使用code换取access_token，没有去获取code。
        if (!isset($_GET['code'])) {
            $this->get_code();
        } else {

            $code = $_GET['code'];
            // 获取网页授权access_token和用户openid
            $data = $this->get_access_token($code);
            // 获取微信用户信息
//                $userInfo = $this->get_user_info($data['access_token'],$data['openid']);
            // 存储用户
//                $this->saveUser($userInfo, $type);
//                $this->saveUser($data['access_token'],$data['openid'],$type);

            $this->redirect('Wechat/saveUser', ['token' => $data['access_token'], 'openid'=> $data['openid'], 'type' => $type]);
        }

	}

	// 获取token
	public function get_code() {
	    $redirectUri = "http://dxc.gqwlcm.com/index/wechat/login";
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=" . $redirectUri . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        header('Location:'.$url);
        exit();
    }

    // 获取access_token
    public function get_access_token($code) {
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=$code&grant_type=authorization_code";
        $data =  $this->curl($url);
        session::set('access_token',$data['access_token']);
        session::set('openid',$data['openid']);
        Cookie::set('openid',$data['openid']);
        return $data;
    }

    // 获取微信用户信息
    public function get_user_info ($token,$openid) {
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
        $data = $this->curl($url);
        if(isset($data['openid'])) {
            // 授权成功
            return $data;
        } else {
            $this->error('授权失败');
        }
    }

    // 存储用户信息，创建钱包
    public function saveUser() {

        // 参数
        $token = input('param.token');
        $openid = input('param.openid');
        $type = input('param.type');

        // 从分享链接进入
        $head = Cookie::has('head') ? Cookie::get('head') : 0;


        // 获取微信用户信息
        $data = $this->get_user_info($token,$openid);

        // 判断当前用户是否已存在
        $user = $this->User->GetOneData(array('user_openid'=>$data['openid']));

        if (!$user) {
            // 用户不存在
            // 存储用户信息
            $userInfo = [
                'user_name'		=> $data['nickname'],
                'user_parent'	=> $this->is_share,
                //'user_sex'		=> $data['sex'],
                'user_avatar'	=> $data['headimgurl'],
                'user_openid'	=> $data['openid'],
                'user_unionid'	=> $data['unionid'], 
            ];
            $saveUserRes = $this->User->CreateData($userInfo);

            // 创建用户钱包信息
            $wallet = array(
                'wallet_user'	=> $this->User->getLastInsID(),
                'wallet_money'	=> 0,
                'wallet_time'	=> time()
            );
            $walletRes = db('wallet')->insert($wallet);

            // 存储成功，跳转页面
            if($saveUserRes['code'] == 1 && $walletRes){
                Cookie::set('openid',$data['openid']);

                // 分享链接进入
                if($head) {
                    $this->redirect('Headshare/list', ['head' => $head]);//团长
                }

                if($this->is_share)
                    $this->redirect(url('head/shareList',['head'=>$this->is_share]));//分享商品
                $this->redirect(url('login/Registered'));
            } else {
                $this->redirect(url('login/Registered'));
            }

        } else {
            session::set('user',$user);
            // 分享链接进入
            if($head) {
                $this->redirect('Headshare/list', ['head' => $head]);//团长
            }

            if (($user['user_type'] == 2 && $type == 3) || ($user['user_type'] == 3 && $type == 2) || $user['user_type'] == 1){ // 普通用户  骑手申请团长 团长申请骑手

                $this->assign('schools',$this->School->GetDataList(array('school_status'=>1)));
                $this->assign('name',$type == 2 ? '骑手' : '团长');
                $this->assign('type',$type);
                $this->assign('user_id',$user['user_id']);
                return $this->fetch('login/registered');

            } else {  // 已注册
                $this->user($user, $type);
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

    // ======================================================================================
	//获取code
	public function getcode()
	{
		$code = input('code');
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appid."&secret=".$this->appsecret."&code=$code&grant_type=authorization_code";

	    $data = $this->curl($url);

	    //处理是否存在用户信息
	    //根据用户唯一标识查找是否有当前用户

	    $user = $this->User->GetOneData(array('user_unionid'=>$data['unionid']));

	    if($user){
	    	if($user['user_openid'] == '')
                $this->User->UpdateData(array('user_id'=>$user['user_id'],'user_openid'=>$data['openid']));

            if($user['user_status'] != 1)
	    		$this->error('此用户已被禁用');//被禁用 跳转到被禁用页面
	    	elseif($user['user_review'] != 1)
	    		$this->error('您未通过审核，请稍后重试！', 'user/index');//被禁用 跳转到被禁用页面
	    	else{
	    		session::set('user',$user);

	    		if($this->is_share)
	    			$this->redirect(url('head/shareList',['head'=>$this->is_share]));//分享商品

	    		if($user['user_type'] == 3)
	    			$this->redirect(url('head/goodslist'));//团长
	    		elseif($user['user_type'] == 2)
	    			$this->redirect(url('user/index'));//骑手
	    		else
	    			$this->redirect(url('login/Registered'));//未审核或者用户
	    	}
	    }else
	    	$this->refresh_token($data['refresh_token']);//如果没有当前用户则继续执行


	    $user = $this->User->GetOneData(array('user_openid'=>$data['openid']));

	    if($user){
	    	if($user['user_status'] != 1)
	    		$this->error('此用户已被禁用');//被禁用 跳转到被禁用页面
	    	elseif($user['user_review'] != 1)
	    		$this->error('您未通过审核，请稍后重试！');//被禁用 跳转到被禁用页面
	    	else{
	    		session::set('user',$user);

	    		if($this->is_share)
	    			$this->redirect(url('head/shareList',['head'=>$this->is_share]));//分享商品

	    		if($user['user_type'] == 3)
	    			$this->redirect(url('head/goodslist'));//团长
	    		elseif($user['user_type'] == 2)
	    			$this->redirect(url('user/index'));//骑手
	    		else
	    			$this->redirect(url('login/Registered'));//未审核或者用户
	    	}
	    }else
	    	$this->refresh_token($data['refresh_token']);//如果没有当前用户则继续执行
	}

	//刷新token
	public function refresh_token($refresh_token)
	{
		$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$this->appid."&grant_type=refresh_token&refresh_token=$refresh_token";
	    $data = $this->curl($url);

        $this->getuserinfo($data['access_token'],$data['openid']);
	}


	//用access_token获取用户信息
	public function getuserinfo($token,$openid)
	{

		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";

		$data = $this->curl($url);

	    //初次获取用户信息处理
		$userinfo = array(
			'user_name'		=> $data['nickname'],
			'user_parent'	=> $this->is_share,
			//'user_sex'		=> $data['sex'],
			'user_avatar'	=> $data['headimgurl'],
			'user_openid'	=> $data['openid'],
			'user_unionid'	=> $data['unionid'],
		);

		$userresult = $this->User->CreateData($userinfo);

		$wallet = array(
			'wallet_user'	=> $this->User->getLastInsID(),
			'wallet_money'	=> 0,
			'wallet_time'	=> time()
		);

		$walletresult = db('wallet')->insert($wallet);

		if($userresult['code'] == 1 && $walletresult){

//			session::set('user',$this->User->GetOneData(array('user_openid'=>$data['openid'])));

	    	if($this->is_share)
	    		$this->redirect(url('head/shareList',['head'=>$this->is_share]));//分享商品

			$this->redirect(url('login/Registered'));
		}else
			$this->error('授权失败');
	}

	//请求方法
	public function curl($url)
	{
		//初始化
	    $curl = curl_init();
	    //设置抓取的url
	    curl_setopt($curl, CURLOPT_URL,$url);
	    //设置头文件的信息作为数据流输出
	    curl_setopt($curl, CURLOPT_HEADER, 0);
	    //设置获取的信息以文件流的形式返回，而不是直接输出。
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    //设置post方式提交
	    curl_setopt($curl, CURLOPT_POST, 1);
	    //设置post数据
	    $post_data = array(
	        "username" => "coder",
	        "password" => "12345"
	        );
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
	    //执行命令
	    $json = curl_exec($curl);
	    //关闭URL请求
	    curl_close($curl);
	    $result = json_decode($json,true);
	    return $result;
	}
}