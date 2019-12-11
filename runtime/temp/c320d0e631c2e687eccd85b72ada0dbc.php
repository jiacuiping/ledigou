<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:78:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\prize\create.html";i:1575612168;}*/ ?>
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
            <label class="layui-form-label">奖品名称</label>
            <div class="layui-input-block">
              <input type="text" name="prize_name"  autocomplete="off" placeholder="请输入奖品名称" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">关联优惠券</label>
            <div class="layui-input-block">
              <select name="prize_coupon" lay-filter="prizecoupon">
                <?php if(is_array($coupons) || $coupons instanceof \think\Collection || $coupons instanceof \think\Paginator): $i = 0; $__LIST__ = $coupons;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$coupon): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $coupon['coupon_id']; ?>"><?php echo $coupon['coupon_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">奖品数量</label>
            <div class="layui-input-block">
              <input type="number" name="prize_num"  autocomplete="off" placeholder="请输入奖品数量" class="layui-input">
            </div>
          </div>

           <div class="layui-form-item">
            <label class="layui-form-label">中奖概率（%）</label>
            <div class="layui-input-block">
              <input type="number" name="prize_probability"  autocomplete="off" placeholder="请输入中奖概率" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">奖品状态</label>
            <div class="layui-input-block">
              <input type="radio" name="prize_status" value="1" title="正常" checked="">
              <input type="radio" name="prize_status" value="0" title="禁用">
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

        var flag = true;
        var prize_status = $("input[name='prize_status']:checked").val(); // 当前商品状态
        var prize_probability = $("input[name='prize_probability']").val(); // 当前商品概率

       if(prize_status == 1) {
            // 判断概率是否超过100
            $.ajax({
              url:"<?php echo url('getSumProbability'); ?>",
              type:"GET",
              async: false, 
              data:{'id':0},
              success:function(res){
                var sum = res.data + parseInt(prize_probability);
                console.log(sum);
                if(sum > 100) {
                    flag = false;
                    layer.msg('奖品总概率不得超过100%');
                    return false;
                }
              },error:function(){
                layer.msg('服务器错误，请稍后重试');
              }
            })
        } 

        // 判断是否提交数据
        if(flag) {
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
        }
        
      });
    });
  </script>
</body>
</html>