<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 钱包表数据模型
 **/
class Wallet extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'order_time';
    protected $rule = [
        'wallet_user|所属用户'       => 'require|unique:wallet',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='wallet_id desc')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='wallet_id desc')
    {
        return $this->where($where)->order($order)->select();
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
     * 获取总条数
     * @param array $param 主键
     **/
    public function GetMoney($user_id)
    {
        return $this->where('wallet_user',$user_id)->value('wallet_money');
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
        return $this->where('wallet_id',$id)->find();
    }

    /**
     * 根据用户id获取状态正常的数据
     * @param int $userid 用户id
     **/
    public function GetOneDataByUserId($userid=0)
    {
        return $this->where('wallet_user',$userid)->where('wallet_status', 1)->find();
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
        $res = $this->allowField(true)->save($param, ['wallet_id' => $param['wallet_id']]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 字段自增
     * @param array  $param   更新条件
     * @param string $field   自增字段
     * @param int    $number  更新数量
     **/
    public function DataSetInc($param,$field,$number=1)
    {
        $res = $this->where($param)->setInc($field,$number);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 字段自减
     * @param array  $param   更新条件
     * @param string $field   自减字段
     * @param int    $number  自减数量
     **/
    public function DataSetDec($param,$field,$number=1)
    {
        $res = $this->where($param)->setDec($field,$number);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where('wallet_id',$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }
}