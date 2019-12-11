<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"/www/wwwroot/thebestwe/public/../application/index/view/user/index.html";i:1563352690;s:59:"/www/wwwroot/thebestwe/application/index/view/base/nav.html";i:1563357453;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>个人中心</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
  .mui-table-view-cell>a:not(.mui-btn){padding: 10px;color: #5e5c5f; font-size: 16px}
</style>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">我的</h1>
		</h1>
	</header>

		<nav class="mui-bar mui-bar-tab">
      <a class="mui-tab-item" id="r-index">
        <span class="mui-icon iconfont">&#xe66f;</span>
        <span class="mui-tab-label">抢单</span>
      </a>
      <a class="mui-tab-item" id="r-index2">
        <span class="mui-icon iconfont">&#xe626;</span>
        <span class="mui-tab-label">任务</span>
      </a>
      <a class="mui-tab-item mui-active" id="r-index3" href="<?php echo url('user/index'); ?>">
        <span class="mui-icon iconfont">&#xe64a;</span>
        <span class="mui-tab-label">我的</span>
      </a>
  </nav>

  <script>
    //打开关于页面
    // document.getElementById('r-index').addEventListener('tap', function() {
    //   mui.openWindow({
    //     url: 'rider-index.html', 
    //     id:'r-index' 
    //   });
    // });
    // document.getElementById('r-index2').addEventListener('tap', function() {  
    //   mui.openWindow({
    //     url: 'rider-index2.html', 
    //     id:'r-index2' 
    //   });
    // });
    // document.getElementById('r-index3').addEventListener('tap', function() {
    //   mui.openWindow({
    //     url: 'rider-index3.html',  
    //     id:'r-index3' 
    //   });
    // });
  </script>

  <div class="mui-content">
		  <div class="rider-index3">
          <!-- 头像 -->
          <img src="<?php echo \think\Session::get('user.user_avatar'); ?>" alt="" class="mui-col-xs-3 left">
          <!-- 用户名称 -->
          <div class="mui-col-xs-8 left index3-div">
              <p><?php echo \think\Session::get('user.user_name'); ?><i class="mui-icon mui-icon-forward right" style="margin-top: 15px"></i></p>
          </div>
      </div>
      <div class="index3-body">
          <ul class="mui-table-view">
              <li class="mui-table-view-cell">
                  <a class="mui-navigate-right" href="<?php echo url('income'); ?>">
                      <i class="iconfont" style="color: #f85050">&#xe627;</i>收入明细
                  </a>
              </li>
              <li class="mui-table-view-cell">
                  <a class="mui-navigate-right" href="rider-index3-list2.html">
                      <i class="iconfont" style="color: #7ac4f1">&#xe61b;</i>订单查询
                  </a>
              </li>
          </ul>
      </div>
	 </div>

</body>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script type="text/javascript">

// tab切换
 mui.init({
        swipeBack: false
    });
    (function($) {
        $('.mui-scroll-wrapper').scroll({
            indicators: true //是否显示滚动条
        });
    var item2 = document.getElementById('item2mobile');
    var sliderSegmentedControl = document.getElementById('sliderSegmentedControl');
    $('.mui-input-group').on('change', 'input', function() {
        if (this.checked) {
            sliderSegmentedControl.className = 'mui-slider-indicator mui-segmented-control mui-segmented-control-inverted mui-segmented-control-' + this.value;
            //force repaint
            sliderProgressBar.setAttribute('style', sliderProgressBar.getAttribute('style'));
        }
     });
})(mui);
</script>