<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:72:"/www/wwwroot/ledigou/public/../application/admin/view/school/update.html";i:1562919744;}*/ ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>编辑学校</title>
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
      <div class="layui-card-header">编辑学校</div>
      <div class="layui-card-body" style="padding: 15px;">
        <form class="layui-form" action="" lay-filter="component-form-group" id="myform">
          <input type="hidden" name="school_id" value="<?php echo $data['school_id']; ?>">

          <div class="layui-form-item">
            <label class="layui-form-label">学校名称</label>
            <div class="layui-input-block">
              <input type="text" name="school_name"  autocomplete="off" placeholder="请输入学校名称" class="layui-input" value="<?php echo $data['school_name']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">校区名称</label>
            <div class="layui-input-block">
              <input type="text" name="school_campus"  autocomplete="off" placeholder="请输入校区名称，非必填" class="layui-input" value="<?php echo $data['school_campus']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">地区</label>
            <div class="layui-input-block">
              <select name="school_province" class="provinceselect">
                <option value="0">选择省</option>
                <?php if(is_array($cityinfo['province']) || $cityinfo['province'] instanceof \think\Collection || $cityinfo['province'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cityinfo['province'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prov): $mod = ($i % 2 );++$i;?>
                  <option value="<?php echo $prov['id']; ?>" <?php if($data['school_province'] == $prov['id']): ?> selected="" <?php endif; ?>><?php echo $prov['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
              <select name="school_city" class="cityselect">
                <?php if(is_array($cityinfo['citys']) || $cityinfo['citys'] instanceof \think\Collection || $cityinfo['citys'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cityinfo['citys'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$city): $mod = ($i % 2 );++$i;?>
                  <option value="0" <?php if($data['school_city'] == $city['id']): ?> selected="" <?php endif; ?>><?php echo $city['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
              <select name="school_area" class="areaselect">
                <?php if(is_array($cityinfo['areas']) || $cityinfo['areas'] instanceof \think\Collection || $cityinfo['areas'] instanceof \think\Paginator): $i = 0; $__LIST__ = $cityinfo['areas'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$area): $mod = ($i % 2 );++$i;?>
                  <option value="0" <?php if($data['school_area'] == $prov['id']): ?> selected="" <?php endif; ?>><?php echo $area['name']; ?></option>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </select>
            </div>
          </div> 

          <div class="layui-form-item">
            <label class="layui-form-label">详细地址</label>
            <div class="layui-input-block">
              <input type="text" name="school_address"  autocomplete="off" placeholder="请输入学校详细地址" class="layui-input" value="<?php echo $data['school_address']; ?>">
            </div>
          </div>

          <div class="layui-form-item">
            <label class="layui-form-label">学校状态</label>
            <div class="layui-input-block">
              <input type="radio" name="school_status" value="1" title="正常" <?php if($data['school_status'] == 1): ?> checked="" <?php endif; ?>>
              <input type="radio" name="school_status" value="0" title="禁用" <?php if($data['school_status'] == 0): ?> checked="" <?php endif; ?>>
            </div>
          </div>

          <div class="layui-form-item layui-layout-admin">
            <div class="layui-input-block">
              <div class="layui-footer" style="left: 0;">
                <div class="layui-btn" lay-submit="" lay-filter="component-form-demo1">保存修改</div>
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