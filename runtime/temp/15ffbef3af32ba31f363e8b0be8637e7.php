<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\coupon\create.html";i:1575525353;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>添加<?php echo $modeltext; ?></title>
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
      <div class="layui-card-header">添加<?php echo $modeltext; ?></div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">
          
          <div class="layui-form-item">
            <label class="layui-form-label">优惠券图片</label>
            <div class="layui-input-block">
              <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>点击上传</button>
              <input type="hidden" name="coupon_img" id="imginput">
            </div>
            <img src="" id="previewimage" style="display:none">
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠券名称</label>
            <div class="layui-input-block">
              <input type="text" name="coupon_name"  autocomplete="off" placeholder="请输入优惠券名称" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠券类型</label>
            <div class="layui-input-block">
              <select name="coupon_type" lay-filter="coupontype">
                <option value="1">满减优惠券</option>
                <option value="2">折扣优惠券</option>
                <option value="3">配送费优惠券</option>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠券总数</label>
            <div class="layui-input-block">
              <input type="number" name="coupon_number"  autocomplete="off" placeholder="请输入优惠券可领取总数" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠信息</label>
            <div class="layui-input-block" id="coupontypeinput">
              <input type="number" name="coupon_price"  autocomplete="off" placeholder="当前是减免优惠券，请输入减免金额" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">使用门槛</label>
            <div class="layui-input-block">
              <input type="number" name="coupon_condition"  autocomplete="off" placeholder="订单满X元可用" class="layui-input">
            </div>
          </div>

<!--           <div class="layui-form-item">
            <label class="layui-form-label">适用分类</label>
            <div class="layui-input-block">
              <input type="checkbox" name="" title="写作">
              <input type="checkbox" name="" title="阅读" checked="">
              <input type="checkbox" name="" title="游戏">
            </div>
          </div> -->

          <div class="layui-form-item">
            <label class="layui-form-label">重复领取</label>
            <div class="layui-input-block">
              <input type="radio" name="coupon_repeat" value="1" title="允许" checked="">
              <input type="radio" name="coupon_repeat" value="0" title="不允许">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠券状态</label>
            <div class="layui-input-block">
              <input type="radio" name="coupon_status" value="1" title="正常" checked="">
              <input type="radio" name="coupon_status" value="0" title="禁用">
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

      upload.render({
        elem: '#test3'
        ,url: "<?php echo url('Upload/UploadImage'); ?>"
        ,accept: 'images' //普通文件
        ,done: function(res){
          layer.msg(res.msg);
          if(res.code){
            $("#previewimage").attr('src',res.path);
            $("#imginput").val(res.path);
            $("#previewimage").show();
          }
        }
      });

      //监听优惠券类型
      form.on('select(coupontype)', function(data){

        var html;

        if(data.value == 1)
          html = "<input type='number' name='coupon_price' autocomplete='off' placeholder='当前是减免优惠券，请输入减免金额' class='layui-input'>";
        else if(data.value == 2)
          html = "<input type='number' name='coupon_price' autocomplete='off' placeholder='当前是折扣优惠券，请输入折扣信息，1-9，1为1折' class='layui-input'>";
        else
          html = "<input type='number' name='coupon_price' readonly unselectable='on' autocomplete='off' value='"+<?php echo $freight; ?>+"' class='layui-input'>";
        
        $("#coupontypeinput").empty();
        $("#coupontypeinput").append(html);
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