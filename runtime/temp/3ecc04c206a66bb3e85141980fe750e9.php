<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\prize\index.html";i:1575626923;}*/ ?>
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
                
                    <div class="condition">
                        <form class="layui-form" style="display: flex;float: left;width: 1000px;" id="myform">

                            <div class="layui-form-item" style="margin-left: 15px;">
                                <div class="layui-input-block">
                                    <input type="text" name="name"  autocomplete="off" placeholder="名称搜索" class="layui-input" value="<?php echo $condition['name']; ?>">
                                </div>
                            </div>

                            <div class="layui-form-item" style="margin-left: 15px;">
                                <div class="layui-input-block">
                                    <select name="coupon" lay-verify="required">
                                        <option value="0">全部</option>
                                        <?php if(is_array($coupons) || $coupons instanceof \think\Collection || $coupons instanceof \think\Paginator): $i = 0; $__LIST__ = $coupons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$coupon): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $coupon['coupon_id']; ?>"><?php echo $coupon['coupon_name']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="layui-btn mgl-20" id="keyword" style="margin-left: 15px !important;">查询</div>
                        </form>
                    </div>

                    <div class="layui-card-body">
                        <table class="layui-hide" id="test-table-form" lay-filter="test-table-form"></table>

                        <script type="text/html" id="test-table-toolbar-toolbarDemo">
                            <div class="layui-btn-container">
                                <button class="layui-btn layui-btn-sm" id="create">添加<?php echo $modeltext; ?></button>
                            </div>
                        </script>

                        <script type="text/html" id="StatusTpl">
                            <input type="checkbox" lay-skin="switch" lay-text="正常|禁用" lay-filter="changeStatus" data-json="{{ encodeURIComponent(JSON.stringify(d)) }}" {{ d.prize_status == 1 ? 'checked' : '' }}>
                        </script>

                        <script type="text/html" id="operating">
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
                ,{field:'prize_id', title:'ID', width:100, unresize: true, sort: true}
                ,{field:'prize_name', title:'奖品名称'}
                ,{field:'prize_coupon', title:'关联优惠券'}
                ,{field:'prize_num', title:'奖品数量'}
                ,{field:'prize_probability', title:'中奖概率(%)', minWidth:80, sort: true}
                ,{field:'prize_status', title:'奖品状态', width:120, templet: '#StatusTpl', unresize: true}
                ,{field:'prize_time', title:'创建时间', minWidth:80, sort: true}
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
        
            //修改数据状态
            form.on('switch(changeStatus)', function(obj){

                var json = JSON.parse(decodeURIComponent($(this).data('json')));
                console.log(json);

                layer.confirm("确认修改<?php echo $modeltext; ?>状态？", {
                    btn: ['确定','取消']
                }, function(){
                    var flag = true;
                    // 如果状态从禁用改为可用，需判断概率和
                    if(json.prize_status == 0){
                        $.ajax({
                          url:"<?php echo url('getSumProbability'); ?>",
                          type:"GET",
                          async: false, 
                          data:{id:json.prize_id},
                          success:function(res){
                            var sum = res.data + parseInt(json.prize_probability);
                            console.log(sum);
                            if(sum > 100) {
                                flag = false;
                                layer.msg('奖品总概率不得超过100%');
                                setTimeout(function(){window.location.reload()},2000);
                            }
                          },error:function(){
                            layer.msg('服务器错误，请稍后重试');
                          }
                        })
                    }

                    if(flag) {
                        $.ajax({
                            url:"<?php echo url('change'); ?>",
                            type:"POST",
                            data:{id:json.prize_id},
                            success:function(res){
                                layer.msg(res.msg);
                                if(res.code)
                                    setTimeout(function(){window.location.reload()},2000);
                            },error:function(){
                                layer.msg('服务器错误，请稍后重试！');
                            }
                        })
                    }

                    
                }, function(){
                    window.location.reload();
                });
            });

            //添加操作
            $("#create").click(function(){
                layer.open({
                    type: 2
                    ,title:"添加<?php echo $modeltext; ?>"
                    ,content: "<?php echo url('create'); ?>"
                    ,shadeClose: true
                    ,area: ['70%', '80%']
                    ,maxmin: true
                });
            });

            $("#keyword").click(function(){
                RenderingTable($("#myform").serializeArray());
            });

            //监听工具条
            table.on('tool(test-table-form)', function(obj){
                var dataid = obj.data.prize_id;
                if(obj.event === 'update'){
                    var url = "<?php echo url('update','',false); ?>/id/" + dataid;
                    layer.open({
                        type: 2
                        ,title:"编辑<?php echo $modeltext; ?>"
                        ,content: url
                        ,shadeClose: true
                        ,area: ['70%', '90%']
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

            //筛选
            function RenderingTable(where=array())
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
                            ,{field:'prize_id', title:'ID', width:100, unresize: true, sort: true}
                            ,{field:'prize_name', title:'奖品名称'}
                            ,{field:'prize_coupon', title:'关联优惠券'}
                            ,{field:'prize_num', title:'奖品数量'}
                            ,{field:'prize_probability', title:'中奖概率', minWidth:80, sort: true}
                            ,{field:'prize_status', title:'奖品状态', width:120, templet: '#StatusTpl', unresize: true}
                            ,{field:'prize_time', title:'创建时间', minWidth:80, sort: true}
                            ,{title:'操作', width:178, align:'center', fixed: 'right', toolbar: '#operating'}
                        ]]
                    ,page: true
                    , done: function (res, curr, count) {}
                });
            }

            $("#chose-prize").click(function(){
                layer.open({
                    type: 2
                    ,title:"添加<?php echo $modeltext; ?>"
                    ,content: "<?php echo url('create'); ?>"
                    ,shadeClose: true
                    ,area: ['70%', '80%']
                    ,maxmin: true
                });
            });
        });
    </script>
</body>
</html>