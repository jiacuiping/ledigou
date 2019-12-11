<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\order\update.html";i:1575961982;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>查看订单</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
        <style>
          
            .orderdata {
                width: 100%;
                height: 300px;
                border-collapse:collapse;
            }

            .rowbox {
                width: 100%;
                height: 30px;
                line-height: 30px;
            }

            .rowtitle {
                background-color: #444;
            }

            .piece {
                float: left;
                text-align: center;
            }

        </style>
    </head>
    <body>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-body" style="padding: 15px;">

                    <table class="layui-table" lay-size="sm">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="3">订单信息</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr> 
                        </thead>
                        <tbody>

                            <tr>
                                <td>订单编号</td>
                                <td>下单用户</td>
                                <td>订单金额</td>
                                <td>支付状态</td>
                                <td>支付金额</td>
                                <td>支付时间</td>
                            </tr>
                            <tr>
                                <td><?php echo $data['order_sn']; ?></td>
                                <td><?php echo $data['order_user']; ?></td>
                                <td><?php echo $data['order_money']; ?>元</td>
                                <td><?php if($data['order_ispay'] == 0): ?> 未支付 <?php else: ?> 已支付 <?php endif; ?></td>
                                <td><?php echo $data['order_paymoney']; ?>元</td>
                                <td><?php if($data['order_paytime'] == 0): ?> 未支付 <?php else: ?> <?php echo date('Y-m-d H:i',$data['order_paytime']); endif; ?></td>
                            </tr>
                        </tbody>
                    </table> 
                    <?php if($data['order_desc'] == '商品购买'): ?>
                        <table class="layui-table" lay-size="sm" style="margin-top: 20px;">
                            <colgroup>
                                <col>
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th rowspan="5">商品信息</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr> 
                            </thead>
                            <tbody>
                                <tr>
                                    <td>商品名称</td>
                                    <td>商品件数</td>
                                    <td>是否是团长推广</td>
                                    <td>是否优惠</td>
                                    <td>共计</td>
                                </tr>
                                <?php if(is_array($otherinfo) || $otherinfo instanceof \think\Collection || $otherinfo instanceof \think\Paginator): $i = 0; $__LIST__ = $otherinfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                                  <tr>
                                      <td><?php echo $item['goods_name']; ?></td>
                                      <td><?php echo $item['item_number']; ?></td>
                                      <td><?php if($item['item_head'] == 0): ?> 否 <?php else: ?> 是 <?php endif; ?></td>
                                      <td><?php if($item['item_is_offer'] == 0): ?> 否 <?php else: ?> 是 <?php endif; ?></td>
                                      <td><?php echo $item['item_money']; ?>元</td>
                                  </tr>
                                <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <table class="layui-table" lay-size="sm" style="margin-top: 20px;">
                            <colgroup>
                                <col>
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th rowspan="6">任务信息</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr> 
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width:75px;">任务标题：</td>
                                    <td style="width: 110px;"><?php echo $otherinfo['task_title']; ?></td>
                                    <td style="width:75px;">关联学校：</td>
                                    <td style="width: 125px;"><?php echo $otherinfo['task_school']; ?></td>
                                    <td style="width:75px;">任务佣金：</td>
                                    <td style="width: 125px;"><?php echo $otherinfo['task_price']; ?>元</td>
                                </tr>
                                <tr>
                                    <td>接单用户：</td>
                                    <td><a href="<?php echo url('user/show',array('id'=>$otherinfo['task_ordersuser'])); ?>"><?php echo $otherinfo['task_username']; ?></a></td>
                                    <td>取货地址：</td>
                                    <td><?php echo $otherinfo['task_pickupaddress']; ?></td>
                                    <td>取货联系电话：</td>
                                    <td><?php echo $otherinfo['task_pickupmobile']; ?></td>
                                </tr>
                                <tr>
                                    <td>收货地址：</td>
                                    <td><?php echo $otherinfo['task_shippingaddress']; ?></td>
                                    <td>收货联系人</td>
                                    <td><?php echo $otherinfo['task_shippinguser']; ?></td>
                                    <td>收货联系电话</td>
                                    <td><?php echo $otherinfo['task_shippingmobile']; ?></td>
                                </tr>
                                <tr>
                                    <td>接单时间：</td>
                                    <td><?php if($otherinfo['task_ordertime'] == 0): ?> 未接单 <?php else: ?> <?php echo date('Y-m-d H:i',$otherinfo['task_ordertime']); endif; ?></td>
                                    <td>送达时间</td>
                                    <td><?php if($otherinfo['task_complete'] == 0): ?> 未送达 <?php else: ?> <?php echo date('Y-m-d H:i',$otherinfo['task_complete']); endif; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>
                    <table class="layui-table" lay-size="sm" style="margin-top: 20px;">
                        <colgroup>
                            <col>
                            <col>
                            <col>
                            <col>
                            <col>
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="5">订单状态</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr> 
                        </thead>
                        <tbody>
                            <tr>
                                <td>订单进度</td>
                                <td>物流单号</td>
                                <td>订单状态</td>
                                <td>退款状态</td>
                            </tr>
                                <tr>
                                    <td><?php echo $data['order_schedule']; ?></td>
                                    <td><?php echo $data['order_ocode']; ?></td>
                                    <td><?php echo $data['order_status']; ?></td>
                                    <td><?php echo $data['order_refund']; ?></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if($data['order_refund'] == "已申请" || $data['order_refund'] == "审核中"): ?>
        <div class="layui-fluid">
            <div class="layui-card">
                <div class="layui-card-body" style="padding: 15px;">
                    <form class="layui-form" action="" lay-filter="component-form-group" id="">
                        <input type="hidden" name="order_id" class="order_id" value="<?php echo $data['order_id']; ?>">
                        <div class="layui-form-item">
                            <label class="layui-form-label">退款审核</label>
                            <div class="layui-input-block">
                                <input type="radio" name="order_refund" value="10" title="已申请" <?php if($data['order_refund'] == '已申请'): ?> checked="" <?php endif; ?>>
                                <input type="radio" name="order_refund" value="20" title="审核中" <?php if($data['order_refund'] == '审核中'): ?> checked="" <?php endif; ?>>
                                <input type="radio" name="order_refund" value="30" title="审核通过" <?php if($data['order_refund'] == '审核通过'): ?> checked="" <?php endif; ?>>
                                <input type="radio" name="order_refund" value="-1" title="拒绝退款" <?php if($data['order_refund'] == '拒绝退款'): ?> checked="" <?php endif; ?>>
                            </div>
                        </div>
                        <div class="layui-form-item layui-layout-admin">
                            <div class="layui-input-block">
                                <div class="layui-footer" style="left: 0;">
                                    <button type="button" class="layui-btn mgl-20 sub-refund" value="查询" style="margin-left: 15px !important;">保存修改</button>
                                    <!-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <?php endif; ?>


        <script src="/static/layui/layui.js"></script>  
        <script>
            layui.config({
                base: '/static/admin/'
            }).extend({
                index: 'lib/index'
            }).use(['index', 'form', 'upload'], function(){
                var $ = layui.$
                ,layer = layui.layer
                ,upload = layui.upload
                ,form = layui.form;

                $(".sub-refund").click(function () {
                    var order_id = $(".order_id").val();
                    var order_refund = $("input[name='order_refund']:checked").val();
                    $.ajax({
                        url:"<?php echo url('order/refundaudit'); ?>",
                        type:"POST",
                        data:{order_id:order_id,order_refund:order_refund},
                        success:function(res){
                            layer.msg(res.msg);
                            if(res.code == 1)
                                setTimeout(function(){window.location.reload()},1000);
                        },error:function(){
                            layer.msg('服务器错误，请稍后重试！');
                        }
                    })
                });
            });


        </script>
    </body>
</html>
