<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:75:"/www/wwwroot/ledigou/public/../application/index/view/headshare/paynow.html";i:1568209287;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>订单结算</title>
  <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1,user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<!--标准mui.css-->
<link rel="stylesheet" href="/static/index/css/mui.min.css">
  <link href="/static/index/css/iconfont.css" rel="stylesheet" >
  <link href="/static/index/css/style.css" rel="stylesheet" >
</head>
<style>
.xq-btn{width: 100%; height: 45px;position: fixed; right: 0; left: 0; bottom: 0; border-top: 1px solid #ccc }
.xq-btn>button{background:#ffb212;height: 100%; color: #fff;font-size: 18px;border: none;border-radius: 0;}
.heji{font-size: 16px;line-height: 45px;color: #e24000;}
.maijia{padding: 10px; margin: 10px 0; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;}
.maijia-p1{font-size: 17px;margin:0 0 10px 0}
.maijia-p2,.maijia-p3{margin: 5px 0;color: #666;}
.maijia-p2 span:nth-child(1){margin: 0 5px 0 20px}
.maijia-p2 span:nth-child(2){color: #fff;background: #e24000;border-radius: 3px;font-size: 12px;padding: 0 5px;}
.shangjia-p1{margin-bottom: 10px;}
.shangjia-p2,.shangjia-p3{color: #999;}
.shangjia3,.shangjia3 p{text-align: right}
.ju{color:#e24000; font-weight: bold; margin-bottom: 10px}
.ju-del{color:#999; margin-left: 5px;font-size: 12px}
.beizhu-qr textarea{min-height: 100px;margin-bottom: 0}
.youhuiquan{font-weight: 17px}
.youhuiquan a{color:#e24000;font-size: 15px; font-weight: bold}
</style>
<body>
	  <header class="mui-bar mui-bar-nav">
	      <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
        <h1 class="mui-title">确认订单</h1>
	  </header>

    <div class="mui-content">
        <div class="maijia">
            <p class="maijia-p1">买家信息</p>
            <p class="maijia-p2"><?php echo $address['address_contact']; ?><span><?php echo $address['address_mobile']; ?></span><span>默认</span></p>
            <p class="maijia-p3"><?php echo $address['address_info']; ?></p>
        </div>
        <div class="maijia">
            <p class="maijia-p1">商家发货</p>
            <div class="shangjia clear">
                <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                    <div class="mui-col-xs-3 left">
                        <img src="<?php echo $item['goods_face']; ?>" alt="" width="100%" height="90px">
                    </div>
                    <div class="mui-col-xs-6 left" style="padding-left: 7px">
                        <p class="shangjia-p1"><?php echo $item['goods_name']; ?></p>
                    </div>
                    <div class="mui-col-xs-3 left shangjia3">
                        <p class="ju">x<?php echo $item['item_number']; ?></p>
                        <p><span class="ju">￥<?php echo $item['offer_price']; ?></span><del class="ju-del">￥<?php echo $item['price']; ?></del></p>
                    </div>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="beizhu-qr">
                <p class="maijia-p1">备注</p>
                <textarea name=""></textarea>
            </div>
<!--             <div class="youhui maijia">
                <p class="youhuiquan">优惠券<a href="#" class="right">立即领取</a></p>
            </div> -->
        </div>
        <div class="xq-btn clear">
            <div class="mui-col-xs-8 left heji" style="text-align:left;padding-left: 25px;">合计:￥<?php echo $order['order_money']; ?> <span style="color:#000">（含运费<?php echo $freight; ?>元）</span> </div>
            <button class="mui-btn mui-col-xs-4 left" id="paynow">去结算</button>
        </div>
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

    $("#paynow").click(function(){
        onBridgeReady("<?php echo $order['order_sn']; ?>");
    })

    function onBridgeReady(order_sn){
      $.ajax({
        url:"<?php echo url('WechatPay/PayNow'); ?>",
        type:"GET",
        data:{order_sn:order_sn},
        success:function(res){
          WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {
               "appId":res.appId,           //公众号名称，由商户传入     
               "timeStamp":res.timeStamp,   //时间戳，自1970年以来的秒数     
               "nonceStr":res.nonceStr,     //随机串     
               "package":res.package,     
               "signType":"MD5",            //微信签名方式：     
               "paySign":res.paySign        //微信签名 
            },
            function(result){
              console.log(result);
              if(result.err_msg == "get_brand_wcpay_request:ok" ){
                 
              }
            });
        },error:function(){
          layer.msg('服务器错误，请刷新重试');
        }
      })
    }
</script>