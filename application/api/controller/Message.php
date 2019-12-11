<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\User as User;
use app\admin\model\Message as MessageModel;

/**
 * 接口主页方法
 */

class Message extends Base
{
	private $User;
	private $Message;

	//构造函数
	public function __construct()
	{
		$this->User = new User();
		$this->Message = new MessageModel();

		parent::__construct();
	}

	//获取消息列表
	public function GetMessageList($unionid='')
	{
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));

		$message = db('message')->where(array('message_user'=>array('in',[0,$user['user_id']]),'message_isdel'=>0))->order('message_show asc,message_id desc')->select();
		//$message = $this->Message->GetDataList(array('message_user'=>array('in',[0,$user['user_id']]),'message_isdel'=>0));

		foreach ($message as $key => $value) {
			$message[$key]['message_icon'] = session::get('config.website_indexurl').$value['message_icon'];

			$message[$key]['message_time'] = fromTime($value['message_time'],'m-d');

			$message[$key]['message_text'] = strlen($value['message_text']) > 50 ? $this->remroverHtmlTag($value['message_text']) : $value['message_text'];
		}

		return json_encode(array('code'=>1,'msg'=>'获取成功','message'=>$message));
	}

	//获取消息详情
	public function GetMessageInfo($message_id)
	{
		$this->Message->UpdateData(array('message_id'=>$message_id,'message_show'=>1));

		return json_encode(array('code'=>1,'msg'=>'获取成功','data'=>$this->Message->GetDataInfoById($message_id)));

	}

	//删除消息
	public function RemoveMessage($message_id)
	{
		return json_encode($this->Message->UpdateData(array('message_id'=>$message_id,'message_isdel'=>1)));
	}


	//截取字符串
	public function remroverHtmlTag($str,$lenght=50)
	{
		$str = strip_tags($str);
		$pattern = '/\s/';
		$content = preg_replace($pattern, '', $str);      
		$seodata = mb_substr($content, 0, $lenght);
		return $seodata;
	}
}