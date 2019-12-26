<?php 

namespace app\api\controller;

use think\Session;
use think\request;
use app\api\controller\Base;
//用户表模型
use app\admin\model\User;
/**
 * 授权登陆方法
 */

class Wechat extends Base
{
	private $appid;
	private $appsecret;
	private $User;

	//构造函数
	public function __construct()
	{
		parent::__construct();
		$this->User = new User();
		$this->appid = 'wxf8d6a2af2002f997';
		$this->appsecret = 'c92650db267d39ae5c4c0d603177883e';

	}

	//授权登陆
	public function Login()
	{
		ob_start();//打开输出控制缓冲
		$url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=http://dxc.gqwlcm.com/api/wechat/getcode&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
		header('Location:'.$url);exit();
	}


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
	    	if($user['user_status'] != 1)
	    		$this->error('此用户已被禁用');//被禁用 跳转到被禁用页面
	    	else{
	    		session::set('user',$user);
	    		$this->redirect(url('index/index'));//正常状态 跳转到首页
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
			'user_avatar'	=> $data['headimgurl'],
			'user_openid'	=> $data['openid']
		);

		$userresult = $this->User->CreateData($userinfo);

		$wallet = array(
			'wallet_user'	=> $this->User->getLastInsID(),
			'wallet_money'	=> 0,
			'wallet_time'	=> time()
		);

		$walletresult = db('wallet')->insert($wallet);

		if($userresult['code'] == 1 && $walletresult){
			session::set('user',$userinfo);
			$this->redirect(url('index/index'));
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
	    return json_decode($json,true);
	}
}