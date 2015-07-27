<?php
echo $_SERVER[''];
?>
<!DOCTYPE html>
<html lang="cn">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/ajax.js"></script>
    <title>Lakutata社团管理后台</title>
</head>
<body>
<nav class="navbar navbar-default">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li class="<?php echo $_SESSION['ann_manage'] ?>"><a href="<?php echo $_SESSION['ADMIN_SETTINGS'] ?>">社团公告管理</a></li>
            <li class="<?php echo $_SESSION['evt_manage'] ?>"><a href="<?php echo $_SESSION['ADMIN_EVENTS'] ?>">社团纪事管理</a></li>
            <li class="<?php echo $_SESSION['info_manage'] ?>"><a href="<?php echo $_SESSION['ADMIN_INFO'] ?>">社团基本信息设置</a></li>
            <li class="<?php echo $_SESSION['position_manage'] ?>"><a href="<?php echo $_SESSION['ADMIN_POSITIONS'] ?>">社团内部职位设置</a></li>
        </ul>
    </div>
</nav>