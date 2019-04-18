<?php session_start() ?>
<html>
    <head>
        <meta charset="utf8" />
        <title>register</title>
    </head>

    <body>
    <?php if(!$_SESSION['user']){ ?>
        <h1>register</h1>
        <form action="doLogin.php" method="POST" name="register">
            <input type="text" name="username" placeholder="请输入用户名" />
            <input type="password" name="password" placeholder="请输入密码" />
            <input type="submit" name="sub" value="快速登录" /> 
        </form>
    <?php }else{ ?>
        <h1>
            <?php echo $_SESSION['user']['username'].'已经登录'; ?>
        </h1>
    <?php } ?>
    </body>
</html>
