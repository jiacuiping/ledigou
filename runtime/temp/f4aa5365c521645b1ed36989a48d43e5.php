<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:74:"/www/wwwroot/ledigou/public/../application/index/view/head/share_list.html";i:1563787114;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>商品列表</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
.tg-btn{border-radius: 20px; color: #fff; background-color: #ffb212;border: 0;float: right;height: 25px; line-height: 1;}
</style>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">团长推广</h1>
		</h1>
	</header>
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
                <a href="<?php echo url('goodsInfo',array('goods_id'=>$item['goods_id'])); ?>"><button class="mui-btn tg-btn">立即购买</button></a>
            </div>
        </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
	  </div>
</body>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>