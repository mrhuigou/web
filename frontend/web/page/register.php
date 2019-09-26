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
                    <img src="http://www.mrhuigou.com/catalog/view/theme/web3.0/images/weibo.png" alt="每日惠购-微博二维码" width="120" height="120" class="db">
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
                    <a href="#"><img src="http://www.mrhuigou.com/catalog/view/theme/web3.0/images/2015/hlogo.jpg" alt="慧生活logo"></a>
                </div>
                <div class="search-box fr clearfix">
                    <input type="text" class="fl">
                    <button class="btn-search fr" type="submit">搜索</button>
                </div>
            </div>
        </div>
    </div>

    <div id="content" class="w990 bc pt50">
        <div class="steps">
            <ol>
                <li class="active"><i>1</i><span>设置登录名</span></li>
                <li><i>2</i><span>填写账户信息</span></li>
                <li><i class="iconfont">&#xe60a;</i><span>注册成功</span></li>
            </ol>
        </div>
        <div class="reg-box bc">  
            <div class="tag-box">
                <ul class="tag-tit clearfix">
                    <li class="current"><a href="#">手机注册</a></li>
                    <li><a href="#">邮箱注册</a></li>
                </ul>
                <div class="tag-con">
                    <table cellpadding="0" cellspacing="0" class="w itagList f14 tableP5">
                        <tr>
                            <td width="12%" align="right">手机</td>
                            <td><input type="text" class="linput placeholder w200" /></td>
                        </tr>
                        <tr>
                            <td align="right">密码</td>
                            <td><input type="password" class="linput w200" /></td>
                        </tr>
                        <tr>
                            <td align="right">确认密码</td>
                            <td><input type="password" class="linput w200" /></td>
                        </tr>
                        <tr>
                            <td align="right">验证码</td>
                            <td>
                                <input type="text" class="linput vm w200" />
                                <a href="###" class="btn lbtn graybtn vm">获取语音验证码</a>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td colspan="2"><label><input type="checkbox" class="vm_2"> 我已阅读并同意 <a href="#" class="green">家润网上商城用户协议</a></label></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><a href="javascript:void(0)" id="btnlogin" class="btn lbtn redbtn w100">确认注册</a></td>
                        </tr>
                    </table>
                    <table cellpadding="0" cellspacing="0" class="w itagList f14 tableP5" style="display:none;">
                        <tr>
                            <td width="12%" align="right">邮箱</td>
                            <td><input type="text" class="linput placeholder w200" /></td>
                        </tr>
                        <tr>
                            <td align="right">密码</td>
                            <td><input type="password" class="linput w200" /></td>
                        </tr>
                        <tr>
                            <td align="right">确认密码</td>
                            <td><input type="password" class="linput w200" /></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><label><input type="checkbox" class="vm_2"> 我已阅读并同意 <a href="#" class="green">家润网上商城用户协议</a></label></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><a href="javascript:void(0)" id="btnlogin" class="btn lbtn redbtn w100">确认注册</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
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