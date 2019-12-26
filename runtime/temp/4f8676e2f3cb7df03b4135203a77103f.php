<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"/www/wwwroot/ledigou/public/../application/index/view/head/order_info.html";i:1563852485;s:61:"/www/wwwroot/ledigou/application/index/view/base/headnav.html";i:1563864365;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>推广记录</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
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
        <!-- 推广记录 -->
        <?php if(is_array($order) || $order instanceof \think\Collection || $order instanceof \think\Paginator): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
          <div id="c-tuiguang">
              <div class="c-tuiguang">
                  <li class="mui-table-view-cell">
                      <a class="mui-navigate-right">
                          <i class="mui-icon iconfont">&#xe606;</i>交易单号:<?php echo $item['order_sn']; ?>
                      </a>
                  </li>
                  <p>销售价格: <span><?php echo $item['order_paymoney']; ?></span></p>
                  <p>佣金: <?php echo $item['sumprice']; ?>元</p>
                  <p class="h-yichu">货物名称: <?php echo $item['goods_names']; ?></p>
                  <p>交易时间: <span><?php echo date('Y-m-d H:i:s',$item['order_paytime']); ?></span></p>
              </div>
              <div class="c-hui"></div>
          </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>

        <!-- 以下重复上面 -->
<!--         <div id="c-tuiguang">
            <div class="c-tuiguang">
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <i class="mui-icon iconfont">&#xe606;</i>交易单号:S17052545481
                    </a>
                </li>
                <p>销售价格: <span>267元</span></p>
                <p>佣金: 5元</p>
                <p class="h-yichu">货物名称: 【三只松鼠_小美初恋时光115g】水果水果水果</p>
                <p>交易时间: <span>2017/09/12 14:40</span></p>
            </div>
            <div class="c-hui"></div>
        </div>
        <div id="c-tuiguang">
            <div class="c-tuiguang">
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <i class="mui-icon iconfont">&#xe606;</i>交易单号:S17052545481
                    </a>
                </li>
                <p>销售价格: <span>267元</span></p>
                <p>佣金: 5元</p>
                <p class="h-yichu">货物名称: 【三只松鼠_小美初恋时光115g】水果水果水果</p>
                <p>交易时间: <span>2017/09/12 14:40</span></p>
            </div>
            <div class="c-hui"></div>
        </div>
        <div id="c-tuiguang">
            <div class="c-tuiguang">
                <li class="mui-table-view-cell">
                    <a class="mui-navigate-right">
                        <i class="mui-icon iconfont">&#xe606;</i>交易单号:S17052545481
                    </a>
                </li>
                <p>销售价格: <span>267元</span></p>
                <p>佣金: 5元</p>
                <p class="h-yichu">货物名称: 【三只松鼠_小美初恋时光115g】水果水果水果</p>
                <p>交易时间: <span>2017/09/12 14:40</span></p>
            </div>
            <div class="c-hui"></div>
        </div> -->
    </div>
</body>
<script type="text/javascript">

</script>