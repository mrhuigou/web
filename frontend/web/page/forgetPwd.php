<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/global.css"  />
    <link rel="stylesheet" type="text/css" href="assets/stylesheets/layout.css"  />
    <link rel="stylesheet/less" type="text/css" href="assets/stylesheets/page.less" />

    <!--lessjs-->
    <script type="text/javascript" src="assets/script/less-1.7.3.min.js"></script>
</head>
<body>
    <div id="header" style="margin-top:0!important;">
        <!--top-->
        <div class="site-nav">
          <div class="site-nav-bd clearfix">
            <ul class="site-nav-bd-l clearfix">
              <li><a href="#">家润首页</a></li>
              <li><a href="#">请登录</a></li>
              <li><a href="#">免费注册</a></li>
            </ul>

            <ul class="site-nav-bd-r clearfix">
              <li class="sn-menu">
                <a class="menu-hd" rel="nofollow">我的家润<b></b></a>
                <div class="menu-bd">
                    <a rel="nofollow">已买到的宝贝</a>
                    <a rel="nofollow">已卖出的宝贝</a>
                </div>
              </li>
              <li class="sn-mobile">
                APP
                <div class="sn-qrcode">
                    <img src="http://www.365jiarun.com/catalog/view/theme/web3.0/images/weibo.png" alt="家润慧生活-微博二维码" width="120" height="120" class="db">
                    <b></b>
                </div>    
              </li>
              <li><a href="#">购物车78</a></li>
              <li><a href="#">收藏夹</a></li>
              <li><a href="#">联系客服</a></li>
              <li><a href="#">网站导航</a></li>
            </ul>
          </div>
        </div>  
        
        <!--logo和search-->
        <div class="header-box w990 bc">
            <div class="logo-search clearfix">
                <div class="logos fl">
                    <a href="#"><img src="http://www.365jiarun.com/catalog/view/theme/web3.0/images/2015/hlogo.jpg" alt="慧生活logo"></a>
                </div>
                <div class="search-box fr clearfix">
                    <input type="text" class="fl">
                    <button class="btn-search fr" type="submit">搜索</button>
                </div>
            </div>
        </div>
    </div>

    <div id="content" class="w990 bc pt50" style="min-height:500px;">
        <span class="f18">找回密码</span>
        
        <div class="password pb30">
            <div class="flowsteps">
                <ol class="num4">
                    <li class="first current"><span><i>1</i><em>输入账户名</em></span></li>
                    <li><span><i>2</i><em>验证身份</em></span></li>
                    <li><span><i>3</i><em>重置密码</em></span></li>
                    <li class="last"><span><i>4</i><em>完成</em></span></li>
                </ol>
            </div>
        </div>
        
        <table cellspacing="0" cellpadding="0" class="w tableP5">
            <tr>
                <td width="30%" align="right" class="f14">账户名</td>
                <td width="70%">
                    <input type="text" class="input vm" placeholder="手机号/会员名/邮箱">
                    <span class="vm gray6 pl10">忘记会员名？就使用手机号或邮箱吧！</span>
                </td>
            </tr>
            <tr>
                <td align="right" class="f14">验证码</td>
                <td>
                    <input type="text" class="input w50 vm">
                    <img src="http://www.365jiarun.com/common/checkcode" alt="tu" width="70" height="24 " class="vm">
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <button class="mbtn redbtn">下一步</button>
                </td>
            </tr>
        </table>

    </div>
    
    <div id="footer"></div>

    <script src="assets/script/jq.min.js"></script>
    <script>
        $(".tag-box .tag-tit li").mouseover(function(){
            var ind=$(this).index();
                _this=$(this);
            _this.addClass("current").siblings().removeClass("current");
            _this.parents(".tag-box").find(".itagList").eq(ind).show().siblings().hide();   
        });
    </script>
</body>
</html>