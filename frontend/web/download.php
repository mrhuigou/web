<?php
function get_device_type(){
 $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
 $type = 'other';
 if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
  $type = 'ios';
 }
 if(strpos($agent, 'android')){
  $type = 'android';
 }
 return $type;
}
if(get_device_type()=='android'){
 $url="http://7xr1vh.com1.z0.glb.clouddn.com/7b0f978b1973514bdf5e57ef6f8f25f1_d";
}else{
 $url="itms-apps://itunes.apple.com/us/app/jia-run-hui-sheng-huo-yong/id1099025648?l=zh&ls=1&mt=8";
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
 <meta charset="UTF-8">
 <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1" />
 <title></title>
 <style type="text/css">
  body,div,span,applet,object,iframe,h1,h2,h3,h4,h5,h6,p,blockquote,pre,a,abbr,acronym,address,big,cite,code,del,dfn,em,img,ins,kbd,q,s,samp,small,strike,strong,sub,sup,tt,var,b,u,i,center,dl,dt,dd,ol,ul,li,fieldset,form,label,legend,table,caption,tbody,tfoot,thead,tr,th,td,article,aside,canvas,details,embed,figure,figcaption,footer,header,hgroup,menu,nav,output,ruby,section,summary,time,mark,audio,video{margin:0;padding:0;border:0;font-size:100%;font:inherit;vertical-align:baseline}
  article,aside,details,figcaption,figure,footer,header,hgroup,menu,nav,section{display:block}
  html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}
  html,body{width:100%}
  body{font-family:Arial,Helvetica,sans-serif;line-height:1.6;background:#fff;font-size:14px;color:#333;-webkit-font-smoothing:antialiased;-webkit-text-size-adjust:100%;text-rendering:optimizeLegibility}
  img,a img,img:focus{border:0;outline:0}
  img{max-width:100%;height: auto;}
  textarea,input,a,textarea:focus,input:focus,a:focus{outline:none}
  h1,h2,h3,h4,h5,h6{font-weight:normal;margin-bottom:15px;line-height:1.4}
  h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{font-weight:inherit;color:#444444}
  body{font-size: 62.5%; font-family: 'Microsoft Yahei','\5FAE\8F6F\96C5\9ED1',Arial,'Hiragino Sans GB','\5B8B\4F53'; line-height: 1.6}
  li{list-style: none;}

  body,div,p,h2,img{margin:0;padding:0;font-size:12px;font-family:simsun;color:#333;}
  img{border:0;}
  p{margin-bottom:5px;line-height:150%;}
  a{outline:none;text-decoration:none;}
  .tc{text-align:center;}
  .db{display:block;}
  .mt5{margin-top:10px;}
  .mt10{margin-top:10px;}
  .p15{padding:15px;}

  .m_top{background:url("images/m.jpg") no-repeat center top;background-size:cover;padding-top:220px;padding-bottom:30px;}
  .m_btn{display:block;padding:7px 0;margin-bottom:10px;width:120px;text-align:center;border-radius:20px;background:#2092b2;color:#fff;font-size:16px;margin-left:auto;margin-right:auto;font-weight:bold;box-shadow:1px 2px 2px rgba(0,0,0,0.2);}
  .m_er{text-align:center;margin-bottom:50px;margin-top:40px;}
  .m_tit{padding:8px 15px;background-color:#2092b2;color:#fff;font-size:14px;font-weight:bold;}
  #weixin-tip{display:none; position: fixed; left:0; top:0; background: rgba(0,0,0,0.8); filter:alpha(opacity=80); width: 100%; height:100%; z-index: 100;}
  #weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%; position: relative;}
  #weixin-tip .close{
   color: #fff;
   padding: 5px;
   font: bold 20px/20px simsun;
   text-shadow: 0 1px 0 #ddd;
   position: absolute;
   top: 0; left: 5%;
  }
 </style>
</head>
<body>
<div class="m">
 <div class="m_top">
  <a href="<?=$url?>" class="m_btn" id="J_weixin">立即下载</a>
  <p class="m_er">
   扫描二维码直接下载 <br />
   <img src="images/er.png" alt="二维码" width="115" height="115" class="mt10" />
  </p>
 </div>

 <h2 class="m_tit">简介</h2>
 <p class="p15">家润同城APP是为手机用户推出的满足其生活消费和线上购物需求的软件，具有查看附近的生活优惠信息、商品搜索、浏览、购买、支付、 收藏、物流查询、语音客服等在线功能，成为了用户方便快捷的生活消费入口。
 </p>
 <h2 class="m_tit mt5">功能</h2>
 <p class="p15">会员注册、商品展示、语音搜索、在线促销、移动支付、用户定位、在线客服、一键分享</p>
 <div id="weixin-tip"><p><img src="images/live_weixin.png" alt="微信打开"/><span id="close" title="关闭" class="close">×</span></p>
 </div>


</div>
<script type="text/javascript">

 var is_weixin = (function() {

  var ua = navigator.userAgent.toLowerCase();

  if (ua.match(/MicroMessenger/i) == "micromessenger") {

   return true;

  } else {

   return false;

  }

 })();

 window.onload = function(){

  var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;

  var btn = document.getElementById('J_weixin');
  var tip = document.getElementById('weixin-tip');
  var close = document.getElementById('close');

  if(is_weixin){

   btn.onclick = function(e){

    tip.style.height = winHeight + 'px';

    tip.style.display = 'block';

    return false;

   }

   close.onclick = function(){

    tip.style.display = 'none';

   }

  }

 }
</script>
</body>
</html>

