<?php
namespace app\admin\model;
use think\Db;
use think\Model;
use think\Validate;
/*
 * 提现
 **/
class Cash extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'cash_time';
    protected $rule = [
        'cash_user|提交用户'    => 'require',
        'cash_money|提现金额'    => 'require',
        'cash_type|提现类型'    => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='cash_time')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='cash_time')
    {
        return $this->where($where)->order($order)->select();
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
        return $this->where('cash_id',$id)->find();
    }

    /**
     * 根据条件获取一列字段
     * @param array $param 主键
     **/
    public function GetColumn($where=array(),$field)
    {
        return $this->where($where)->column($field);
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
     * 获取总条数
     * @param array $param 主键
     **/
    public function GetCount($where=array())
    {
        return $this->where($where)->count();
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

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'添加成功');
    }

    /**
     * 修改操作
     * @param array $param 需要修改的数组
     **/
    public function UpdateData($param)
    {
        
        $res = $this->allowField(true)->save($param, ['cash_id' => $param['cash_id']]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    // 通过审核
    public function passCheck($cash)
    {
       $cashId = $cash['cash_id'];
       $cashMoney = $cash['cash_money'];
       $userId = $cash['cash_user'];

        // 查看用户钱包是否正常使用
        $wallet = new Wallet();
        $userWallet = $wallet->GetOneDataByUserId($userId);
        if(!$userWallet) {
            return ['code'=>0,'msg'=>'用户钱包已冻结'];
        }

        // 修改状态
        $update = array('cash_id'=>$cashId,'cash_status'=>1,);
        $cashRes = $this->UpdateData($update);
        if(!$cashRes) {
            return ['code'=>0,'msg'=>'审核失败'];
        }

        // 提现到钱包
        $walletRes = $wallet->DataSetInc(['wallet_user' => $userId], 'wallet_money', $cashMoney);
        if(!$walletRes) {
            return ['code'=>0,'msg'=>'存入钱包失败'];
        }

        return ['code'=>1,'msg'=>'审核成功'];

    }
}