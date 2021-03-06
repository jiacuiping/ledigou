<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/ledigou/public/../application/admin/view/school/create.html";i:1562916774;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>创建学校</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <link rel="stylesheet" href="/static/layui/css/layui.css" media="all">
  <link rel="stylesheet" href="/static/admin/css/admin.css" media="all">

  <style>

    .layui-form-select {
      width:33.33%;
      float: left;
    }

  </style>

</head>
<body>
  <div class="layui-fluid">
    <div class="layui-card">
      <div class="layui-card-header">创建学校</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">

          <div class="layui-form-item">
            <label class="layui-form-label">学校名称</label>
            <div class="layui-input-block">
              <input type="text" name="school_name"  autocomplete="off" placeholder="请输入学校名称" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">校区名称</label>
            <div class="layui-input-block">
              <input type="text" name="school_campus"  autocomplete="off" placeholder="请输入校区名称，非必填" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">地区</label>
            <div class="layui-input-block">
              <select name="school_province" class="provinceselect">
                <option value="0">选择省</option>
                <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prov): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $prov['id']; ?>"><?php echo $prov['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
              <select name="school_city" class="cityselect">
                <option value="0">选择市</option>
              </select>
              <select name="school_area" class="areaselect">
                <option value="0">选择区</option>
              </select>
            </div>
          </div> 

          <div class="layui-form-item">
            <label class="layui-form-label">详细地址</label>
            <div class="layui-input-block">
              <input type="text" name="school_address"  autocomplete="off" placeholder="请输入学校详细地址" class="layui-input">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">学校状态</label>
            <div class="layui-input-block">
              <input type="radio" name="school_status" value="1" title="正常" checked="">
              <input type="radio" name="school_status" value="0" title="禁用">
            </div>
          </div>

          <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
              <div class="layui-footer" style="left: 0;">
                <div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">确认创建</div>
                <!-- <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="/static/layui/layui.js"></script>  
  <script>
    layui.config({
      base: '/static/admin/' //静态资源所在路径
    }).extend({
      index: 'lib/index' //主入口模块
    }).use(['index', 'form', 'upload'], function(){
      var $ = layui.$
      ,layer = layui.layer
      ,upload = layui.upload
      ,form = layui.form;

      form.on('select', function(data){
        $.ajax({
          url:"<?php echo url('selectCity'); ?>",
          type:"GET",
          data:{adcode:data.value},
          success:function(res){
            if(res.code == 1){
              if(res.level != 'district')
                appendHtml(res.level,res.citys);
            }else
              layer.msg(res.msg);
          },error:function(){
            layer.msg('服务器错误，请稍后重试');
          }
        })
      });

      /* 监听提交 */
      form.on('submit(component-form-demo1)', function(data){
        $.ajax({
          url:"<?php echo url('create'); ?>",
          type:"POST",
          data:$("#myform").serialize(),
          success:function(res){
            layer.msg(res.msg);
            if(res.code)
              setTimeout(function(){window.parent.location.reload()},2000);
          },error:function(){
            layer.msg('服务器错误，请稍后重试');
          }
        })
      });

      function appendHtml(level,citys)
      {
        var object = level == 'province' ? $(".cityselect") : $(".areaselect");
        var html = level == 'province' ? "<option value='0'>选择市</option>" : "<option value='0'>选择区</option>";

        $(citys).each(function(index,item){
          html += "<option value="+item.id+">"+item.name+"</option>";
        });

        object.empty();
        object.append(html);

        form.render('select');
      }
    });
  </script>
</body>
</html>