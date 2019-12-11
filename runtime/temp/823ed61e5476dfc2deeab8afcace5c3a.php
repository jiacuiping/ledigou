<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/thebestwe/public/../application/admin/view/user/update.html";i:1563265084;}*/ ?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>编辑<?php echo $typename; ?></title>
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
      <div class="layui-card-header">编辑<?php echo $typename; ?></div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">
          <input type="hidden" name="user_id" value="<?php echo $data['user_id']; ?>">

          <div class="layui-form-item">
            <label class="layui-form-label"><?php echo $typename; ?>名称</label>
            <div class="layui-input-block">
              <input type="text" name="user_name"  autocomplete="off" class="layui-input" value="<?php echo $data['user_name']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label"><?php echo $typename; ?>手机</label>
            <div class="layui-input-block">
              <input type="text" name="user_mobile"  autocomplete="off" class="layui-input" value="<?php echo $data['user_mobile']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label"><?php echo $typename; ?>头像</label>
            <div class="layui-input-block">
              <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>点击上传</button>
              <input type="hidden" name="user_avatar" id="imginput" value="<?php echo $data['user_avatar']; ?>">
            </div>
            <img src="<?php echo $data['user_avatar']; ?>" id="previewimage">
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">身份证号</label>
            <div class="layui-input-block">
              <input type="text" name="user_idcard"  autocomplete="off" placeholder="请输入身份证号" class="layui-input" value="<?php echo $data['user_idcard']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">所属学校</label>
            <div class="layui-input-block">
              <select name="user_school" lay-verify="required">
                <?php if(is_array($schools) || $schools instanceof \think\Collection || $schools instanceof \think\Paginator): $i = 0; $__LIST__ = $schools;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $school['school_id']; ?>" <?php if($data['user_school'] == $school['school_id']): ?> selected="" <?php endif; ?>><?php echo $school['school_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">用户类型</label>
            <div class="layui-input-block">
              <input type="radio" name="user_type" value="1" title="会员" <?php if($data['user_type'] == 1): ?> checked="" <?php endif; ?>>
              <input type="radio" name="user_type" value="2" title="骑手" <?php if($data['user_type'] == 2): ?> checked="" <?php endif; ?>>
              <input type="radio" name="user_type" value="3" title="团长 " <?php if($data['user_type'] == 3): ?> checked="" <?php endif; ?>>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">用户状态</label>
            <div class="layui-input-block">
              <input type="radio" name="user_status" value="1" title="正常" <?php if($data['user_status'] == 1): ?> checked="" <?php endif; ?>>
              <input type="radio" name="user_status" value="0" title="禁用" <?php if($data['user_status'] == 0): ?> checked="" <?php endif; ?>>
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