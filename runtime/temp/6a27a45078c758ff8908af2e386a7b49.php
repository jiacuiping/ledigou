<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:81:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\financial\order_info.html";i:1576030301;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>订单列表</title>
        <meta name="renderer" content="webkit">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
        <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
        <style>
            .imglist {
                height: 38px;
            }

            .condition {
                height: 40px;
                margin-top: 10px;
            }

            .layui-input-block {
                margin-left: 0px;
            }
            .layui-form-label {
                width: auto;
            }

        </style>
    </head>
<body>

    <div class="layui-card layadmin-header">
        <div class="layui-breadcrumb" lay-filter="breadcrumb">
            <a lay-href="">主页</a>
            <a><cite>组件</cite></a>
            <a><cite>数据表格</cite></a>
            <a><cite>加入表单元素</cite></a>
        </div>
    </div>
  
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">订单列表</div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">订单总数：<?php echo $summary['order_num']; ?></label>
                        <label class="layui-form-label">跑腿订单：<?php echo $summary['order_type1']; ?></label>
                        <label class="layui-form-label">商品订单：<?php echo $summary['order_type2']; ?></label>
                        <label class="layui-form-label">总销售额：<?php echo $summary['order_money']; ?></label>
                    </div>
                    <div class="condition">
                        <form class="layui-form" style="display: flex;float: left;width: 1200px;" id="myform" method="post" action="<?php echo url('excel/createExcel'); ?>">
                            <div class="layui-form-item" style="margin-left: 15px;">
                                <div class="layui-input-block">
                                    <select name="type">
                                        <option value="">选择订单类型</option>
                                        <option value="商品购买">商品购买</option>
                                        <option value="跑腿任务">跑腿任务</option>
                                    </select>
                                </div>
                            </div>

                            <div class="layui-inline" style="margin-left: 15px;width: 300px">
                                <div class="layui-input-inline" style="width:100%">
                                    <input type="text" class="layui-input" id="test10" name="time" placeholder=" 订单日期筛选 ">
                                </div>
                            </div>

                            <div class="layui-btn mgl-20" id="keyword" style="margin-left: 15px !important;">查询</div>
                        </form>

                    </div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="test-table-form" lay-filter="test-table-form"></table>

                        <script type="text/html" id="test-table-switchTpl">
                            <input type="checkbox" name="order_ispay" lay-skin="switch" lay-text="已支付|未支付" lay-filter="test-table-sexDemo"
                            value="{{ d.order_id }}" {{ d.order_ispay == 1 ? 'disabled' : '' }} data-json="{{ encodeURIComponent(JSON.stringify(d)) }}" {{ d.order_ispay == 1 ? 'checked' : '' }}>
                        </script>

                        <script type="text/html" id="test-table-operate-barDemo">
                            <a class="layui-btn layui-btn-xs" lay-event="update">查看订单</a>
                            <!-- <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a> -->
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <script src="/static/layui/layui.js"></script>  
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

            var condition = [];
            $.each($("#myform").serializeArray(), function(i, field){
                condition[field.name] = field.value;
            });

            table.render({
                elem: '#test-table-form'
                ,url:"<?php echo url('order'); ?>"
                ,toolbar: '#test-table-toolbar-toolbarDemo'
                ,where : condition
                ,cellMinWidth: 80
                ,cols: [[
                        {type: 'checkbox'}
                        ,{field:'order_id', title:'ID', width:100, unresize: true, sort: true}
                        ,{field:'order_sn', title:'订单编号'}
                        ,{field:'order_user', title:'下单用户'}
                        ,{field:'order_desc', title:'订单类型'}
                        ,{field:'order_money', title:'订单总额'}
                        ,{field:'order_ispay', title:'支付状态', width:120, templet: '#test-table-switchTpl', unresize: true}
                        ,{field:'order_paytime', title:'支付时间', minWidth:80, sort: true}
                        ,{field:'order_time', title:'创建时间', minWidth:80, sort: true}
                        ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                    ]]
                ,page: true
            });

            laydate.render({
                elem: '#test10'
                ,type: 'datetime'
                ,range: ','
            });
        
            //监听性别操作
            form.on('switch(test-table-sexDemo)', function(obj){

                var json = JSON.parse(decodeURIComponent($(this).data('json')));

                if(json.order_result == 0){
                    layer.confirm('确认该订单已成功？此操作不可撤销！', {
                        btn: ['确定','取消']
                    }, function(){
                        $.ajax({
                            url:"<?php echo url('changeOrder'); ?>",
                            type:"POST",
                            data:{order_id:json.order_id},
                            success:function(res){
                                layer.msg(res.msg);
                                if(res.code)
                                    setTimeout(function(){window.location.reload()},2000);
                            },error:function(){
                                layer.msg('服务器错误，请稍后重试！');
                            }
                        })
                    }, function(){
                        window.location.reload();
                    });
                }else
                    layer.msg('该订单已完成，不可撤销');
            });


            $("#keyword").click(function(){
                RenderingTable($("#statusSelect").val(),$("#myform").serializeArray());
            });


            //筛选
            function RenderingTable(status=-1,where=array())
            {
                var condition = [];
                $.each(where, function(i, field){
                    condition[field.name] = field.value;
                });

                var tableIns = table.render({
                    elem: '#test-table-form'
                    ,url:"<?php echo url('order'); ?>"
                    ,toolbar: '#test-table-toolbar-toolbarDemo'
                    , method: 'post'
                    , where : condition
                    ,cellMinWidth: 80
                    ,cols: [[
                            {type: 'checkbox'}
                            ,{field:'order_id', title:'ID', width:100, unresize: true, sort: true}
                            ,{field:'order_sn', title:'订单编号'}
                            ,{field:'order_user', title:'下单用户'}
                            ,{field:'order_desc', title:'订单类型'}
                            ,{field:'order_money', title:'订单总额'}
                            ,{field:'order_ispay', title:'支付状态', width:120, templet: '#test-table-switchTpl', unresize: true}
                            ,{field:'order_paytime', title:'支付时间', minWidth:80, sort: true}
                            ,{field:'order_time', title:'创建时间', minWidth:80, sort: true}
                            ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                        ]]
                    ,page: true
                    , done: function (res, curr, count) {}
                });
            }
        });
    </script>
</body>
</html>