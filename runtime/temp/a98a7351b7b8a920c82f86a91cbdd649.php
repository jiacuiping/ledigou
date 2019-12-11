<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:70:"/www/wwwroot/ledigou/public/../application/admin/view/sort/create.html";i:1562986718;}*/ ?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>添加分类</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">

  <style>
    
    #previewimage {
      margin: 10px 0px 10px 110px;
      height: 200px;
    }

  </style>

</head>
<body>
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-header">添加分类</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">

          <div class="layui-form-item">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-block">
              <input type="text" name="sort_name"  autocomplete="off" placeholder="请输入分类名称" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">分类图标</label>
            <div class="layui-input-block">
              <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>点击上传</button>
              <input type="hidden" name="sort_icon" id="imginput">
              <span style="font-weight: 900;margin-left: 20px;" id="imgmsg">建议尺寸：200*200</span>
            </div>
            <img src="" id="previewimage" style="display:none">
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">分类权重</label>
            <div class="layui-input-block">
              <input type="number" name="sort_rank"  autocomplete="off" placeholder="请输入分类权重" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">分类状态</label>
            <div class="layui-input-block">
              <input type="radio" name="sort_status" value="1" title="可用" checked="">
              <input type="radio" name="sort_status" value="0" title="禁用">
            </div>
          </div>

          <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
              <div class="layui-footer" style="left: 0;">
                <div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">确认添加</div>
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

      upload.render({
        elem: '#test3'
        ,url: "<?php echo url('Upload/UploadImage'); ?>"
        ,accept: 'images' //普通文件
        ,done: function(res){
          layer.msg(res.msg);
          if(res.code){
            $("#previewimage").attr('src',res.path);
            $("#imginput").val(res.path);
            $("#imgmsg").hide();
            $("#previewimage").show();
          }
        }
      });

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