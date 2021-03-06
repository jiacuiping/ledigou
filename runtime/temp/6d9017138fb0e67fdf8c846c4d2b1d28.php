<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/wwwroot/thebestwe/public/../application/admin/view/setting/index.html";i:1562318321;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>网站设置</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">
  <style>
      
      .layui-form-label {
	      width: 120px;
      }

      .layui-input-block {
	      margin-left:150px;
      }

  </style>
</head>
<body>
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-header">网站设置</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">
	        <div class="layui-form-item">
	            <label class="layui-form-label">网站名称</label>
	            <div class="layui-input-block">
	            	<input type="text" autocomplete="off" name="website_name" placeholder="请输入网站名称" class="layui-input" value="<?php echo $info['website_name']; ?>">
	            </div>
	        </div>
	          
	        <div class="layui-form-item">
	            <label class="layui-form-label">网站英文名称</label>
	            <div class="layui-input-block">
	            	<input type="text" name="website_ename" autocomplete="off" placeholder="请输入网站英文名称" class="layui-input" value="<?php echo $info['website_ename']; ?>">
	            </div>
			</div>
	          
			<div class="layui-form-item">
	            <label class="layui-form-label">网站前台首页</label>
	            <div class="layui-input-block">
	            	<input type="text" name="website_indexurl" autocomplete="off" placeholder="请输入网站前台首页" class="layui-input" value="<?php echo $info['website_indexurl']; ?>">
	            </div>
	        </div>

	        <div class="layui-form-item">
	            <label class="layui-form-label">网站后台首页</label>
	            <div class="layui-input-block">
	           		<input type="text" name="website_adminurl" autocomplete="off" placeholder="请输入网站后台首页" class="layui-input" value="<?php echo $info['website_adminurl']; ?>">
	            </div>
	        </div>

	        <div class="layui-form-item">
	            <label class="layui-form-label">网站口号</label>
	            <div class="layui-input-block">
	            	<input type="text" name="website_slogan" autocomplete="off" placeholder="请输入网站口号" class="layui-input" value="<?php echo $info['website_slogan']; ?>">
	            </div>
	        </div>

          <div class="layui-form-item">
              <label class="layui-form-label">网站登陆密码</label>
              <div class="layui-input-block">
                <input type="text" name="password" autocomplete="off" placeholder="请输入网站登陆密码，为空则不修改" class="layui-input">
              </div>
          </div>

	        <div class="layui-form-item layui-layout-admin">
		    	<label class="layui-form-label" style="width:330px;color:#666;">注：修改网站配置信息后，下次载入缓存生效</label>
	            <div class="layui-input-block">
					<div class="layui-footer" style="left: 0;">
						<div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">立即提交</div>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
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


    $(".prohibited").click(function(){
      var text = $(this).prev().html();
      layer.msg(text+'不可编辑');
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
            setTimeout(function(){window.location.reload()},2000);
        },error:function(){
          layer.msg('服务器错误，请稍后重试');
        }
      })
    });

  });
  </script>
</body>
</html>
