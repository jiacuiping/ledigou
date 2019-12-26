<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:69:"/www/wwwroot/ledigou/public/../application/index/view/user/order_info.html";i:1563848583;s:57:"/www/wwwroot/ledigou/application/index/view/base/nav.html";i:1563862922;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>送餐</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
  <link href="/static/index/css/mui.min.css" rel="stylesheet">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/mui.picker.min.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
  .mui-table-view-cell>a:not(.mui-btn){padding: 10px;color: #5e5c5f; font-size: 16px}
  .mui-btn-block{margin:0;padding: 0;font-size: 14px;background: none;border: none;color: #8b898c }
  .rider-xiadan{border: 0;padding:0;}
  .xiadan-btn{margin:10px 0;}
  .rider-xiadan{border-bottom: 1px solid #f4f2f5}
  .rider-xiadan30 p{border: 0; padding: 2px 0;}
  .rider-xiadan30 p:nth-child(1){font-size: 16px}
  .ui-alert{font-size: 14px; color: #8b898c}
</style>
<body>
	<header class="mui-bar mui-bar-nav">
	    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
      <h1 class="mui-title">订单查询</h1>
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
		  <div class="index3-chaxun clear">
          <div class="mui-col-xs-8 left chaxun-left clear">
              <div class="mui-col-xs-5 left ui-alert"  id='result'>
                  <button id='demo1' data-options='{"type":"date","beginYear":2010,"endYear":2020}' class="btn mui-btn mui-btn-block"><?php echo $date['startdate']; ?></button>
              </div>
              <span>至</span>
              <div class="mui-col-xs-5 right ui-alert" id='result2'>
                  <button id='demo2' data-options2='{"type":"date","beginYear":2010,"endYear":2020}' class="btn mui-btn mui-btn-block"><?php echo $date['enddate']; ?></button>
              </div>
          </div>
          <div class="mui-col-xs-4 right chaxun-right">
              <button class="mui-btn search">查询</button>
          </div>
          <div></div>
      </div>

      <div class="zong-money"><p>总收入: <span><?php echo $sum; ?></span>元</p></div>

      <div class="index3-list2"><p>订单查询</p></div>
      <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
        <div class="rider-index">
            <div class="rider-xiadan clear">
                <div class="mui-col-xs-9 left rider-xiadan2 rider-xiadan30">
                    <p class="h-yichu">订单编号: <?php echo $item['order_sn']; ?></p>
                    <p class="h-yichu" style="color: #918f92"><?php echo date('Y-m-d H:i',$item['task_complete']); ?></p>
                </div>
                <div class="mui-col-xs-3 left rider-xiadan3">
                    <span>+<?php echo $item['task_price']; ?></span>元
                </div>
            </div>
        </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
	 </div>

</body>
<script src="/static/index/js/mui.min.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script src="/static/index/js/mui.picker.min.js"></script>
<script type="text/javascript">
// 选择时间
(function($) {
        $.init();
        var result = $('#result')[0];
        var btns = $('#demo1');
        btns.each(function(i, btn) {
          btn.addEventListener('tap', function() {
            var _self = this;
            if(_self.picker) {
              _self.picker.show(function (rs) {
                result.innerText = rs.text;
                _self.picker.dispose();
                _self.picker = null;
              });
            } else {
              var optionsJson = this.getAttribute('data-options') || '{}';
              var options = JSON.parse(optionsJson);
              var id = this.getAttribute('id');
              _self.picker = new $.DtPicker(options);
              _self.picker.show(function(rs) {
                result.innerText = rs.text;
                _self.picker.dispose();
                _self.picker = null;
              });
            }
            
          }, false);
        });
      })(mui);

(function($) {
        $.init();
        var result = $('#result2')[0];
        var btns = $('#demo2');
        btns.each(function(i, btn) {
          btn.addEventListener('tap', function() {
            var _self = this;
            if(_self.picker) {
              _self.picker.show(function (rs) {
                result.innerText = rs.text;
                _self.picker.dispose();
                _self.picker = null;
              });
            } else {
              var optionsJson = this.getAttribute('data-options2') || '{}';
              var options = JSON.parse(optionsJson);
              var id = this.getAttribute('id');
              _self.picker = new $.DtPicker(options);
              _self.picker.show(function(rs) {
                result.innerText = rs.text;
                _self.picker.dispose();
                _self.picker = null;
              });
            }
            
          }, false);
        });
      })(mui);

      $(".search").click(function(){
        var time1 = $("#result").text();
        var time2 = $("#result2").text();

        if($.trim(time1) == '开始日期' && $.trim(time2) == '结束日期'){
          alert('请至少选择一个日期');
        }else{
          window.location.href="<?php echo url('income'); ?>?startdate="+time1+"&enddate="+time2;
        }
      })
</script>