<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:90:"C:\xampp\htdocs\yyyyy\yueguangshenjing\public/../application/admin\view\main\rulePage.html";i:1508491848;}*/ ?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 权限提示</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> <link href="__PUBLIC__/admin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="__PUBLIC__/admin/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="__PUBLIC__/admin/css/animate.css" rel="stylesheet">
    <link href="__PUBLIC__/admin/css/style.css?v=4.1.0" rel="stylesheet">
    <!--<script>if(window.top !== window.self){ window.top.location = window.location;}</script>-->

</head>

<body class="gray-bg">

<div class="lock-word animated fadeInDown">
</div>
<div class="middle-box text-center lockscreen animated fadeInDown">
    <div>
        <div class="m-b-md">
            <?php if(\think\Session::get('pic') != ''): ?>
            <img src="__PUBLIC__<?php echo \think\Session::get('pic'); ?>" style="border-radius:40px" width="80px" height="80px">
            <?php else: ?>
            <img src="__PUBLIC__/admin/headPic/a5.jpg" style="border-radius:40px" width="80px" height="80px">
            <?php endif; ?>
            <!--<img alt="image" class="img-circle circle-border" src="__PUBLIC__/admin/img/a1.jpg">-->
        </div>
        <?php if(\think\Session::get('name') != ''): ?>
        <h3>亲爱的 <font color="red"><?php echo \think\Session::get('name'); ?></font></h3>
        <?php else: ?>
        <h3>亲爱的 <font color="red" size="1">你还没有名字哦</font></h3>
        <?php endif; ?>
        <p>暂时没有该操作的权限</p>
    </div>
</div>

<!-- 全局js -->
<script src="__PUBLIC__/admin/js/jquery.min.js?v=2.1.4"></script>
<script src="__PUBLIC__/admin/js/bootstrap.min.js?v=3.3.6"></script>
</body>
</html>
