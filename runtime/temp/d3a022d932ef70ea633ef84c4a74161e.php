<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\financial\task.html";i:1576031901;}*/ ?>
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
                    <label class="layui-form-label">订单总数：<?php echo $summary['num']; ?></label>
                    <label class="layui-form-label">总销售额：<?php echo $summary['money']; ?></label>
                </div>
                <div class="condition">
                    <form class="layui-form" style="display: flex;float: left;width: 1200px;" id="myform" method="post" action="">

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
            ,url:"<?php echo url('task'); ?>"
            ,toolbar: '#test-table-toolbar-toolbarDemo'
            ,where : condition
            ,cellMinWidth: 80
            ,cols: [[
                {type: 'checkbox'}
                ,{field:'task_id', title:'ID', width:80, unresize: true, sort: true}
                ,{field:'task_user', title:'发布用户', width:100}
                ,{field:'task_title', title:'任务标题', width:100}
                ,{field:'task_price', title:'任务佣金', width:100}
                ,{field:'task_desc', title:'任务描述'}
                ,{field:'task_ordersuser', title:'接单用户'}
                ,{field:'task_status', title:'任务状态'}
                ,{field:'task_schedule', title:'订单进度'}
                ,{field:'create_time', title:'发布时间', minWidth:80, sort: true}
            ]]
            ,page: true
        });

        laydate.render({
            elem: '#test10'
            ,type: 'datetime'
            ,range: ','
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
                ,url:"<?php echo url('task'); ?>"
                ,toolbar: '#test-table-toolbar-toolbarDemo'
                , method: 'post'
                , where : condition
                ,cellMinWidth: 80
                ,cols: [[
                    {type: 'checkbox'}
                    ,{field:'task_id', title:'ID', width:80, unresize: true, sort: true}
                    ,{field:'task_user', title:'发布用户', width:100}
                    ,{field:'task_title', title:'任务标题', width:100}
                    ,{field:'task_price', title:'任务佣金', width:100}
                    ,{field:'task_desc', title:'任务描述'}
                    ,{field:'task_ordersuser', title:'接单用户'}
                    ,{field:'task_status', title:'任务状态'}
                    ,{field:'task_schedule', title:'订单进度'}
                    ,{field:'create_time', title:'发布时间', minWidth:80, sort: true}
                ]]
                ,page: true
                , done: function (res, curr, count) {}
            });
        }

    });
</script>
</body>
</html>