<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:77:"/www/wwwroot/thebestwe/public/../application/index/view/login/registered.html";i:1563360607;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>申请<?php echo $name; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <!--标准mui.css-->
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/style.css" rel="stylesheet" >
  <link href="/static/layui/css/layui.css" rel="stylesheet" >
  <style>
    
    .layui-form-select {
      width: 79%;
      margin:0px 0px 20px 79px;
      height: 37px !important;
    }

    .layui-input {
      border-top-left-radius: 0px !important;
      border-bottom-left-radius: 0px !important;
    }

    .submitform {
      border-radius: 20px;
      background: #915845;
      color: #fff;
      border: none;
      width: 100%;
      height: 40px;
      margin-top: 30px;
      font-size: 16px;
      line-height: 40px;
    }

    .layui-input {
      padding-left: 10px !important;
    }

  </style>

</head>
<body>
  <header class="mui-bar mui-bar-nav">
      <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">申请成为<?php echo $name; ?></h1>
  </header>
  <div class="mui-content">
	  <!-- 申请成为团长 -->
      <div class="login">
          <p class="login-p">欢迎加入<?php echo $name; ?>，请完整填写以下信息</p>
          <form class="layui-form" id="myform">
            <input type="hidden" name="user_type" value="<?php echo $type; ?>">
            <div class="l-relative">
                <span class="l-absolute">姓名</span>
                <input type="text" name="user_name" value="" placeholder="您的姓名">
            </div>
            <div class="l-relative">
                <span class="l-absolute">年龄</span>
                <input type="text" name="user_age" value="" placeholder="您的年龄">
            </div>
            <div class="l-relative">
                <span class="l-absolute">手机</span>
                <input type="text" name="user_mobile" value="" placeholder="您的联系方式">
            </div>
            <div class="l-relative">
                <span class="l-absolute">验证码</span>
                <input type="text" name="vericode" value="" placeholder="您的验证码">
                <div class="l-btn getmsg" style="padding: 6px 12px;">获取验证码</div>
            </div>

            <div class="l-relative">
                <span class="l-absolute" style="z-index: 1;">学校</span>
                <select name="user_school" lay-verify="" lay-search>
                  <option value="0" selected="">请选择学校</option>
                  <?php if(is_array($schools) || $schools instanceof \think\Collection || $schools instanceof \think\Paginator): $i = 0; $__LIST__ = $schools;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$school): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $school['school_id']; ?>"><?php echo $school['school_name']; ?></option>
                  <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>

            <div class="l-relative">
                <span class="l-absolute">联系地址</span>
                <input type="text" name="address_info" value="" placeholder="您的联系地址">
            </div>
            <div class="l-relative">
                <span class="l-absolute">身份证号</span>
                <input type="text" name="user_idcard" value="" placeholder="您的身份证号">
            </div>
          </form>
          <div class="submitform">点击申请</div>
      </div>
	</div>
</body>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script src="/static/layui/layui.js"></script>
  <script>
    layui.use('form', function(){
        var form = layui.form;
      });

    $(".submitform").click(function(){
        $.ajax({
          url:"<?php echo url('Registered'); ?>",
          type:"POST",
          data:$("#myform").serialize(),
          success:function(res){
            alert(res.msg);
            if(res.code)
              setTimeout(function(){window.location.reload()},2000);
          },error:function(){
            alert('服务器错误，请稍后重试');
          }
        })
    })
  </script>
</html>