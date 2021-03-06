<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"/www/wwwroot/thebestwe/public/../application/admin/view/warehouse/create.html";i:1562981601;}*/ ?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>创建仓库</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
</head>
<body>
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-header">创建仓库</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">

          <div class="layui-form-item">
            <label class="layui-form-label">仓库名称</label>
            <div class="layui-input-block">
              <input type="text" name="ware_name"  autocomplete="off" placeholder="请输入仓库名称" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">所属学校</label>
            <div class="layui-input-block">
              <select name="ware_school" lay-verify="required">
                <?php if(is_array($schools) || $schools instanceof \think\Collection || $schools instanceof \think\Paginator): $i = 0; $__LIST__ = $schools;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $school['school_id']; ?>"><?php echo $school['school_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">仓库状态</label>
            <div class="layui-input-block">
              <input type="radio" name="ware_status" value="1" title="可用" checked="">
              <input type="radio" name="ware_status" value="0" title="禁用">
            </div>
          </div>

          <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
              <div class="layui-footer" style="left: 0;">
                <div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">确认创建</div>
                <!-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script src="/static/layui/layui.js"></script>  
  <script>
    layui.config({
      base: '/static/admin/' //静态资源所在路径
    }).extend({
      index: 'lib/index' //主入口模块
    }).use(['index', 'form', 'upload'], function(){
      var $ = layui.$
      ,layer = layui.layer
      ,upload = layui.upload
      ,form = layui.form;


      /* 监听提交 */
      form.on('submit(component-form-demo1)', function(data){
        $.ajax({
          url:"<?php echo url('create'); ?>",
          type:"POST",
          data:$("#myform").serialize(),
          success:function(res){
            layer.msg(res.msg);
            if(res.code)
              setTimeout(function(){window.parent.location.reload()},2000);
          },error:function(){
            layer.msg('服务器错误，请稍后重试');
          }
        })
      });
    });
  </script>
</body>
</html>