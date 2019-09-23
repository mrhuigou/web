<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <title>登录认证</title>
</head>
<body>
<script type="text/javascript">
    apiready = function() {
        //关闭认证页面
        api.sendEvent({
            name : 'login_out',
        });
    }
</script>
</body>
</html>