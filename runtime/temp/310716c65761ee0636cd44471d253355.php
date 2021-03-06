<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/wwwroot/ledigou/public/../application/index/view/head/goods_info.html";i:1563934077;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>商品详情</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
.xiangqing-img{width:100%; height: 290px}
.xq-btn{width: 100%; height: 45px;position: fixed; right: 0; left: 0; bottom: 0}
.jieshao{border-top: 8px solid #f6f6f6; padding:10px 15px;}
.js-p1{font-size: 18px; margin: 6px 0;}
.js-p2{color: #5a5657;font-size: 16px}
.js-p3{color: #ffb212;font-size: 16px; margin: 7px 0 15px 0;}
.shuliang{border-top: 1px solid #e4e2e3;border-bottom: 1px solid #e4e2e3;text-align: left; font-size: 16px;padding:10px 0;}
.xq-btn>button{background:#ffb212; width: 100%; height: 100%; color: #fff;font-size: 18px}
</style>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">商品详情</h1>
		</h1>
	</header>

    <div class="mui-content">
		  <!-- 上方轮播图 start-->
      <div class="mui-slider">
        <div class="mui-slider-group mui-slider-loop">
          <!--支持循环，需要重复图片节点-->
          <!-- <div class="mui-slider-item mui-slider-item-duplicate"><a href="#"><img src="/static/index/imgs/bg.png" class="xiangqing-img"/></a></div> -->
          <div class="mui-slider-item"><a href="#"><img src="<?php echo $data['goods_image']; ?>" class="xiangqing-img"/></a></div>
<!--           <div class="mui-slider-item"><a href="#"><img src="/static/index/imgs/shop.png" class="xiangqing-img"/></a></div>
          <div class="mui-slider-item"><a href="#"><img src="/static/index/imgs/shop.png" class="xiangqing-img"/></a></div>
          <div class="mui-slider-item"><a href="#"><img src="/static/index/imgs/bg.png" class="xiangqing-img"/></a></div> -->
          <!--支持循环，需要重复图片节点-->
          <!-- <div class="mui-slider-item mui-slider-item-duplicate"><a href="#"><img src="/static/index/imgs/shop.png" class="xiangqing-img"/></a></div> -->
        </div>

        <!-- 轮播点 -->
<!--         <div class="mui-slider-indicator">
          <div class="mui-indicator mui-active"></div>
          <div class="mui-indicator"></div>
          <div class="mui-indicator"></div>
          <div class="mui-indicator"></div>
        </div> -->
      </div>
      <!-- 轮播图 end -->
      
      <!-- 产品介绍 start -->
      <div class="jieshao clear">
          <p class="js-p1"><?php echo $data['goods_name']; ?></p>
          <p class="js-p2">
            <span>售价: </span> 
            <span style="color: #bd2226; margin: 0 7px">￥<?php echo $data['goods_offer_price']; ?></span> 
            <del>￥<?php echo $data['goods_price']; ?></del>
          </p>
          <!-- <p class="js-p3">免费配送</p> -->
          <div class="shuliang clear">
             <span style="line-height: 2">购买数量:</span>
             <div class="mui-numbox" data-numbox-step='1' data-numbox-min='1' style="float: right">
                <button class="mui-btn mui-numbox-btn-minus" type="button">-</button>
                <input class="mui-numbox-input" type="number" />
                <button class="mui-btn mui-numbox-btn-plus" type="button">+</button>
              </div>
          </div>
      </div>
      <!-- 产品介绍 end -->

      <!-- 立即购买按钮 -->
      <div class="xq-btn"><button class="mui-btn">立即购买</button></div>
	  </div>
</body>
</html>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script>

  //轮播 获得slider插件对象
  var gallery = mui('.mui-slider');
  gallery.slider({
    interval:2000//自动轮播周期，若为0则不自动播放，默认为0；
  });

</script>