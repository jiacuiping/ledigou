<?php
namespace app\admin\model;
use think\Db;
use think\Model;
use think\Validate;
/*
 * 订单表数据模型
 **/
class Order extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'order_time';
    protected $updateTime = false;
    protected $rule = [
        'order_sn|订单编号'       => 'require|unique:order',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='order_id desc')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();

    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='order_id desc')
    {
        return $this->where($where)->order($order)->select();
    }

    /**
     * 根据条件获取数据订单id
     * @param array $where 查询条件
     **/
    public function GetOrderIds($where=array())
    {
        return $this->where($where)->column('order_id');
    }

    /**
     * 获取总条数
     * @param array $param 主键
     **/
    public function GetCount($where=array())
    {
        return $this->where($where)->count();
    }

    /**
     * 根据条件获取一条数据
     * @param array $param 主键
     **/
    public function GetOneData($where=array())
    {
        return $this->where($where)->find();
    }

    /**
     * 根据主键获取一条数据
     * @param array $param 主键
     **/
    public function GetOneDataById($id=0)
    {
        return $this->where('order_id',$id)->find();
    }

    /**
     * 根据条件获取一个字段
     * @param array $param 主键
     **/
    public function GetField($where=array(),$field)
    {
        return $this->where($where)->value($field);
    }

    /**
     * 添加操作
     * @param array $param 需要添加的数组
     **/
    public function CreateData($param)
    {
        $validate = new Validate($this->rule);
        $result   = $validate->check($param);

        if(!$result)
            return array('code'=>0,'msg'=>$validate->getError());

        $res = $this->allowField(true)->save($param);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'添加成功','id'=>$this->getLastInsID());
    }

    /**
     * 修改操作
     * @param array $param 需要修改的数组
     **/
    public function UpdateData($param)
    {
        $res = $this->allowField(true)->save($param, ['order_id' => $param['order_id']]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where('order_id',$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }

    /*
     * 根据订单退款状态码获取状态
     */
    public function getRefundText($orderRefund)
    {
        $refundText = "未申请";
        switch ($orderRefund) {
            case 10:
                $refundText = '已申请';
                break;
            case 20:
                $refundText = '审核中';
                break;
            case 30:
                $refundText = '通过审核';
                break;
            case -1:
                $refundText = '拒绝退款';
                break;
            default:
                $refundText = '未申请';
                break;
        }

        return $refundText;
    }


    /**
     * 订单退款审核
     * @param $orderId       订单id
     * @param $orderRefund   退款进度
     * @param $order         订单信息
     * @return array|bool    返回结果
     */
    public function updateOrderStatus($orderId, $orderRefund, $order)
    {

        // 如果退款进度为“审核中”或者“拒绝退款”，只需要修改订单的退款进度
        if ($orderRefund == 20 || $orderRefund == -1) {
            return $this->UpdateData(array('order_id'=>$orderId,'order_refund'=>$orderRefund));
        } elseif ($orderRefund == 30) {
            // 如果退款进度为 审核通过，需要修改订单的退款进度和订单状态，并且把用户支付金额退回到用户钱包

            // 启动事务
            Db::startTrans();
            try{
                // 查看用户钱包是否正常使用
                $wallet = new Wallet();
                $userWallet = $wallet->GetOneDataByUserId($order->order_user);
                if(!$userWallet) {
                    return ['code'=>1,'msg'=>'用户钱包已冻结'];
                }

                // 修改退款进度和订单状态
                $update = array('order_id'=>$orderId,'order_refund'=>$orderRefund, 'order_status' => 50);
                $orderRes = $this->allowField(true)->save($update, ['order_id' => $update['order_id']]);

                // 把用户支付金额退回到用户钱包
                $walletRes = $wallet->DataSetInc(['wallet_user' => $order->order_user], 'wallet_money', $order->order_paymoney);

                return $orderRes && $walletRes;
                // 提交事务
                Db::commit();
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return ['code' => 1, 'msg' => $e->getMessage()];
            }
        }
    }
}