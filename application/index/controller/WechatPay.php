<?php
namespace app\index\controller;

use think\Session;
use think\request;
use app\index\controller\WechatBase;
//用户表模型
use app\admin\model\User;
use app\admin\model\Task;
use app\admin\model\Goods;
use app\admin\model\Order;
use app\admin\model\Limited;
use app\admin\model\Message;
use app\admin\model\MessageModel;
use app\admin\model\OrderItem;
use app\admin\model\WechatConfig;


class WechatPay extends WechatBase
{
    private $sign;
    private $User;
    private $Task;
    private $Goods;
    private $Order;
    private $Limited;
    private $Message;
    private $MessageModel;
    private $OrderItem;
    private $WechatConfig;


    public function __construct()
    {
        parent::__construct();

        $this->User = new User();
        $this->Task = new Task();
        $this->Goods = new Goods();
        $this->Limited = new Limited();
        $this->Message = new Message();
        $this->MessageModel = new MessageModel();
        $this->Order = new Order();
        $this->OrderItem = new OrderItem();
        $this->timestamp = time();
        $this->nonce_str = $this->nonceStr(32);
        $this->sign_type = 'MD5';
        $this->resign = '';
    }


    //支付
    public function PayNow($order_sn)
    {
        //获取订单详情
        $order = $this->Order->GetOneData(array('order_sn'=>$order_sn));

        //验证订单状态
        if(!$order) return array('code'=>0,'msg'=>'订单不存在');
        if($order['order_ispay'] == 1) return array('code'=>0,'msg'=>'订单已支付');
        if($order['order_status'] > 9) return array('code'=>0,'msg'=>'订单已支付');
        // if(strtotime($order['order_time']) < time()-180) return array('code'=>0,'msg'=>'订单已失效');

        return $this->unifiedorder($order['order_money']*100,'商品购买',$this->User->GetField(array('user_id'=>$order['order_user']),'user_openid'));
    }

    //统一下单
    public function unifiedorder($total_fee=0, $body='', $openid='')
    {
        $data = [
            'appid' => $this->appid,
            'body' => $body,
            'mch_id' => $this->mchid,
            'nonce_str' => $this->nonce_str,
            'notify_url' => $this->notify_url,
            'openid' => $openid,
            'out_trade_no' => time() . rand(11, 99),
            'sign_type' => $this->sign_type,
            'spbill_create_ip' => Request::instance()->ip(),
            'total_fee' => $total_fee * 1,
            'trade_type' => 'JSAPI',
        ];

        $data['sign'] = $this->GetSign($data);

        $xml = $this->arrayToXml($data);

        $result = $this->curl("https://api.mch.weixin.qq.com/pay/unifiedorder",$xml);

        $return = $this->xmlToArray($result);

        $this->package = 'prepay_id=' . $return['prepay_id'];
        $this->renCreatesign();
        $returns = [
            'code'  => 1,
            'appId' => $this->appid,
            'timeStamp' => (string)$this->timestamp,
            'nonceStr' => $this->nonce_str,
            'package' => $this->package,
            'signType' => $this->sign_type,
            'paySign' => $this->resign,
        ];

        return $returns;
    }

    //二次签名
    public function renCreatesign()
    {
        $build_data = [
            'appId' => $this->appid,
            'nonceStr' => $this->nonce_str,
            'package' => $this->package,
            'signType' => $this->sign_type,
            'timeStamp' => $this->timestamp,
            'key' => $this->key,
        ];
        $result = http_build_query($build_data);
        $put_data = str_replace('%3D', '=', $result); //格式化网址
        $signatrue = md5($put_data);

        $this->resign = strtoupper($signatrue);

    }

    //小程序支付完成修改订单
    /*public function CallBack($order_sn)
    {
        $text = json_encode(input());
        file_put_contents(ROOT_PATH . 'runtime/log/201912/pay.txt', $text);
        $order = $this->Order->GetOneData(array('order_sn'=>$order_sn));

        $change = array(
            'order_id'          => $order['order_id'],
            'order_ispay'       => 1,
            'order_paymoney'    => $order['order_money'],
            'order_paytime'     => time(),
            'order_schedule'    => 10,
            'order_status'      => 10
        );

        $this->Order->UpdateData($change);

        if($order['order_desc'] == '跑腿任务')
            $this->Task->UpdateData(array('task_id'=>$order['order_task'],'task_status'=>10));

        return $order['order_id'];
    }*/

    public function CallBack($order_sn)
    {
        $order = $this->Order->GetOneData(array('order_sn'=>$order_sn));
        $orderId = $order['order_id'];

        // 获取抽奖机会
        $money = $order['order_money'];
        if($money > 0) {
            $this->User->getPriceChance($order['order_user']);
        }

        $change = array(
            'order_id'          => $order['order_id'],
            'order_ispay'       => 1,
            'order_paymoney'    => $order['order_money'],
            'order_paytime'     => time(),
            'order_schedule'    => 10,
            'order_status'      => 10
        );

        $this->Order->UpdateData($change);

        if($order['order_desc'] == '跑腿任务')
            $this->Task->UpdateData(array('task_id'=>$order['order_task'],'task_status'=>10));

        if($order['order_desc'] == '商品购买'){
            $item = $this->OrderItem->GetDataList(array('item_order'=>$order['order_id']));
            foreach ($item as $key => $value) {
                $this->Goods->DataSetInc(array('goods_id'=>$value['item_goods']),'goods_sales',$value['item_number']);
                $this->Goods->DataSetDec(array('goods_id'=>$value['item_goods']),'goods_reserve',$value['item_number']);
            }

            if($value['item_is_offer'] != 0)
                $this->Limited->DataSetInc(array('limited_id'=>$value['item_is_offer']),'limited_sold',$value['item_number']);
        }


        $msgmodel = $this->MessageModel->GetOneData(array('model_type'=>'支付成功'));

        $message = array(
            'message_type'      => '支付成功',
            'message_title'     => $msgmodel['model_title'],
            'message_icon'      => $msgmodel['model_icon'],
            'message_text'      => $msgmodel['model_text'],
            'message_user'      => $order['order_user'],
        );

        $this->Message->CreateData($message);


        return $order['order_id'];
    }
}