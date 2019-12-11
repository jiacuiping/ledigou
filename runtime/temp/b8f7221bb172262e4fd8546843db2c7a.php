<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:80:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\limited\update.html";i:1575525358;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>编辑优惠商品</title>
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
      <div class="layui-card-header">编辑优惠商品</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">
          <input type="hidden" name="limited_id" value="<?php echo $data['limited_id']; ?>">
          <div class="layui-inline" style="margin-bottom:15px">
            <label class="layui-form-label">选择商品</label>
            <div class="layui-input-inline">
              <select name="limited_goods" lay-verify="required" lay-search="">
                <option value="0">直接选择或搜索选择</option>
                <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                	<option value="<?php echo $item['goods_id']; ?>" <?php if($item['goods_id'] == $data['limited_goods']): ?> selected="" <?php endif; ?>><?php echo $item['goods_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div> 
          
          <div class="layui-form-item">
            <label class="layui-form-label">优惠价格</label>
            <div class="layui-input-block">
              <input type="float" name="limited_money" value="<?php echo $data['limited_money']; ?>" autocomplete="off" placeholder="请输入商品优惠价格" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠总件数</label>
            <div class="layui-input-block">
              <input type="number" name="limited_number" value="<?php echo $data['limited_number']; ?>" autocomplete="off" placeholder="优惠商品总件数，超出自动取消优惠活动，为0时不限制" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">每人限购数</label>
            <div class="layui-input-block">
              <input type="number" name="limited_user_number" value="<?php echo $data['limited_user_number']; ?>" autocomplete="off" placeholder="每人最多购买件数，为0时不限制" class="layui-input">
            </div>
          </div>
          
          <div class="layui-inline">
            <label class="layui-form-label">优惠时间</label>
            <div class="layui-input-inline" style="width:200px">
              <input type="text" class="layui-input" name="times" id="test6" placeholder=" - " value="<?php echo date('Y-m-d',$data['limited_stime']); ?> - <?php echo date('Y-m-d',$data['limited_etime']); ?>">
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
    }).use(['index', 'form', 'upload','laydate'], function(){
      var $ = layui.$
      ,layer = layui.layer
      ,upload = layui.upload
      ,laydate = layui.laydate
      ,form = layui.form;

      laydate.render({
        elem: '#test6'
        ,range: true
      });

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