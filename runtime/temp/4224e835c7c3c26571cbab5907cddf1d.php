<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\limited\index.html";i:1575525359;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>优惠列表</title>
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
                    <div class="layui-card-header">优惠列表</div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="test-table-form" lay-filter="test-table-form"></table>

                        <script type="text/html" id="test-table-toolbar-toolbarDemo">
                            <div class="layui-btn-container">
                                <button class="layui-btn layui-btn-sm" id="create">新增优惠商品</button>
                            </div>
                        </script>

                        <script type="text/html" id="test-table-operate-barDemo">
                            <a class="layui-btn layui-btn-xs" lay-event="update">编辑</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
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
                        ,{field:'limited_id', title:'ID', width:100, unresize: true, sort: true}
                        ,{field:'limited_goods', title:'商品名称'}
                        ,{field:'limited_money', title:'优惠价格'}
                        ,{field:'limited_sold', title:'总销量'}
                        ,{field:'limited_time', title:'创建时间', minWidth:80, sort: true}
                        ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                    ]]
                ,page: true
            });

            $("#create").click(function(){
                layer.open({
                    type: 2
                    ,title:'新增优惠商品'
                    ,content: "<?php echo url('create'); ?>"
                    ,shadeClose: true
                    ,area: ['70%', '65%']
                    ,maxmin: true
                });
            });

            $("#keyword").click(function(){
                RenderingTable($("#statusSelect").val(),$("#myform").serializeArray());
            });

            //监听工具条
            table.on('tool(test-table-form)', function(obj){

              var dataid = obj.data.limited_id;
              if(obj.event === 'update'){
                var url = "<?php echo url('update','',false); ?>/id/" + dataid;
                layer.open({
                  type: 2
                  ,title:'编辑优惠商品'
                  ,content: url
                  ,shadeClose: true
                  ,area: ['70%', '65%']
                  ,maxmin: true
                });
              } else if(obj.event === 'del'){
                var url = "<?php echo url('delete','',false); ?>/id/" + dataid;
                layer.confirm('您确定要取消该商品的优惠吗？', function(index){
                  $.ajax({
                    url:url,
                    success:function(res){
                      layer.msg(res.msg);
                      if(res.code)
                        setTimeout(function(){window.location.reload()},2000);
                    },error:function(){
                      layer.msg('服务器错误，请稍后重试！');
                    }
                  })
                  layer.close(index);
                });
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
                          ,{field:'limited_id', title:'ID', width:100, unresize: true, sort: true}
                          ,{field:'limited_goods', title:'商品名称'}
                          ,{field:'limited_money', title:'优惠价格'}
                          ,{field:'limited_sold', title:'总销量'}
                          ,{field:'limited_time', title:'创建时间', minWidth:80, sort: true}
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