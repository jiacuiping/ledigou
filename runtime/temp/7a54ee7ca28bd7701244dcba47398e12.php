<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"/www/wwwroot/ledigou/public/../application/index/view/head/release.html";i:1568171244;s:61:"/www/wwwroot/ledigou/application/index/view/base/headnav.html";i:1563864365;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>发布分享</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<style>
  .l-jia2 {
    background-image: url() !important;
  }

  .mui-col-xs-4 {
    width:50% !important;
  }

  .tg-btn{
    border-radius: 20px;
    color: #fff;
    background-color: #ffb212;
    border: 0;
    float: right;
    height: 25px;
    line-height: 1;
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
    <input type="hidden" value="<?php echo $ids; ?>" id="idsbox">
    <div class="mui-content">
    <!-- 商品发布 -->
      <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
        <div class="l-zong l-zong2">
          <div class="idbox" data-id="<?php echo $item['goods_id']; ?>"></div>
          <div class="l-img">
              <img src="<?php echo $item['goods_image']; ?>" alt="">
          </div>
          <div class="l-zi">
              <p><?php echo $item['goods_name']; ?></p>
              <p>￥<span class="l-span"><?php echo $item['goods_price']; ?></span></p>
              <p class="l-jia2" style="">佣金: ￥<?php echo $item['goods_brokerage']; ?><button type="button" class="removeGoods">点击删除</button></p>
          </div>
        </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
      <!-- 分享按钮 -->
      <a href="#forward" title="">
          <button type="button" class="shenqing-btn shenqing-btn2">分享</button>
      </a>
    </div>

    <!-- 分享到某某处 -->
    <div id="forward" class="mui-popover mui-popover-action mui-popover-bottom">
      <ul class="mui-table-view">
      <div style="padding: 20px 15px">
          <p style="font-size: 16px;margin-bottom: 20px">选择要分享到的平台</p>
          <div class="yaoqing-ul">
            <ul id="shareul">
              <li class="mui-col-xs-4" data-type='2'>
                  <img src="/static/index/imgs/yaoqing1.png" alt="">
                  <span style="display: block">微信</span>
              </li>
              <li class="mui-col-xs-4" data-type='1'>
                  <img src="/static/index/imgs/yaoqing2.png" alt="">
                  <span style="display: block">微信朋友圈</span>
              </li>
            </ul>
          </div>
      </div>
      </ul>
      <ul class="mui-table-view">
        <li class="mui-table-view-cell">
            <a href="#forward"><b>取消分享</b></a>
        </li>
      </ul>
    </div>
</body>
<script src="/static/layui/layui.js"></script>
<script src="/static/index/js/jquery.min.js"></script>
<script src="/static/layui/layui.js"></script>
<script type="text/javascript">

  $(function(){

    var config = [];

    $.ajax({
      url:"<?php echo url('WechatShare/GetSigna'); ?>",
      type:"GET",
      data:{ids:$("#idsbox").val()},
      success:function(res){
        if(res.code){
          config = res.data;
          wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: config.appId, // 必填，公众号的唯一标识
            timestamp: config.timestamp, // 必填，生成签名的时间戳
            nonceStr: config.nonceStr, // 必填，生成签名的随机串
            signature: config.signature,// 必填，签名
            jsApiList: ['updateAppMessageShareData','updateTimelineShareData'] // 必填，需要使用的JS接口列表
          });
        }
      }
    });
  })


  //点击改变样式
  $(document).ready(function() {

    $(".l-jia").click(function() {
      if($(this).hasClass('gaibian1'))
        $(this).removeClass("gaibian1");
      else
        $(this).addClass("gaibian1");
    });

    //移除商品
    $(".removeGoods").click(function(){
      $(this).parents('.l-zong').remove();
    });

    layui.use('layer', function(){
      var layer = layui.layer;

      if($("#idsbox").val() == ''){
        layer.msg('请先选择要分享的商品');
        setTimeout(function(){location.href="<?php echo url('goodslist'); ?>"},2000);
      }

      $("ul#shareul").on('click','li',function(){

        var type = $(this).attr('data-type');

        var ids = '';

        $(".idbox").each(function(){
          ids += $(this).attr("data-id")+',';
        });

        if(ids != ''){
          $.ajax({
            url:"<?php echo url('Share'); ?>",
            type:"GET",
            data:{ids:ids.substring(0,ids.length-1),type:type},
            success:function(res){
              if(res.code)
                share(type,res.user_id);
              else
                layer.msg(res.msg);
            },error:function(){
              layer.msg('服务器错误，请刷新重试');
            }
          });
        }else
          layer.msg('请至少分享一个商品');

        function share(type,user_id){
          if(type == 1){
            wx.ready(function () {      //需在用户可能点击分享按钮前就先调用
                wx.updateTimelineShareData({
                    title: '快来看看我分享的商品！', // 分享标题
                    link: 'http://dxc.gqwlcm.com/index/headshare/list/head/'+user_id, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://dxc.gqwlcm.com/uploads/20190722/e65ea675b8051c048325bd994e3154cf.jpeg', // 分享图标
                    success: function () {
                      layer.msg('分享成功');
                    }
                })
            });
          }else{
            wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
                wx.updateAppMessageShareData({ 
                    title: '快来看看我分享的商品！', // 分享标题
                    desc: '我在乐迪购发现了一些有趣的商品！', // 分享描述
                    link: 'http://dxc.gqwlcm.com/index/headshare/list/head/'+user_id, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://dxc.gqwlcm.com/uploads/20190722/e65ea675b8051c048325bd994e3154cf.jpeg', // 分享图标
                    success: function () {
                      layer.msg('分享成功');
                    }
                })
            });
          }
        } 
      });
    });
  });
</script>