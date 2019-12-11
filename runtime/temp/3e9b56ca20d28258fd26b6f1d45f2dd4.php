<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:73:"/www/wwwroot/thebestwe/public/../application/admin/view/goods/create.html";i:1563246755;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>添加商品</title>
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
      <div class="layui-card-header">添加商品</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">

          <div class="layui-form-item">
            <label class="layui-form-label">商品名称</label>
            <div class="layui-input-block">
              <input type="text" name="goods_name"  autocomplete="off" placeholder="请输入商品名称" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">商品封面</label>
            <div class="layui-input-block">
              <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>点击上传</button>
              <input type="hidden" name="goods_image" id="imginput">
            </div>
            <img src="" id="previewimage" style="display:none">
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">商品价格</label>
            <div class="layui-input-block">
              <input  type="number" step="0.01" name="goods_price"  autocomplete="off" placeholder="请输入商品价格" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">秒杀价格</label>
            <div class="layui-input-block">
              <input  type="number" step="0.01" name="goods_offer_price"  autocomplete="off" placeholder="请输入商品秒杀价格" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">商品佣金</label>
            <div class="layui-input-block">
              <input  type="number" step="0.01" name="goods_brokerage"  autocomplete="off" placeholder="请输入商品秒杀价格" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">商品库存</label>
            <div class="layui-input-block">
              <input  type="number" name="goods_reserve"  autocomplete="off" placeholder="请输入商品库存" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">所在仓库</label>
            <div class="layui-input-block">
              <select name="goods_warehouse" lay-verify="required">
                <?php if(is_array($wares) || $wares instanceof \think\Collection || $wares instanceof \think\Paginator): $i = 0; $__LIST__ = $wares;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ware): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $ware['ware_id']; ?>"><?php echo $ware['ware_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">商品分类</label>
            <div class="layui-input-block">
              <select name="goods_sort" lay-verify="required">
                <?php if(is_array($sorts) || $sorts instanceof \think\Collection || $sorts instanceof \think\Paginator): $i = 0; $__LIST__ = $sorts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sort): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $sort['sort_id']; ?>"><?php echo $sort['sort_name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">优惠券</label>
            <div class="layui-input-block">
              <input type="radio" name="goods_is_coupon" value="1" title="可用" checked="">
              <input type="radio" name="goods_is_coupon" value="0" title="不可用">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">是否推荐</label>
            <div class="layui-input-block">
              <input type="radio" name="goods_is_top" value="1" title="是" checked="">
              <input type="radio" name="goods_is_top" value="0" title="否">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">商品状态</label>
            <div class="layui-input-block">
              <input type="radio" name="goods_status" value="1" title="正常" checked="">
              <input type="radio" name="goods_status" value="0" title="禁用">
            </div>
          </div>

          <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">商品介绍</label>
            <div class="layui-input-block">
              <textarea name="goods_info" placeholder="请输入商品描述" class="layui-textarea"></textarea>
            </div>
          </div>

          <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
              <div class="layui-footer" style="left: 0;">
                <div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">添加商品</div>
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