<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 订单表数据模型
 **/
class OrderItem extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'item_time';
    protected $table = 'xin_order_item';
    protected $rule = [
        'item_goods|订单商品'       => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='item_id desc')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='item_id desc')
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
     * 通过主键获取用户组信息
     * @param array $id 主键
     **/
    public function GetOneDataById($id=0)
    {
        return $this->where('item_id',$id)->find();
    }

    /**
     * 通过主键获取用户组名称
     * @param array $id 主键
     **/
    public function GetField($where=array(),$field)
    {
        return $this->where($where)->value($field);
    }

    /**
     * 通过主键获取用户组名称
     * @param array $id 主键
     **/
    public function GetColumn($where=array(),$field)
    {
        return $this->where($where)->column($field);
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
        $res = $this->allowField(true)->save($param, ['item_id' => $param['item_id']]);

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
        return $this->where('item_id',$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }
}