<?php
namespace app\index\controller;

use app\admin\model\Cash as CashModel;
use think\Session;
use app\index\controller\LoginBase;

// 提现

class Cash extends LoginBase
{
    private $Cash;

    public function __construct()
    {
        parent::__construct();
        $this->Cash = new CashModel;
    }

    // 提交提现申请
    public function apply_cash()
    {
        $res = $this->Cash->CreateData(input('post.'));
        if($res['code']) {
            $this->redirect(url('user/cash',['mess'=>"申请信息已提交，等待后台审核"]));
        } else {
            return array('code'=>0,'msg'=>'提交失败');
        }
    }

}
