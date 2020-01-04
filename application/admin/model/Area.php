<?php
namespace app\admin\model;
use think\Model;
use think\Validate;

class Area extends Model
{
    protected $autoWriteTimestamp = true;
    protected $rule = [
        'name|名称'       => 'require',
    ];

    //获取数据列表，不分页
    public function GetDataList($where=array())
    {
        return $this->where($where)->select();
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
        return $this->where('address_id',$id)->find();
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
     * 根据条件获取一列数据
     * @param array $param 主键
     **/
    public function GetColumn($where=array(),$field)
    {
        return $this->where($where)->column($field);
    }
}