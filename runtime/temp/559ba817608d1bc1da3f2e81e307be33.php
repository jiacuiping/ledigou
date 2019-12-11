<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:69:"/www/wwwroot/ledigou/public/../application/index/view/order/info.html";i:1563529031;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>订单详情</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
.mui-table-view-cell{border-bottom: 1px solid #f0eef1}
</style>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">订单详情</h1>
		</h1>
	</header>
  <div class="mui-content">
		  <!-- 订单详情 -->
      <div class="rider-detail">
          <!-- 第一块 商品信息 -->
          <ul class="mui-table-view">
              <li class="mui-table-view-cell">
                  <span>商品信息</span>
              </li>
              <?php if($order['order_desc'] == '商品购买'): if(is_array($item) || $item instanceof \think\Collection || $item instanceof \think\Paginator): $i = 0; $__LIST__ = $item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$items): $mod = ($i % 2 );++$i;?>
                  <li class="mui-table-view-cell">
                      <span><?php echo $items['goods_name']; ?></span>
                      <span><span style="margin-right: 130px">x<?php echo $items['item_number']; ?></span>￥<?php echo $items['item_money']; ?></span>
                  </li>
                <?php endforeach; endif; else: echo "" ;endif; else: ?>
                <li class="mui-table-view-cell">
                      <span><?php echo $task['task_title']; ?></span>
                  </li>
              <?php endif; ?>
              <li class="mui-table-view-cell">
                  <span>联系方式</span>
                  <span><?php echo $task['task_pickupmobile']; ?></span>
              </li>
              <li class="mui-table-view-cell">
                  <span>取货地址</span>
                  <span><?php echo $task['task_pickupaddress']; ?></span>
              </li>
          </ul>
          <div class="c-hui"></div>

          <!-- 第二块 配送信息 -->
          <ul class="mui-table-view">
              <li class="mui-table-view-cell">
                  <span>配送信息</span>
              </li>
              <li class="mui-table-view-cell">
                  <span>联系人</span>
                  <span><?php echo $user; ?></span>
              </li>
              <li class="mui-table-view-cell">
                  <span>联系方式</span>
                  <span><?php echo $task['task_shippingmobile']; ?></span>
              </li>
              <li class="mui-table-view-cell">
                  <span>收货地址</span>
                  <span><?php echo $task['task_shippingaddress']; ?></span>
              </li>
          </ul>
          <div class="c-hui"></div>
          
          <!-- 第三块 佣金 -->
          <ul class="mui-table-view">
              <li class="mui-table-view-cell">
                  <span>佣金</span>
                  <span style="color: #b32124">￥<?php echo $task['task_price']; ?></span>
              </li>
          </ul>
          <div class="c-hui"></div>

          <!-- 第四块 订单信息 -->
          <ul class="mui-table-view">
              <li class="mui-table-view-cell">
                  <span>订单信息</span>
              </li>
              <li class="mui-table-view-cell">
                  <span>订单编号</span>
                  <span><?php echo $order['order_sn']; ?></span>
              </li>
              <li class="mui-table-view-cell">
                  <span>下单时间</span>
                  <span><?php echo $order['order_time']; ?></span>
              </li>
<!--               <li class="mui-table-view-cell">
                  <span>取货时间</span>
                  <span>2017-10-12 20:35:15</span>
              </li> -->
              <li class="mui-table-view-cell">
                  <span>订单状态</span>
                  <!-- 情况一 : 已完成 -->
                  <span style="color: #b32124">
                    <?php if($task['task_schedule'] == 0): ?> 未接单 <?php elseif($task['task_schedule'] == 10): ?> 待取货 <?php elseif($task['task_schedule'] == 20): ?> 配送中 <?php else: ?> 已完成  <?php endif; ?>
                  </span>
                  <!-- 情况二 : 配送中 (ps: 这种情况时下面的送达时间为空白) -->
                  <!-- <span style="color: #b32124">配送中</span> -->
              </li>
              <li class="mui-table-view-cell">
                  <span>送达时间</span>
                  <span><?php if($task['task_complete'] != 0): ?> <?php echo date('Y-m-d H:i:s',$task['task_complete']); else: ?> 未送达 <?php endif; ?></span>
              </li>
          </ul>
          
          <!-- 第五部分 备注 -->
          <div class="beizhu">
              <p>备注</p>
              <textarea disabled><?php echo $task['task_desc']; ?></textarea>

              <?php if($task['task_schedule'] == 0): ?>
                <button type="button" class="mui-btn xiadan-btn detail-btn GrabTheOrder" data-id="<?php echo $task['task_id']; ?>">点击抢单</button>
              <?php endif; if($task['task_ordersuser'] == \think\Session::get('user.user_id')): if($task['task_schedule'] == 10): ?>
                  <button type="button" class="mui-btn xiadan-btn detail-btn changeSchedule" data-id="<?php echo $task['task_id']; ?>" data-schedule="<?php echo $task['task_schedule']; ?>">已取货</button>
                <?php elseif($task['task_schedule'] == 20): ?>
                  <button type="button" class="mui-btn xiadan-btn detail-btn changeSchedule" data-id="<?php echo $task['task_id']; ?>" data-schedule="<?php echo $task['task_schedule']; ?>">已送达</button>
                <?php elseif($task['task_schedule'] == 30): ?>
                  <button type="button" class="mui-btn xiadan-btn detail-btn">已完成</button>
                <?php endif; endif; ?>
          </div>
      </div>
	</div>

</body>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script src="/static/layui/layui.js"></script>
<script>
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
    });

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