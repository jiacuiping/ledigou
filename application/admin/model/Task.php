<?php
namespace app\admin\model;
use think\Model;
use think\Validate;
/*
 * 订单表数据模型
 **/
class Task extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'create_time';
    protected $rule = [
        'task_user|发单用户'                => 'require',
        'task_shippingmobile|收货手机'      => 'require',
    ];

    /**
     * 分页读取数据
     * @param array $where   条件
     * @param int   $page    第几页
     * @param int   $limit   每页的条数
     **/
    public function GetListByPage($where=array(), $page=1, $limit=10, $order='task_id desc')
    {   
        return $this->where($where)->page($page,$limit)->order($order)->select();
    }

    //获取数据列表，不分页
    public function GetDataList($where=array(), $order='task_id desc')
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
        return $this->where('task_id',$id)->find();
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
        $res = $this->allowField(true)->save($param, ['task_id' => $param['task_id']]);

        return $res === false ? array('code'=>0,'msg'=>$this->getError()) : array('code'=>1,'msg'=>'修改成功');
    }

    /**
     * 删除数据
     * @param int $id 删除数据的id
     **/
    public function DeleteData($id)
    {
        return $this->where('task_id',$id)->delete() ? array('code'=>1,'msg'=>'删除成功') : array('code'=>0,'msg'=>'删除失败');
    }


    /**
     * 根据任务状态码获取状态信息
     * @param $taskStatus
     * @return string
     */
    public function getStatusText($taskStatus)
    {
        $statusText = "已发布";
        switch ($taskStatus) {
            case 10:
                $statusText = '已付费';
                break;
            case 20:
                $statusText = '进行中';
                break;
            case 30:
                $statusText = '已完成';
                break;
            case 40:
                $statusText = '已评价';
                break;
            default:
                $statusText = '已发布';
                break;
        }

        return $statusText;
    }


    /**
     * 根据任务状态码获取状态信息
     * @param $taskSchedule
     * @return string
     */
    public function getScheduleText($taskSchedule)
    {
        $scheduleText = "未接单";
        switch ($taskSchedule) {
            case 10:
                $scheduleText = '待取货';
                break;
            case 20:
                $scheduleText = '配送中';
                break;
            case 30:
                $scheduleText = '已送达';
                break;
            case 35:
                $scheduleText = '确认收货';
                break;
            case 40:
                $scheduleText = '已评价';
                break;
            default:
                $scheduleText = '未接单';
                break;
        }

        return $scheduleText;
    }
}