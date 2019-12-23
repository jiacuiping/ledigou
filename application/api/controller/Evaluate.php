<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\User;
use app\admin\model\Goods;
use app\admin\model\Order;
use app\admin\model\OrderItem;
use app\admin\model\Evaluate as EvaluateModel;

/**
 * 接口主页方法
 */

class Evaluate extends Base
{
	private $Item;
	private $User;
	private $Goods;
	private $Order;
	private $Evaluate;

	//构造函数
	public function __construct()
	{
		$this->User = new User();
		$this->Goods = new Goods();
		$this->Order = new Order();
		$this->Item = new OrderItem();
		$this->Evaluate = new EvaluateModel();

		parent::__construct();
	}

	//获取商品评价列表
	public function GetEvaluateList($goods_id,$is_image=false,$star=0,$order='eval_time desc',$page=1,$limit=10)
	{
		$ids = $this->Item->GetColumn(array('item_goods'=>$goods_id),'item_id');

		$where['eval_item'] = array('in',implode(',',$ids));

		if($is_image != false) $where['eval_images'] = array('=',"not null");

		if($star != 0) $where['eval_star'] = $star;

		$data = $this->Evaluate->GetListByPage($where,$page,$limit,$order);

		foreach ($data as $key => $value) {

			$data[$key]['user'] = $this->User->GetOneData(array('user_id'=>$value['eval_user']));

			if($value['eval_images'] != ''){

				$images = str_replace(',',','.session::get('config.website_indexurl'),$value['eval_images']);

				$data[$key]['eval_images'] = explode(',',session::get('config.website_indexurl').$images);

			}else
				$data[$key]['eval_images'] = array();
		}

		if(empty($data)) return json_encode(array('code'=>0,'msg'=>'暂无评价信息'));

		return json_encode(array('code'=>1,'msg'=>'获取成功','Evaluate'=>$data));
	}

	//获取用户评价列表
	public function GetUserEvaluateList($unionid)
	{
		$user = $this->User->GetOneData(array('user_unionid'=>$unionid));

		if(!$user) return json_encode(array('code'=>0,'msg'=>'用户不存在'));

		$data = $this->Evaluate->GetDataList(array('eval_user'=>$user['user_id']));

		foreach ($data as $key => $value) {

			$data[$key]['goods_name'] = $this->Goods->GetField(array('goods_id'=>$this->Item->GetField(array('item_id'=>$value['eval_item']),'item_goods')),'goods_name');

			if($value['eval_images'] != ''){

				$images = str_replace(',',','.session::get('config.website_indexurl'),$value['eval_images']);

				$data[$key]['eval_images'] = explode(',',session::get('config.website_indexurl').$images);

			}else
				$data[$key]['eval_images'] = array();
		}

		return json_encode(array('code'=>1,'msg'=>'获取成功','data'=>$data));
	}


	//获取评价详情
	public function GetEvaluateInfo($eval_id=0)
	{

		if($eval_id == 0) return json_encode(array('code'=>0,'msg'=>'参数不正确'));

		$eval = $this->Evaluate->GetOneData(array('eval_id',$eval_id));

		if($eval){
			return json_encode(array('code'=>1,'msg'=>'获取成功','eval'=>$eval));
		}else
			return json_encode(array('code'=>0,'msg'=>'商品不存在'));
	}

	// 提交评价
	public function CommitEvaluate($order_sn = '', $eval_text = '')
    {
        // 判断参数
        if($order_sn == '') return json_encode(array('code'=>0,'msg'=>'参数不正确'));
        if($eval_text == '') return json_encode(array('code'=>0,'msg'=>'内容不能为空'));

        // 订单信息
        $orderInfo = $this->Order->GetOneData(['order_sn' => $order_sn]);
        if(!$orderInfo) {
            return json_encode(array('code'=>0,'msg'=>'订单不存在'));
        }

        $orderItem = $this->Item->GetOneDataByOrderId($orderInfo['order_id']);
        if(!$orderItem) {
            return json_encode(array('code'=>0,'msg'=>'订单不存在'));
        }

        // 存储评价
        $evaluate = [
            'eval_user' => $orderInfo['order_user'],
            'eval_item' => $orderItem['item_id'],
            'eval_text' => $eval_text,
            'eval_star' => 5,
            'eval_images' => '',
            'eval_is_incognito' => 0,
            'eval_time' => time(),
        ];

        $result = $this->Evaluate->CreateData($evaluate);

        if($result['code']) {
            // 评价成功，修改订单状态
            $orderUpdate = [
                'order_id' => $orderInfo['order_id'],
                'order_status' => 40,
            ];
            $orderRes= $this->Order->UpdateData($orderUpdate);
            return json_encode(array('code'=>1,'msg'=>'提交成功', 'order_id' => $orderInfo['order_id']));
        } else {
            return json_encode(array('code'=>0,'msg'=>'提交失败'));
        }
    }
}