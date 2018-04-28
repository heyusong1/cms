<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:70:"F:\XAMP\htdocs\tp5.0\public/../application/admin\view\Shouyi\list.html";i:1524707105;s:72:"F:\XAMP\htdocs\tp5.0\public/../application/admin\view\public\header.html";i:1512561465;s:72:"F:\XAMP\htdocs\tp5.0\public/../application/admin\view\public\footer.html";i:1472804942;}*/ ?>

<p><a href="<?php echo url('admin/shouyi/add'); ?>">添加数据</a></p>
<table border="1" class="table table-bordered table-hover text-center">
<tr>
<th>序号</th>
<th></th>
<th colspan="2">操作</th>
</tr>
<?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
<tr>
<td><?php echo $i; ?></td>
<td><?php echo isset($item['name'])?$item['name']:''; ?></td>
<td><a href="<?php echo url('admin/shouyi/edit','id='.$item['id']); ?>">编辑</a></td>
<td><a href="<?php echo url('admin/shouyi/delete','id='.$item['id']); ?>" class="deleteOneData">删除</a></td>
</tr>
<?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php if((! \think\Request::instance()->isPjax())): ?>
        </div>
    </div>
</body>
</html>
<?php endif; ?>