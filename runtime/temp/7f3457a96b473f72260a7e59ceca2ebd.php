<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:74:"/www/wwwroot/ledigou/public/../application/index/view/head/goods_list.html";i:1563933880;s:61:"/www/wwwroot/ledigou/application/index/view/base/headnav.html";i:1563864365;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>商品列表</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
<style>
  
  .mui-content {
    padding-bottom:100px !important;
  }

  .addbat {
    position:fixed;
    bottom:60px;
    width: 100%;
    height: 40px;
    line-height: 40px;
    background-color: #ffb212;
    color:#fff;
  }

</style>
</head>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">我是团长</h1>
		</h1>
	</header>

  <link href="/static/index/css/mui.min.css" rel="stylesheet">
<link href="/static/index/css/iconfont.css" rel="stylesheet" >
<link href="/static/index/css/style.css" rel="stylesheet" >

<nav class="mui-bar mui-bar-tab">
  <a class="mui-tab-item" id="c-index">
    <span class="mui-icon iconfont">&#xe605;</span>
    <span class="mui-tab-label">商品列表</span>
  </a>
  <a class="mui-tab-item" id="c-index2">
    <span class="mui-icon iconfont">&#xe6a2;</span>
    <span class="mui-tab-label">商品发布</span>
  </a>
  <a class="mui-tab-item" id="c-index3">
    <span class="mui-icon iconfont">&#xe60d;</span>
    <span class="mui-tab-label">推广记录</span>
  </a>
  <a class="mui-tab-item mui-active" id="c-index4">
    <span class="mui-icon iconfont">&#xe81e;</span>
    <span class="mui-tab-label">佣金汇总</span>
  </a>
</nav>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script src="/static/layui/layui.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
  //打开关于页面
  document.getElementById('c-index').addEventListener('tap', function() {
    mui.openWindow({
      url: "<?php echo url('Head/GoodsList'); ?>", 
      id:'c-index' 
    });
  });
  document.getElementById('c-index2').addEventListener('tap', function() {  
    mui.openWindow({
      url: "<?php echo url('head/release'); ?>", 
      id:'c-index2' 
    });
  });
  document.getElementById('c-index3').addEventListener('tap', function() {
    mui.openWindow({
      url: "<?php echo url('head/order'); ?>",  
      id:'c-index3' 
    });
  });
  document.getElementById('c-index4').addEventListener('tap', function() {
    mui.openWindow({
      url: "<?php echo url('head/income'); ?>", 
      id:'c-index4'
    });
  });

  $(function(){

    var url = window.location.href;

    $(".mui-tab-item").removeClass('mui-active');
    
    if(url.indexOf('head/goodslist') != -1)
      $("#c-index").addClass('mui-active');
    else if(url.indexOf('head/release') != -1)
      $("#c-index2").addClass('mui-active');
    else if(url.indexOf('head/order') != -1)
      $("#c-index3").addClass('mui-active');
    else
      $("#c-index4").addClass('mui-active');
  })

</script>

    <div class="mui-content">
		<!-- 商品列表 -->
      <div class="mui-input-row mui-search l-select">
          <input type="search" class="mui-input-clear" placeholder="在商城内搜索">
      </div>

      <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
        <div class="l-zong">
            <div class="l-img">
                <img src="<?php echo $item['goods_image']; ?>" alt="">
            </div>
            <div class="l-zi">
                <p><?php echo $item['goods_name']; ?></p>
                <p>￥<span class="l-span"><?php echo $item['goods_price']; ?></span></p>
                <p class="l-jia" data-id="<?php echo $item['goods_id']; ?>">佣金: ￥<?php echo $item['goods_brokerage']; ?></p>
            </div>
        </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
      <div class="addbat">确认添加</div>
	  </div>
</body>

<script type="text/javascript">

  // 点击改变样式
  $(document).ready(function() {
    $(".l-jia").click(function() {
      if($(this).hasClass('gaibian1'))
        $(this).removeClass("gaibian1");
      else
        $(this).addClass("gaibian1");
    });}
  );

  layui.use('layer', function(){
    var layer = layui.layer;

    $(".addbat").click(function(){
      var ids = '';
      $(".gaibian1").each(function(){
        ids += $(this).attr("data-id")+',';
       });

      if(ids != ''){
        window.location.href="<?php echo url('release'); ?>?ids="+ids.substring(0,ids.length-1);
      }else
        layer.msg('请选择至少一个商品！');
    });
  });

</script>