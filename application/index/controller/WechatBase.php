<?php 

namespace app\index\controller;

use think\Controller;
use think\Session;
use think\request;
use app\admin\model\Config;

/**
 * 前台基础方法（Justin：2019-03-12）
 */

class WechatBase extends Controller
{
	protected $appid = 'wxf8d6a2af2002f997';
	protected $mchid = '1545804761';
	protected $key = '78901fd7992fb7e5bc3323b4cb48ef8c';
	protected $notify_url = 'http://dxc.gqwlcm.com/index/wechat_pay/Callback';

	public function __construct()
	{
		parent::__construct();

		//检测配置文件
		if(!session::has('config')) 
		{
			$config = new Config();
			session::set('config',$config->GetConfig());
		}
	}


	//获取签名
	public function GetSign($data)
	{
		$data = $this->ASCII($data);

        return strtoupper(md5($data.'&key='.$this->key));
	}

	//随机字符串
	public function nonceStr($length = 16) { 
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
	    $randomString = ''; 
	    for ($i = 0; $i < $length; $i++) { 
	        $randomString .= $characters[rand(0, strlen($characters) - 1)]; 
	    } 
	    return $randomString; 
	}

	/**
	 * 数组转xml
	 * @throws WxPayException
	**/
    public function arrayToXml($data = [])
    {
        if (!is_array($data) || count($data) == 0) {
            throw new WxPayException('数组数据异常!');
        }
        $xml = '<xml>';
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            } else {
                $xml .= '<' . $key . '>' . $val . '</' . $key . '>';
            }
        }
        $xml .= "</xml>";

        return $xml;
    }

	/**
	 * xml转数组
	 * @throws WxPayException
	**/
	public function xmlToArray($xml,$isfile=false){   
		//禁止引用外部xml实体
		libxml_disable_entity_loader(true);
		if($isfile){
			if(!file_exists($xml)) return false;
			$xmlstr = file_get_contents($xml);
		}else{
			$xmlstr = $xml;
		}

		$result= json_decode(json_encode(simplexml_load_string($xmlstr, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
		return $result;
	}

	//数组ASCII排序
	public function ASCII($params = array()){
	    if(!empty($params)){
	       $p =  ksort($params);
	       if($p){
	           $str = '';
	           foreach ($params as $k=>$val){
	               $str .= $k .'=' . $val . '&';
	           }
	           $strs = rtrim($str, '&');
	           return $strs;
	       }
	    }
	    return '参数错误';
	}

	//请求方法
	public function curl($url,$data)
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
	    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	    //执行命令
	    $json = curl_exec($curl);
	    //关闭URL请求
	    curl_close($curl);

	    return $json;
	}
}