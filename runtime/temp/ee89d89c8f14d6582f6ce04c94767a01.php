<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"/www/wwwroot/thebestwe/public/../application/admin/view/order/update.html";i:1563332830;}*/ ?>
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
                                <th></th>
                            </tr> 
                        </thead>
                        <tbody>

                            <tr>
                                <td>订单编号</td>
                                <td>下单用户</td>
                                <td>团长分享</td>
                                <td>订单金额</td>
                                <td>支付状态</td>
                                <td>支付金额</td>
                                <td>支付时间</td>
                            </tr>
                            <tr>
                                <td><?php echo $data['order_sn']; ?></td>
                                <td><?php echo $data['order_user']; ?></td>
                                <td><?php echo $data['order_head']; ?></td>
                                <td><?php echo $data['order_money']; ?>元</td>
                                <td><?php if($data['order_ispay'] == 0): ?> 未支付 <?php else: ?> 已支付 <?php endif; ?></td>
                                <td><?php echo $data['order_paymoney']; ?>元</td>
                                <td><?php if($data['order_paytime'] == 0): ?> 未支付 <?php else: ?> <?php echo date('Y-m-d H:i',$data['order_paytime']); endif; ?></td>
                            </tr>
                        </tbody>
                    </table> 

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
                                <th rowspan="3">商品信息</th>
                                <th></th>
                                <th></th>
                            </tr> 
                        </thead>
                        <tbody>
                            <tr>
                                <td>商品名称</td>
                                <td>商品件数</td>
                                <td>商品总额</td>
                            </tr>
                            <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                              <tr>
                                  <td><?php echo $item['goods_name']; ?></td>
                                  <td><?php echo $item['item_number']; ?></td>
                                  <td><?php echo $item['item_money']; ?>元</td>
                              </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

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
            });
        </script>
    </body>
</html>
