<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\evaluate\index.html";i:1575525354;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?php echo $modeltext; ?>列表</title>
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
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header"><?php echo $modeltext; ?>列表</div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="test-table-form" lay-filter="test-table-form"></table>
                        <script type="text/html" id="operating">
                            <a class="layui-btn layui-btn-xs" lay-event="show">查看</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <script src="/static/layui/layui.js"></script>  
    <script>

        var cols;

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

            cols = [[
                {type: 'checkbox'}
                ,{field:'eval_id', title:'ID', width:100, unresize: true, sort: true}
                ,{field:'eval_user', title:'评价用户'}
                ,{field:'eval_item', title:'评价商品'}
                ,{field:'eval_star', title:'评价星际'}
                ,{field:'eval_is_incognito', title:'是否匿名', minWidth:80, sort: true}
                ,{field:'eval_time', title:'评价时间', minWidth:80, sort: true}
                ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#operating'}
            ]];

            table.render({
                elem: '#test-table-form'
                ,url:"<?php echo url('index'); ?>"
                ,toolbar: '#test-table-toolbar-toolbarDemo'
                ,cellMinWidth: 80
                ,cols: cols
                ,page: true
            });

            //监听工具条
            table.on('tool(test-table-form)', function(obj){
                var dataid = obj.data.eval_id;
                if(obj.event === 'show'){
                    var url = "<?php echo url('show','',false); ?>/id/" + dataid;
                    layer.open({
                        type: 2
                        ,title:"编辑<?php echo $modeltext; ?>"
                        ,content: url
                        ,shadeClose: true
                        ,area: ['70%', '70%']
                        ,maxmin: true
                    });
                } else if(obj.event === 'del'){
                    var url = "<?php echo url('delete','',false); ?>/id/" + dataid;
                    layer.confirm("您确定要删除该<?php echo $modeltext; ?>吗？", function(index){
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
        });
    </script>
</body>
</html>