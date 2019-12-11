<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:71:"/www/wwwroot/thebestwe/public/../application/admin/view/user/index.html";i:1563262354;}*/ ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>用户列表</title>
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
        <input type="hidden" id="type" value="<?php echo $type; ?>">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">用户列表</div>
                    <div class="condition">
                        <form class="layui-form" style="display: flex;float: left;width: 1000px;" id="myform">

                            <div class="layui-form-item" style="margin-left: 15px">
                                <div class="layui-input-block">
                                    <input type="text" name="name" autocomplete="off" placeholder="名称筛选" class="layui-input" value="<?php echo $condition['name']; ?>">
                                </div>
                            </div>

                            <div class="layui-form-item" style="margin-left: 15px;">
                                <div class="layui-input-block">
                                    <input type="text" name="mobile" autocomplete="off" placeholder="手机号筛选" class="layui-input" value="<?php echo $condition['mobile']; ?>">
                                </div>
                            </div>

                            <div class="layui-btn mgl-20" id="keyword" style="margin-left: 15px !important;">查询</div>
                        </form>

                    </div>
                    <div class="layui-card-body">
                        <table class="layui-hide" id="test-table-form" lay-filter="test-table-form"></table>

                        <script type="text/html" id="test-table-switchTpl">
                            <input type="checkbox" name="user_status" lay-skin="switch" lay-text="正常|禁用" lay-filter="test-table-sexDemo"
                            value="{{ d.user_id }}" {{ d.user_status == 1 ? 'disabled' : '' }} data-json="{{ encodeURIComponent(JSON.stringify(d)) }}" {{ d.user_status == 1 ? 'checked' : '' }}>
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

            var type = $("#type").val();
            var cols;
            var typename;

            if(type == 1){
                cols =  [[
                            {type: 'checkbox'}
                            ,{field:'user_id', title:'ID', width:100, unresize: true, sort: true}
                            ,{field:'user_name', title:'会员名称'}
                            ,{field:'user_mobile', title:'会员手机'}
                            ,{field:'user_avatar', title:'会员头像'}
                            ,{field:'user_school', title:'所属学校', minWidth:80, sort: true}
                            ,{field:'user_status', title:'会员状态', width:120, templet: '#test-table-switchTpl', unresize: true}
                            ,{field:'user_time', title:'创建时间', minWidth:80, sort: true}
                            ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                        ]];
                typename = '会员';
            }else if(type == 2){
                cols =  [[
                            {type: 'checkbox'}
                            ,{field:'user_id', title:'ID', width:100, unresize: true, sort: true}
                            ,{field:'user_name', title:'骑手名称'}
                            ,{field:'user_mobile', title:'骑手 手机'}
                            ,{field:'user_avatar', title:'骑手头像'}
                            ,{field:'user_school', title:'所属学校', minWidth:80, sort: true}
                            ,{field:'user_status', title:'骑手状态', width:120, templet: '#test-table-switchTpl', unresize: true}
                            ,{field:'user_time', title:'创建时间', minWidth:80, sort: true}
                            ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                        ]];
                typename = '骑手';
            }else if(type == 3){
                cols =  [[
                            {type: 'checkbox'}
                            ,{field:'user_id', title:'ID', width:100, unresize: true, sort: true}
                            ,{field:'user_name', title:'团长名称'}
                            ,{field:'user_mobile', title:'团长手机'}
                            ,{field:'user_avatar', title:'团长头像'}
                            ,{field:'user_school', title:'所属学校', minWidth:80, sort: true}
                            ,{field:'user_status', title:'团长状态', width:120, templet: '#test-table-switchTpl', unresize: true}
                            ,{field:'user_time', title:'创建时间', minWidth:80, sort: true}
                            ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#test-table-operate-barDemo'}
                        ]];
                typename = '团长';
            }

            table.render({
                elem: '#test-table-form'
                ,url:"<?php echo url('index'); ?>?type="+type
                ,toolbar: '#test-table-toolbar-toolbarDemo'
                ,cellMinWidth: 80
                ,cols: cols
                ,page: true
            });

            $("#create").click(function(){
                layer.open({
                    type: 2
                    ,title:'创建'+typename
                    ,content: "<?php echo url('create'); ?>"
                    ,shadeClose: true
                    ,area: ['70%', '80%']
                    ,maxmin: true
                });
            });

            $("#keyword").click(function(){
                RenderingTable($("#statusSelect").val(),$("#myform").serializeArray());
            });

            //监听工具条
            table.on('tool(test-table-form)', function(obj){
                var dataid = obj.data.user_id;
                if(obj.event === 'update'){
                    var url = "<?php echo url('update','',false); ?>/id/" + dataid;
                    layer.open({
                        type: 2
                        ,title:'编辑'+typename
                        ,content: url
                        ,shadeClose: true
                        ,area: ['70%', '90%']
                        ,maxmin: true
                    });
                } else if(obj.event === 'del'){
                    var url = "<?php echo url('delete','',false); ?>/id/" + dataid;
                    layer.confirm('您确定要删除该'+typename+'吗？', function(index){
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
                    ,url:"<?php echo url('index'); ?>?type="+type
                    ,toolbar: '#test-table-toolbar-toolbarDemo'
                    , method: 'post'
                    , where : condition
                    ,cellMinWidth: 80
                    ,cols: cols
                    ,page: true
                    , done: function (res, curr, count) {}
                });
            }
        });
    </script>
</body>
</html>