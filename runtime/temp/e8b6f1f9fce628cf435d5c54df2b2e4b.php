<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"/www/wwwroot/ledigou/public/../application/index/view/order/list.html";i:1568180927;s:57:"/www/wwwroot/ledigou/application/index/view/base/nav.html";i:1563862922;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>订单列表</title>
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
  .empty{margin-top: 30px;font-weight: 500;font-size: 18px;}
  .mui-slider{height: 100%}
  .rider-xiadan{border: 0;padding:0;}
  .xiadan-btn{margin:10px 0;}
  .abox .schedule{
    font-size: 16px;
    line-height: 38px;
    display: table-cell;
    overflow: hidden;
    width: 1%;
    -webkit-transition: background-color .1s linear;
    transition: background-color .1s linear;
    text-align: center;
    white-space: nowrap;
    text-overflow: ellipsis;
  }

  .abox .artive {
    color: #90583f;
    border-bottom: 3px solid #90583f;
  }
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
      <div style="width: 100%;">
        <div class="mui-content" style="background-color:#fff;">
            <div id="slider" class="mui-slider">
                <div class="abox">                  
                    <a href="<?php echo url('list',array('schedule'=>10)); ?>" class="schedule <?php if($schedule == 10): ?> artive <?php endif; ?>">待取货</a>
                    <a href="<?php echo url('list',array('schedule'=>20)); ?>" class="schedule <?php if($schedule == 20): ?> artive <?php endif; ?>">配送中</a>
                    <a href="<?php echo url('list',array('schedule'=>30)); ?>" class="schedule <?php if($schedule == 30): ?> artive <?php endif; ?>">已完成</a>
                </div>

                <div class="mui-slider-group">
                    <div id="item1mobile" class="mui-slider-item mui-control-content mui-active" style="border: none;">
                      <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "$empty" ;else: foreach($__LIST__ as $key=>$order): $mod = ($i % 2 );++$i;?>
                        <div class="rider-index">
                            <ul class="mui-table-view">
                              <li class="mui-table-view-cell">
                                  <a class="mui-navigate-right" style="color: #727073;text-align: left;" href="<?php echo url('info',array('task_id'=>$order['task_id'])); ?>"> 
                                      订单编号:<span style="color: #000;margin-left: 10px"><?php echo $order['order_sn']; ?></span>
                                  </a>
                              </li>
                            </ul>
                            <div class="rider-xiadan clear">
                                <div class="rider-xiadan2 rider-xiadan30">
                                    <p class="h-yichu">
                                      <img src="/static/index/imgs/qu.png" alt="" id="img24"><?php echo $order['task_pickupaddress']; if($order['task_schedule'] == 10): ?> <span class="right"><?php echo $order['task_pickupmobile']; ?></span> <?php endif; ?>
                                    </p>
                                    <p class="h-yichu">
                                      <img src="/static/index/imgs/song.png" id="img24"><?php echo $order['task_shippingaddress']; if($order['task_schedule'] == 20): ?> <span class="right"><?php echo $order['task_pickupmobile']; ?></span> <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <?php if($schedule == 10): ?>
                              <button type="button" class="mui-btn xiadan-btn changeSchedule" data-id="<?php echo $order['task_id']; ?>" data-schedule="<?php echo $order['task_schedule']; ?>">已取货</button>
                            <?php elseif($schedule == 20): ?>
                              <button type="button" class="mui-btn xiadan-btn changeSchedule" data-id="<?php echo $order['task_id']; ?>" data-schedule="<?php echo $order['task_schedule']; ?>">已送达</button>
                            <?php elseif($schedule == 30): ?>
                              <button type="button" class="mui-btn xiadan-btn">等待确认收货</button>
                            <?php elseif($schedule == 35): ?>
                              <button type="button" class="mui-btn xiadan-btn">已完成</button>
                            <?php endif; ?>
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

  layui.use('layer', function(){
    var layer = layui.layer;

    $(".changeSchedule").click(function(){
      $.ajax({
        url:"<?php echo url('changeSchedule'); ?>",
        type:"GET",
        data:{task_id:$(this).attr('data-id'),schedule:$(this).attr('data-schedule')},
        success:function(res){
          layer.msg(res.msg);
          if(res.code)
            setTimeout(function(){window.location.reload()},2000);
        },error:function(){
          layer.msg('操作失败，请刷新重试');
        }
      })
    });
  });
</script>