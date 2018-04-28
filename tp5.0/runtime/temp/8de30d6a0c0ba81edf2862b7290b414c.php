<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:75:"D:\wamp64\www\php\tp5.0\public/../application/admin\view\Usersize\edit.html";i:1514441443;s:75:"D:\wamp64\www\php\tp5.0\public/../application/admin\view\public\header.html";i:1512561465;s:75:"D:\wamp64\www\php\tp5.0\public/../application/admin\view\public\footer.html";i:1472804942;}*/ ?>

<form action="<?php echo url('usersize/edit_ok','id='.$data['id']); ?>" method="post" class="form-x">
<div class="form-group">
    <div class="label">
        <label>用户名</label>
    </div>
    <div class="field">
        <input type="text" name="name" class="input" value="<?php echo $data['name']; ?>">
    </div>
</div>
<div class="form-group">
    <div class="label">
        <label>密码</label>
    </div>
    <div class="field">
        <input type="text" name="password" class="input" value="<?php echo $data['password']; ?>">
    </div>
</div>
<div class="form-group">
    <div class="label">
        <label>性别</label>
    </div>
    <div class="field">
        <input type="text" name="sex" class="input" value="<?php echo $data['sex']; ?>">
    </div>
</div>
<div class="form-group">
    <div class="label">
        <label>年龄</label>
    </div>
    <div class="field">
        <input type="text" name="age" class="input" value="<?php echo $data['age']; ?>">
    </div>
</div>
<div class="form-group">
    <div class="label">
        <label>图片</label>
    </div>
    <div class="field">
        <input type="text" name="img" class="input" value="<?php echo $data['img']; ?>">
    </div>
</div>
<div class="form-group">
    <div class="label">
        <label>文本域</label>
    </div>
    <div class="field">
        <input type="text" name="text" class="input" value="<?php echo $data['text']; ?>">
    </div>
</div>
<div class="form-button">
    <input type="submit" value="提交" class="button bg-main">
</div>
</form>
<?php if((! \think\Request::instance()->isPjax())): ?>
        </div>
    </div>
</body>
</html>
<?php endif; ?>