<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="/statics/css/bootstrap.min.css" rel="stylesheet">
<link href="/statics/css/font-awesome.css" rel="stylesheet">
<link href="/statics/css/checkbox.css" rel="stylesheet">
<link href="/statics/css/icheck.css" rel="stylesheet">
<link href="/statics/css/animate.min.css" rel="stylesheet">
<link href="/statics/css/style.css" rel="stylesheet">
<link href="/statics/js/layui/css/layui.css" rel="stylesheet">
<script src="/statics/js/jquery.js"></script>
<script src="/statics/js/bootstrap.js"></script>
<script src="/statics/js/layui/layui.js"></script>
<script>
layui.use('layer', function(){ 
var layer = layui.layer;
	layer.config({
	  //extend: 'espresso/style.css',
	  //skin: 'layer-ext-espresso'
	});
});
</script> 
</head>
<body class="gray-bg">

<div class="wrapper wrapper-content animated <?php echo C('Animation');?>">
  <div class="row">
    <div class="col-sm-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title"> <?php
$open=$_GET['open']; if($open==2){ ?>
<style>
.ibox-title{display:none !important;}
</style>
<?php
} ?>
<?php  $getMenu = D('Menu')->getmenunav(); if($getMenu) { ?>
	<?php
 foreach($getMenu as $v){ $app=$v['app']; $controller=$v['controller']; $action=$v['action']; $ajax=$v['ajax']; $width=$v['width']; $height=$v['height']; ?>
    &nbsp;
    <?php if($ajax==1): ?>
    <a class="J_alink menuid btn btn-<?php echo $action==ACTION_NAME ?'info':"default"; ?> btn-sm" href="<?php echo U("".$app."/".$controller."/".$action."",$v['parameter']);?>">
        <?php echo $v['name'] ;?>
    </a>
    <?php elseif($ajax==2): ?>
    <a class="menuid btn btn-default btn-sm" onClick="layerfrm('<?php echo $v['name'] ;?>','<?php echo $v['width'];?>px','<?php echo $v['height'];?>px','<?php echo U("".$app."/".$controller."/".$action."",$v['parameter']);?>')">
        <?php echo $v['name'] ;?>
    </a>
    <?php else: ?>
    <a class="menuid btn btn-<?php echo $action==ACTION_NAME ?'info':"default"; ?> btn-sm" href="<?php echo U("".$app."/".$controller."/".$action."",$v['parameter']);?>">
        <?php echo $v['name'] ;?>
    </a>
    <?php endif; ?>
    <?php  } } ?>
&nbsp;
<a class="btn btn-success btn-sm" onclick="reloadPage(window)"><i class="fa fa-refresh"></i> 刷新</a>
&nbsp;
 </div>
        <div class="ibox-content">
          <form class="form-horizontal m-t" action="<?php echo U('Tag/del');?>" method="post" id="commentForm">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="text-align:center;width:1%"> <input type="checkbox" class="i-checks" id="chkall"></th>
                  <th>标签</th>
                  <th>文章</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <?php if(is_array($data['list'])): $i = 0; $__LIST__ = $data['list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="float-e-margins">
                  	<td style="text-align:center;width:1%"><input type="checkbox" id="ck_id" class="i-checks" name="id[]"  value="<?php echo ($vo["id"]); ?>"></td>
                    <td><?php echo ($vo["tag"]); ?></td>
                    <td><?php echo ($vo["title"]); ?></td>
                    <td><a href="<?php echo U('Tag/del',array('id'=>$vo['id']));?>" class="btn mg0 btn-danger btn-xs J_del"><i class="fa fa-times"></i> 删除</a></td>
                  </tr><?php endforeach; endif; else: echo "" ;endif; ?>
              </tbody>
            </table>
            <?php echo ($data['page']); ?>
            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-3">
                <button class="btn btn-primary" type="submit">删除选择</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="/statics/js/validate/validate.js"></script> 
<script src="/statics/js/validate/messages.js"></script>
<script src="/statics/js/common.js"></script> 
<script src="/statics/js/icheck.js"></script> 
<script>
$(document).ready(function(){
	$(".i-checks").iCheck({
		checkboxClass:"icheckbox_square-green",
		radioClass:"iradio_square-green",
	})
	$('#chkall').on('ifChecked', function(event){
		$('input#ck_id').iCheck('check');
	});
	$('#chkall').on('ifUnchecked', function(event){
		$('input#ck_id').iCheck('uncheck');
	});
});
</script>
</body></html>