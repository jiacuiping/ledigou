<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 会员登陆表数据模型
 **/
class User extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'user_time';
    protected $rule = [
        'user_mobile|手机号'  => 'unique:user',
        'user_openid|令牌'    => 'unique:user',
        'user_unionid|唯一标识'    => 'unique:user',
        'user_wxid|令牌'    => 'unique:user',
        'user_idcare|身份证号'  => 'unique:user',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='user_id desc')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='user_id desc')
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
        return $this->where('user_id',$id)->find();
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

        if(isset($param['user_idcard']) && !CheckIdCard($param['user_idcard'])) return array('code'=>0,'msg'=>'身份证号不正确');

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
        if(isset($param['user_idcard']) && !CheckIdCard($param['user_idcard'])) return array('code'=>0,'msg'=>'身份证号不正确');
        
        $res = $this->allowField(true)->save($param, ['user_id' => $param['user_id']]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where('user_id',$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }

    /**
     * 用户支付金额到达300时，获得一次抽奖机会
     * @param $param
     * @param $orderPrice
     * @throws \think\Exception
     */
    public function getPriceChance($param, $orderPrice)
    {
        if ($orderPrice > 300 || $orderPrice == 300) {
            $this->where($param)->setInc('user_price_num',1);
        }
    }
}