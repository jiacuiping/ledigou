<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/ledigou/public/../application/admin/view/evaluate/show.html";i:1570525217;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>查看评价</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
        <style>
          
            .orderinfo {
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
                                <th rowspan="3">基本信息</th>
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
                                <td>用户名称</td>
                                <td>手机号码</td>
                                <td>用户头像</td>
                                <td>商品名称</td>
                                <td>评价星级</td>
                                <td>是否匿名</td>
                                <td>评价时间</td>
                            </tr>
                            <tr>
                                <td><?php echo $user['user_name']; ?></td>
                                <td><?php echo $user['user_mobile']; ?></td>
                                <td><a href="<?php echo $user['user_avatar']; ?>" target="_blank">点击查看</a></td>
                                <td><?php echo $goods['goods_name']; ?></td>
                                <td><?php echo $evaluate['eval_star']; ?></td>
                                <td><?php if($evaluate['eval_is_incognito'] == 0): ?> 匿名 <?php else: ?> 未匿名 <?php endif; ?></td>
                                <td><?php echo $evaluate['eval_time']; ?></td>
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
                                <td>购买件数</td>
                                <td>是否为团长推荐</td>
                                <td>是否使用优惠券</td>
                                <td>商品总额</td>
                                <td>佣金总额</td>
                                <td>下单时间</td>
                            </tr>
                            <tr>
                                <td><?php echo $item['item_number']; ?></td>
                                <td><?php if($item['item_head'] == 0): ?> 否 <?php else: ?> 是 <?php endif; ?></td>
                                <td><?php if($item['item_is_offer'] == 0): ?> 否 <?php else: ?> 是 <?php endif; ?></td>
                                <td><?php echo $item['item_money']; ?>元</td>
                                <td><?php echo $item['item_commission']; ?>元</td>
                                <td><?php echo $item['item_time']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="layui-table" lay-size="sm" style="margin-top: 20px;">
                        <colgroup>
                            <col>
                        </colgroup>
                        <thead>
                            <tr>
                                <th rowspan="6">评价详情</th>
                                <th></th>
                            </tr> 
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan='6'>评价内容</td>
                            </tr>
                            <tr>
                                <td colspan='6' style="height: 90px;"><?php echo $evaluate['eval_text']; ?></td>
                            </tr>
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
