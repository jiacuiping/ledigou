<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/wwwroot/ledigou/public/../application/admin/view/user/showrecord.html";i:1572514884;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/static/layui/css/layui.css"  media="all">
    <!-- 注意：如果你直接复制所有代码到本地，上述css路径需要改成你本地的 -->
</head>
<body>
    <?php if(empty($data)): ?>
        <div style="width: 100%;text-align: center;margin-top: 100px;font-size: 20px;">暂无记录</div>
    <?php else: ?>
        <table class="layui-table" style="width: 96%;margin:20px auto">
            <colgroup>
                <col width="150">
                <col width="150">
                <col width="200">
                <col>
                <col>
                <col>
                <col>
            </colgroup>
            <thead>
                <tr>
                    <th>订单编号</th>
                    <th>订单类型</th>
                    <th>订单总额</th>
                    <th>是否支付</th>
                    <th>下单时间</th>
                    <th>订单状态</th>
                    <th>查看详情</th>
                </tr> 
            </thead>
            <tbody>
                <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo $item['order_sn']; ?></td>
                        <td><?php echo $item['order_desc']; ?></td>
                        <td><?php echo $item['order_money']; ?></td>
                        <td><?php if($item['order_ispay'] == 1): ?> 是 <?php else: ?> 否 <?php endif; ?></td>
                        <td><?php echo $item['order_time']; ?></td>
                        <td><?php echo $item['order_status']; ?></td>
                        <td><a href="<?php echo url('order/update',array('id'=>$item['order_id'])); ?>">点击查看</a></td>
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
	<script src="/static/layui/layui.js" charset="utf-8"></script>
	<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->
</body>
</html>