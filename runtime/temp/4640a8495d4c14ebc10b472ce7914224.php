<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:70:"/www/wwwroot/ledigou/public/../application/index/view/order/index.html";i:1563528777;s:57:"/www/wwwroot/ledigou/application/index/view/base/nav.html";i:1563862922;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>接单大厅</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
  .mui-segmented-control.mui-segmented-control-inverted .mui-control-item.mui-active {color: #90583f;}
  .mui-segmented-control.mui-segmented-control-inverted~.mui-slider-progress-bar{background-color: #90583f;}
  .mui-slider-indicator.mui-segmented-control{background: #f8f6f9;}
  .mui-slider{height: 100%}
  .empty{margin-top: 30px;font-weight: 500;font-size: 18px;}
</style>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">接单大厅</h1>
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
      <a class="mui-tab-item" id="r-index3" href="<?php echo url('user/index'); ?>">
        <span class="mui-icon iconfont">&#xe64a;</span>
        <span class="mui-tab-label">我的</span>
      </a>
  </nav>
  <script src="/static/index/js/jquery.min.js"></script>
  <script>
    //打开关于页面
    document.getElementById('r-index').addEventListener('tap', function() {
      mui.openWindow({
        url: "<?php echo url('order/index'); ?>", 
        id:'r-index' 
      });
    });
    document.getElementById('r-index2').addEventListener('tap', function() {  
      mui.openWindow({
        url: "<?php echo url('order/list'); ?>", 
        id:'r-index2' 
      });
    });
    document.getElementById('r-index3').addEventListener('tap', function() {
      mui.openWindow({
        url: "<?php echo url('user/index'); ?>",  
        id:'r-index3' 
      });
    });

    $(function(){
      var url = window.location.href;

      $(".mui-tab-item").removeClass('mui-active');

      console.log(url.indexOf('order/index'));

      if(url.indexOf('order/index') != -1)
        $("#r-index").addClass('mui-active');
      else if(url.indexOf('order/list') != -1)
        $("#r-index2").addClass('mui-active');
      else
        $("#r-index3").addClass('mui-active');
    })

  </script>

  <div class="mui-content">
		<!-- 商品列表 -->
      <div style="width: 100%;">
        <a href="<?php echo url('index',array('type'=>1)); ?>" title=""><div class="<?php if($type == 1): ?> jiedan-title <?php else: ?> jiedan-title3 <?php endif; ?>">商品任务</div></a>
        <a href="<?php echo url('index',array('type'=>2)); ?>" title=""><div class="<?php if($type == 2): ?> jiedan-title jiedan-title4 <?php else: ?> jiedan-title2 <?php endif; ?>">跑腿任务</div></a>
          <!-- 代抢已抢tab选项登录 -->
        <div class="mui-content" style="background-color:#fff;">
            <div id="slider" class="mui-slider">
                <div id="sliderSegmentedControl" class="mui-slider-indicator mui-segmented-control mui-segmented-control-inverted">
                    <a class="mui-control-item" href="#item1mobile" style="font-size: 16px">代抢</a>
                    <a class="mui-control-item" href="#item2mobile" style="font-size: 16px">已抢</a>
                </div>
                <div id="sliderProgressBar" class="mui-slider-progress-bar mui-col-xs-6" style="margin: 0"></div>
                <div class="mui-slider-group">
                    <div id="item1mobile" class="mui-slider-item mui-control-content mui-active" style="border: none;">
                          <?php if(is_array($MissedOrder) || $MissedOrder instanceof \think\Collection || $MissedOrder instanceof \think\Paginator): $i = 0; $__LIST__ = $MissedOrder;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$morder): $mod = ($i % 2 );++$i;?>
                            <!-- 这是一部分 start -->
                            <div class="rider-index">

                                <ul class="mui-table-view">
                                  <li class="mui-table-view-cell">
                                      <a class="mui-navigate-right" style="color: #727073;text-align: left;" href="<?php echo url('info',array('task_id'=>$morder['task_id'])); ?>"> 
                                          订单编号:<span style="color: #000;margin-left: 10px"><?php echo $morder['order_sn']; ?></span>
                                      </a>
                                  </li>
                                </ul>

                                <!-- 下单 -->
                                <div class="rider-xiadan clear">
                                    <div class="mui-col-xs-2 left rider-xiadan1">
                                        <p>下单</p>
                                        <p><?php echo date('H:i',$morder['create_time']); ?></p>
                                    </div>
                                    <div class="mui-col-xs-7 left rider-xiadan2">
                                        <p class="h-yichu"><img src="/static/index/imgs/qu.png" alt="" id="img24"><?php echo $morder['task_pickupaddress']; ?></p>
                                        <p class="h-yichu"><img src="/static/index/imgs/song.png" alt="" id="img24"><?php echo $morder['task_shippingaddress']; ?></p>
                                    </div>
                                    <div class="mui-col-xs-3 left rider-xiadan3">
                                        <span><?php echo $morder['task_price']; ?></span>元
                                    </div>
                                </div>
                                <button type="button" class="mui-btn xiadan-btn GrabTheOrder" data-id="<?php echo $morder['task_id']; ?>">抢单</button>
                            </div>
                            <div style="background:#f8f6f9;height: 15px"></div>
                          <?php endforeach; endif; else: echo "$empty" ;endif; ?>
                    </div>
                    
                    <div id="item2mobile" class="mui-slider-item mui-control-content"  style="border: none;">
                          <?php if(is_array($TakeOrders) || $TakeOrders instanceof \think\Collection || $TakeOrders instanceof \think\Paginator): $i = 0; $__LIST__ = $TakeOrders;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$torder): $mod = ($i % 2 );++$i;?>
                            <!-- 这是一部分 start -->
                            <div class="rider-index">
                                <ul class="mui-table-view">
                                  <li class="mui-table-view-cell">
                                      <a class="mui-navigate-right" style="color: #727073;text-align: left;" href="<?php echo url('info',array('task_id'=>$torder['task_id'])); ?>"> 
                                          订单编号:<span style="color: #000;margin-left: 10px"><?php echo $torder['order_sn']; ?></span>
                                      </a>
                                  </li>
                                </ul>
                                <!-- 下单 -->
                                <div class="rider-xiadan clear">
                                    <div class="mui-col-xs-2 left rider-xiadan1">
                                        <p>下单</p>
                                        <p><?php echo date('H:i',$torder['create_time']); ?></p>
                                    </div>
                                    <div class="mui-col-xs-7 left rider-xiadan2">
                                        <p class="h-yichu"><img src="/static/index/imgs/qu.png" alt="" id="img24"><?php echo $torder['task_pickupaddress']; ?></p>
                                        <p class="h-yichu"><img src="/static/index/imgs/song.png" alt="" id="img24"><?php echo $torder['task_shippingaddress']; ?></p>
                                    </div>
                                    <div class="mui-col-xs-3 left rider-xiadan3">
                                        <span><?php echo $torder['task_price']; ?></span>元
                                    </div>
                                </div>
                                <button type="button" class="mui-btn xiadan-btn">已被抢</button>
                            </div>
                            <div style="background:#f8f6f9;height: 15px"></div>
                          <?php endforeach; endif; else: echo "$empty" ;endif; ?>
                      </div>
                </div>
            </div>
        </div>
      </div>
	 </div>

</body>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script src="/static/layui/layui.js"></script>
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
              sliderProgressBar.setAttribute('style', sliderProgressBar.getAttribute('style'));
          }
       });
  })(mui);

  layui.use('layer', function(){
    var layer = layui.layer;

    $(".GrabTheOrder").click(function(){
      $.ajax({
        url:"<?php echo url('GrabTheOrder'); ?>",
        type:"GET",
        data:{task_id:$(this).attr('data-id')},
        success:function(res){
          layer.msg(res.msg);
          if(res.code)
            setTimeout(function(){window.location.reload()},2000);
        },error:function(){
          layer.msg('抢单失败');
        }
      })
    })
  });
</script>