<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:79:"D:\phpstudy_pro\WWW\ledigou\public/../application/admin\view\index\welcome.html";i:1575525356;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style>
		html, body {
			height: 100%;
			width: 100%;
			margin:0px;
		}

		div {
			width: 100%;
			height: 100%;
			font-size: 2rem;
			box-sizing: border-box;
			padding-top:20%; 
			text-align: center;
			font-weight: 900;
		}

		#zzsc {

			width: 920px;

			margin: 100px auto;

		}

	</style>
</head>
<body>
	<div><?php echo $Greetings; ?><br/>Welcome To <?php echo \think\Session::get('config.website_ename'); ?></div>

	<div id="zzsc">
		<canvas id="canvas" width="920" height="400"></canvas>
	</div>
	<script type="text/javascript" src="/static/admin/js/jquery.js"></script>
	<script type="text/javascript" src="/static/admin/js/zzsc.js"></script>
</body>
</html>