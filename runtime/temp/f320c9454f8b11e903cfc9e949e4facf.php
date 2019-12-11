<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"/www/wwwroot/ledigou/public/../application/index/view/head/income.html";i:1568173771;s:61:"/www/wwwroot/ledigou/application/index/view/base/headnav.html";i:1563864365;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>收益列表</title>
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
        <!-- 佣金汇总 -->
        <div class="c-yongjin">
            <!-- 上面灰色部分 start -->
            <div class="c-yongjin-top">
                <select id="timeselect">
                    <option value="0">全部</option>
                    <option value="1">本月</option>
                    <option value="2">本周</option>
                </select>
                <div class="c-yj-input">
                    <input type="text" name="" value="">
                    <!-- 点击搜索后跳转 -->
                    <a href="#" title="">
                        <img src="/static/index/imgs/search.png" alt="">
                    </a>
                </div>
                <span class="right">合计 ￥<?php echo $sum; ?></span>
            </div>
            <?php if(is_array($order) || $order instanceof \think\Collection || $order instanceof \think\Paginator): $i = 0; $__LIST__ = $order;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
              <div class="l-zong">
                  <div class="l-img l-img2">
                      <img src="/static/index/imgs/yongjin.png" alt="">
                  </div>
                  <div class="l-zi l-zi2">
                      <p>交易单号: <?php echo $item['order_sn']; ?><span>+<?php echo $item['income']; ?></span></p>
                      <p><?php echo date('Y-m-d H:i',$item['order_paytime']); ?></p>
                  </div>
              </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>  
    </div>

    <script>
      
      $("#timeselect").change(function(){
        location.href="<?php echo url('income','',false); ?>/Interval/" + $(this).val();
      });

    </script>

</body>
</html>