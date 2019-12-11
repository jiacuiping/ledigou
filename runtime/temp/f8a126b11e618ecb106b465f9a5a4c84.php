<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/ledigou/public/../application/admin/view/rotate/update.html";i:1562726244;}*/ ?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>编辑轮播图</title>
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
      <div class="layui-card-header">编辑轮播图</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">
          <input type="hidden" name="rotate_id" value="<?php echo $data['rotate_id']; ?>">
          <div class="layui-form-item">
            <label class="layui-form-label">上传图片</label>
            <div class="layui-input-block">
              <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>点击上传</button>
              <input type="hidden" name="rotate_img" id="imginput" value="<?php echo $data['rotate_img']; ?>">
            </div>
            <img src="<?php echo $data['rotate_img']; ?>" id="previewimage">
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">轮播图描述</label>
            <div class="layui-input-block">
              <input type="text" name="rotate_desc"  autocomplete="off" placeholder="请输入轮播图描述" class="layui-input" value="<?php echo $data['rotate_desc']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">展示位置</label>
            <div class="layui-input-block">
              <select name="rotate_address" lay-verify="required">
                <option value="index" <?php if($data['rotate_address'] == 'index'): ?> selected="" <?php endif; ?>>首页</option>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">跳转链接</label>
            <div class="layui-input-block">
              <input type="text" name="rotate_href"  autocomplete="off" placeholder="请输入轮播图跳转链接，为空则不跳转" class="layui-input" value="<?php echo $data['rotate_href']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">轮播图权重</label>
            <div class="layui-input-block">
              <input type="number" name="rotate_rank"  autocomplete="off" placeholder="请输入轮播图权重，数字越大越靠前" class="layui-input" value="<?php echo $data['rotate_rank']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">轮播图状态</label>
            <div class="layui-input-block">
              <input type="radio" name="rotate_status" value="1" title="展示" <?php if($data['rotate_status'] == 1): ?> checked="" <?php endif; ?>>
              <input type="radio" name="rotate_status" value="0" title="不展示" <?php if($data['rotate_status'] == 0): ?> checked="" <?php endif; ?>>
            </div>
          </div>

          <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
              <div class="layui-footer" style="left: 0;">
                <div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">保存修改</div>
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
          }
        }
      });

      /* 监听提交 */
      form.on('submit(component-form-demo1)', function(data){
        $.ajax({
          url:"<?php echo url('update'); ?>",
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