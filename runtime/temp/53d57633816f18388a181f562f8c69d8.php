<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:87:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\user\commissionrecord.html";i:1575877740;}*/ ?>
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
   
        <table class="layui-table" id="order-table" style="width: 96%;margin:20px auto">
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
                    <th>总额</th>
                    <th>佣金总额</th>
                    <th>时间</th>
                </tr> 
            </thead>
            <tbody>
                <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <tr>
                        <td><?php echo $item['order_sn']; ?></td>
                        <td><?php echo $item['item_money']; ?></td>
                        <td><?php echo $item['item_commission']; ?></td>
                        <td><?php echo $item['item_time']; ?></td>
                    </tr>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    <?php endif; ?>
	<script src="/static/layui/layui.js" charset="utf-8"></script>
	<!-- 注意：如果你直接复制所有代码到本地，上述js路径需要改成你本地的 -->


    <script>

        layui.config({
            base: '/static/admin/' //静态资源所在路径
        }).extend({
            index: 'lib/index' //主入口模块
        }).use(['index', 'table', 'form', 'layer', 'laydate'], function(){
            var table = layui.table
            ,laydate = layui.laydate
            ,admin = layui.admin
            ,layer = layui.layer
            ,form = layui.form
            ,$ = layui.$;

            laydate.render({
                elem: '#test10'
                ,type: 'datetime'
                ,range: ','
            });

            $("#submit-con").click(function(){
                RenderingTable($("#statusSelect").val(),$("#orderform").serializeArray());
            });
    

           
        });
    </script>
</body>
</html>