<?php
namespace app\index\controller;

use think\Session;
use think\request;
use app\index\controller\Base;
//用户表模型
use app\admin\model\User;
use app\admin\model\WechatConfig;

class WechatShare extends Base
{
	private $WechatConfig;

	public function __construct()
	{
		parent::__construct();

		$this->WechatConfig = new WechatConfig();
	}


	//获取处理后的签名
	public function GetSigna($ids='')
	{
		$config = $this->WechatConfig->GetConfig();

		$token = $config['wechat_access_token'] == '' || $config['wechat_token_time'] < time() ? $this->GetToken() : $config['wechat_access_token'];

		$ticket = $config['wechat_jsapi_ticket'] == '' || $config['wechat_ticket_time'] < time() ? $this->GetTicket($token) : $config['wechat_jsapi_ticket'];

		$url = "http://dxc.gqwlcm.com/index/head/release/ids/".$ids;

		//"//dxc.gqwlcm.com/index/head/shareList/head/".session::get('user.user_id');

		$timestamp = time();

		$nonceStr = $this->generateRandomString();

	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序 j -> n -> t -> u
	    $string = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=".$url;

	    $signature = sha1($string);
	    $signPackage = array (
	        "appId" => $config['wechat_appid'],
	        "nonceStr" => $nonceStr,
	        "timestamp" => $timestamp,
	        "url" => $url,
	        "signature" => $signature,
	        "rawString" => $string,
	        "ticket" => $ticket,
	        "token" => $token
	    );
	    dump($signPackage);die;
	    // 提供数据给前端
	    return array('code' => 1, 'data' => $signPackage);
	}

	//获取token
	public function GetToken()
	{
		$config = $this->WechatConfig->GetConfig();

		if($config['wechat_access_token'] == '' || $config['wechat_token_time'] < time()){

			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$config['wechat_appid']."&secret=".$config['wechat_appSecret'];

			$data = $this->curl($url);

			if(isset($data['access_token'])){
				$this->WechatConfig->UpdateConfig(array('wechat_access_token'=>$data['access_token'],'wechat_token_time'=>time()+$data['expires_in']));
				return $data['access_token'];
			}else
				return '';

		}else
			return $config['wechat_access_token'];
	}

	//获取签名
	public function GetTicket($token='')
	{
		$config = $this->WechatConfig->GetConfig();

		if($token == '')
			$token = $config['wechat_access_token'] == '' || $config['wechat_token_time'] < time() ? $this->GetToken() : $config['wechat_access_token'];

		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$token."&type=jsapi";

		$data = $this->curl($url);

		if($data['errmsg'] == 'ok'){
			$this->WechatConfig->UpdateConfig(array('wechat_jsapi_ticket'=>$data['ticket'],'wechat_ticket_time'=>time()+$data['expires_in']));
			return $data['ticket'];
		}else
			return '';
	}

	//随机字符串
	public function generateRandomString($length = 16) { 
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	    $randomString = ''; 
	    for ($i = 0; $i < $length; $i++) { 
	        $randomString .= $characters[rand(0, strlen($characters) - 1)]; 
	    } 
	    return $randomString; 
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