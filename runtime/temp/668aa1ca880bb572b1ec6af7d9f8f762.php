<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/thebestwe/public/../application/admin/view/order/index.html";i:1563332005;}*/ ?>
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
                    <div class="condition">
                        <form class="layui-form" style="display: flex;float: left;width: 1000px;" id="myform" method="post" action="<?php echo url('excel/createExcel'); ?>">

                            <div class="layui-form-item" style="margin-left: 15px">
                                <div class="layui-input-block">
                                    <input type="text" name="sn" autocomplete="off" placeholder="订单编号筛选" class="layui-input" value="<?php echo $condition['sn']; ?>">
                                </div>
                            </div>

                            <div class="layui-btn mgl-20" id="keyword" style="margin-left: 15px !important;">查询</div>
                            <!-- <div class="layui-btn mgl-20" id="excel">导出数据</div> -->
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
        }).use(['index', 'table', 'form', 'layer'], function(){
            var table = layui.table
            ,admin = layui.admin
            ,layer = layui.layer
            ,form = layui.form
            ,$ = layui.$;

            table.render({
                elem: '#test-table-form'
                ,url:"<?php echo url('index'); ?>"
                ,toolbar: '#test-table-toolbar-toolbarDemo'
                ,cellMinWidth: 80
                ,cols: [[
                        {type: 'checkbox'}
                        ,{field:'order_id', title:'ID', width:100, unresize: true, sort: true}
                        ,{field:'order_sn', title:'订单编号'}
                        ,{field:'order_user', title:'下单用户'}
                        ,{field:'order_money', title:'订单总额'}
                        ,{field:'order_ispay', title:'支付状态', width:120, templet: '#test-table-switchTpl', unresize: true}
                        ,{field:'order_paytime', title:'支付时间', minWidth:80, sort: true}
                        ,{field:'order_time', title:'创建时间', minWidth:80, sort: true}
                        ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                    ]]
                ,page: true
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


            $("#create").click(function(){
                layer.open({
                    type: 2
                    ,title:'添加轮播图'
                    ,content: "<?php echo url('create'); ?>"
                    ,shadeClose: true
                    ,area: ['70%', '71%']
                    ,maxmin: true
                });
            });

            $("#keyword").click(function(){
                RenderingTable($("#statusSelect").val(),$("#myform").serializeArray());
            });

            //监听工具条
            table.on('tool(test-table-form)', function(obj){

                if(obj.data.member_group == 1)
                    layer.msg('超级管理员不允许修改或删除');
                else{
                    var dataid = obj.data.order_id;
                    if(obj.event === 'update'){
                        var url = "<?php echo url('update','',false); ?>/id/" + dataid;
                        layer.open({
                            type: 2
                            ,title:'查看订单'
                            ,content: url
                            ,shadeClose: true
                            ,area: ['70%', '80%']
                            ,maxmin: true
                        });
                    } else if(obj.event === 'del'){
                        // var url = "<?php echo url('delete','',false); ?>/id/" + dataid;
                        // layer.confirm('您确定要删除该订单吗？', function(index){
                        //     $.ajax({
                        //         url:url,
                        //         success:function(res){
                        //             layer.msg(res.msg);
                        //             if(res.code)
                        //                 setTimeout(function(){window.location.reload()},2000);
                        //         },error:function(){
                        //             layer.msg('服务器错误，请稍后重试！');
                        //         }
                        //     })
                        //     layer.close(index);
                        // });
                    }
                }
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
                    ,url:"<?php echo url('index'); ?>"
                    ,toolbar: '#test-table-toolbar-toolbarDemo'
                    , method: 'post'
                    , where : condition
                    ,cellMinWidth: 80
                    ,cols: [[
                            {type: 'checkbox'}
                            ,{field:'order_id', title:'ID', width:100, unresize: true, sort: true}
                            ,{field:'order_sn', title:'订单编号'}
                            ,{field:'order_user', title:'下单用户'}
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