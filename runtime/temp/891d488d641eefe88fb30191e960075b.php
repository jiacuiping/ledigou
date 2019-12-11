<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\user\show.html";i:1575525368;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>查看详情</title>
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
                                <th rowspan="3">用户信息</th>
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
                                <td><?php echo $data['user_name']; ?></td>
                                <td>用户来源</td>
                                <td><?php echo $data['user_parent']; ?></td>
                                <td>用户年龄</td>
                                <td><?php echo $data['user_age']; ?></td>
                            </tr>
                            <tr>
                                <td>用户手机</td>
                                <td><?php echo $data['user_mobile']; ?></td>
                                <td>用户头像</td>
                                <td><a href="<?php echo $data['user_avatar']; ?>" target="_blank">点击查看</a></td>
                                <td>用户身份证号</td>
                                <td><?php echo $data['user_idcard']; ?></td>
                            </tr>
                            <tr>
                                <td>用户类型</td>
                                <td><?php if($data['user_type'] == 1): ?> 普通会员 <?php elseif($data['user_type'] == 2): ?> 骑手 <?php elseif($data['user_type'] == 3): ?> 团长 <?php else: ?> 骑手、团长 <?php endif; ?></td>
                                <td>用户状态</td>
                                <td><?php if($data['user_status'] == 1): ?> 正常 <?php else: ?> 禁用 <?php endif; ?></td>
                                <td>注册时间</td>
                                <td><?php echo $data['user_time']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <?php if($data['user_type'] == 2 || $data['user_type'] == 4): ?>
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
                                    <th rowspan="5">骑手统计信息</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr> 
                            </thead>
                            <tbody>
                                <tr>
                                    <td>总接单数</td>
                                    <td>完成数</td>
                                    <td>总佣金</td>
                                    <td>已提现佣金</td>
                                    <td>账户余额</td>
                                </tr>
                                <tr>
                                    <td><?php echo $statistics['rider_ordercount']; ?></td>
                                    <td><?php echo $statistics['rider_ordercomplete']; ?></td>
                                    <td><?php echo $statistics['rider_moneycount']; ?>元</td>
                                    <td><?php echo $statistics['rider_moneyextract']; ?>元</td>
                                    <td><?php echo $statistics['rideer_money']; ?>元</td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; if($data['user_type'] == 3 || $data['user_type'] == 4): ?>
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
                                    <th rowspan="5">团长统计信息</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr> 
                            </thead>
                            <tbody>
                                <tr>
                                    <td>分享次数</td>
                                    <td>销量</td>
                                    <td>流量统计</td>
                                    <td>总佣金</td>
                                    <td>已提现佣金</td>
                                    <td>账户余额</td>
                                </tr>
                                <tr>
                                    <td><?php echo $statistics['head_sharecount']; ?></td>
                                    <td><?php echo $statistics['head_salescount']; ?></td>
                                    <td><?php echo $statistics['head_userscount']; ?>人</td>
                                    <td><?php echo $statistics['head_moneycount']; ?>元</td>
                                    <td><?php echo $statistics['head_withdcount']; ?>元</td>
                                    <td><?php echo $statistics['head_money']; ?>元</td>
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
                                <th rowspan="5">收货地址</th>
                                <th></th>
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
                                <td>省</td>
                                <td>市</td>
                                <td>区</td>
                                <td>学校名称</td>
                                <td>详细地址</td>
                                <td>联系人</td>
                                <td>联系电话</td>
                                <td>是否默认地址</td>
                            </tr>
                            <?php if(is_array($address) || $address instanceof \think\Collection || $address instanceof \think\Paginator): $i = 0; $__LIST__ = $address;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                              <tr>
                                  <td><?php echo $item['address_province']; ?></td>
                                  <td><?php echo $item['address_city']; ?></td>
                                  <td><?php echo $item['address_area']; ?></td>
                                  <td><?php echo $item['address_school']; ?></td>
                                  <td><?php echo $item['address_info']; ?></td>
                                  <td><?php echo $item['address_contact']; ?></td>
                                  <td><?php echo $item['address_mobile']; ?></td>
                                  <td><?php if($item['address_default'] == 0): ?> 否 <?php else: ?> 是 <?php endif; ?></td>
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
