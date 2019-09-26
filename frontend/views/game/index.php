<html>
<head>
    <meta charset="utf-8">
    <title>家润网祝万相年会圆满成功</title>
    <link href="/game/style.css" type="text/css" rel="stylesheet" media="screen, projection">
    <link href="/game/style1.css" type="text/css" rel="stylesheet" media="screen, projection">
    <link href="/game/ui-dialog.css" type="text/css" rel="stylesheet" media="screen, projection">
</head>
<body>
<div class="readme hide">
    <p>▪ 按键盘空格键或者字母A可进行抽取,隐藏菜单请按ESC。</p>
    <p>▪ ESC菜单中高级设置可以设置参与人数，格子大小，重置抽奖数据等信息。</p>
    <p>▪ 点击已经中奖格子并输入点击的格子编号可取消该格子中奖状态，并清除中奖信息。</p>
    <p>▪ 中奖信息保存在本机上，如清理缓存活更换机器则记录将消失。</p>
    <p>▪ 请使用Chrome浏览器浏览，在投影仪上展示，请进入浏览器的全屏模式浏览。</p>
</div>
<div id="bodybg">
    <img src="bodybg.jpg" class="stretch"/>
</div>
<div class="model hide">
    <p><b>标题：</b><input type="text" id="title" class="ui-dialog-autofocus" value="" placeholder="输入标题，可以为空。"/></p>
    <p><b>人数：</b><input type="text" id="personCount" class="ui-dialog-autofocus" value="" placeholder="输入人数，请输入数字。"/></p>
    <p><b>方格宽：</b><input type="text" id="itemk"></p>
    <p><b>方格高：</b><input type="text" id="itemg"></p>
    <p class="line"><label><input type="radio" name="ms" style="width:15px;" checked value="50">眼花缭乱模式</label>
        <label><input type="radio" name="ms" style="width:15px;" value="300">惊心动魄模式</label></p>
    <p class="line"><label><input type="checkbox" id="reset" value="1"/>重置已产生的抽奖数据</label></p>
</div>
<a href="/" style="display:inline-block;vertical-align: middle;position: absolute;top:7px;left:20px;">
    <img src="https://www.mrhuigou.com/assets/images/logo3.png" alt="logo" width="300">
</a>
<div class="top" style="color: white;font-weight: bold;">家润网祝万相年会圆满成功</div>
<img src="/game/choujiang.png" width="140" height="140" style="position: absolute;top: 10px; right: 17px; cursor: pointer;" class="wcode">
<div class="wcode-pop" style="display: none;"><img src="choujiang.png"></div>
<div class="tips">扫一扫，抽奖 <span style="font-size: 14px;">&gt;&gt;</span></div>
<div class="clearfix">
    <div class="items">
        <div style="text-align: center;padding-top: 25px;" id="scan_img">
            <p style="font-size: 20px;font-weight: bold; color: #fff;margin-bottom: 20px;">微信扫一扫参与活动~</p>
            <img src="/game/choujiang.png">
        </div>
    </div>
    <div class="menu">
        <div class="help">
            按键盘空格键或者字母A可进行抽取,查看帮助请按F1,隐藏请按ESC。
            <a href="javascript:;" class="config">高级设定</a>
        </div>
        <div class="ss" style="display: none;">
            <ol></ol>
        </div>
    </div>
</div>

<!-- 礼品 -->
<a href="javascript:;">
    <img src="/game/img.png" class="game-gift">
</a>
<audio id="runingmic" class="hide" loop><source src="/game/runing.wav"></audio>
<audio id="pausemic" class="hide"><source src="/game/stop.mp3"></audio>
<script type="text/javascript" src="/game/jquery.js"></script>
<script type="text/javascript" src="/game/jquery.pulsate.min.js"></script>
<script type="text/javascript" src="/game/dialog-plus-min.js"></script>
<script type="text/javascript" src="/game/ipop.js"></script>
<script type="text/javascript" src="/game/app.js"></script>

</body>
</html>