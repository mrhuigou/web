<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
$this->title ='订单物流跟踪';
?>
<?=fx\widgets\Header::widget(['title'=>$this->title])?>
<section class="veiwport  mb50">
    <div class="fool white clearfix">
        <em class="fl mr15 iconfont">&#xe61f;</em>
        <div class="fl w-per77 f12 lh150">
            <p class="f14 mb5">订单编号：<?=$model->order_no?> <em class="fr fb"><?=$model->orderStatus->name;?></em></p>
            <p class="f14 mb5">订单金额(含运费):￥<?= number_format($model->total,2);?></p>
            <p class="f14 mb5">配送公司: 每日惠购自送</p>
        </div>
    </div>
    <?php if($order_path){?>
    <ul class="filter  redFilter three bdb clearfix">
        <li ><p class="lh200 vm">配送人员</p><em class="vm fb"><?=$order_path['name']?$order_path['name']:"---"?></em></li>
        <li ><p class="lh200 vm">物流电话</p><em class="vm fb"><a href="tel:<?=$order_path['mobile']?$order_path['mobile']:"---"?>"><?=$order_path['mobile']?$order_path['mobile']:"---"?></a></em></li>
        <li ><p class="lh200 vm">车牌号码</p><em class="vm fb"><?=$order_path['vehicleNo']?$order_path['vehicleNo']:"---"?></em></li>
    </ul>
    <div class="p10 tc whitebg mt5">
        <div id="map" class="tc"style="height: 300px"></div>
    </div>
        <?php $this->beginBlock("JS_BaiDu")?>
        // 百度地图API功能
        var map = new BMap.Map("map");
        map.centerAndZoom(new BMap.Point(<?=$order_path['warehouselng']?>,<?=$order_path['warehouselat']?>), 15);
        map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
        map.addControl(new BMap.ScaleControl());                    // 添加比例尺控件
        map.addControl(new BMap.OverviewMapControl());              //添加缩略地图控件
        var warehousepoint=new BMap.Point(<?=$order_path['warehouselng']?>,<?=$order_path['warehouselat']?>);    //起点-仓库
        var bollpoint=new BMap.Point(<?=$order_path['bollng']?$order_path['bollng']:$order_path['warehouselng']?>,<?=$order_path['bollat']?$order_path['bollat']:$order_path['warehouselat']?>);
        var orderpoint=new BMap.Point(<?=$order_path['orderlng']?$order_path['orderlng']:$order_path['warehouselng']?>,<?=$order_path['orderlat']?$order_path['orderlat']:$order_path['warehouselat']?>);
        window.run = function (){
        map.clearOverlays();                        //清除地图上所有的覆盖物
        var driving = new BMap.DrivingRoute(map);    //创建驾车实例
        driving.search(warehousepoint, bollpoint);                 //第一个驾车搜索
        driving.search(bollpoint, orderpoint);                 //第二个驾车搜索
        driving.setSearchCompleteCallback(function(){
        var pts = driving.getResults().getPlan(0).getRoute(0).getPath();    //通过驾车实例，获得一系列点的数组
        var polyline = new BMap.Polyline(pts);
        map.addOverlay(polyline);
        var warehouseIcon = new BMap.Icon("/assets/images/ico/warehouse1.png", new BMap.Size(22,29));
        var carIcon = new BMap.Icon("/assets/images/ico/car.png", new BMap.Size(42,55));
        var orderIcon = new BMap.Icon("/assets/images/ico/user1.png", new BMap.Size(22,29));
        var warehouse_marker = new BMap.Marker(warehousepoint,{icon:warehouseIcon});  // 创建标注
        var order_marker = new BMap.Marker(orderpoint,{icon:orderIcon});  // 创建标注
        var car_marker = new BMap.Marker(bollpoint,{icon:carIcon});  // 创建标注
        map.addOverlay(warehouse_marker);               // 将标注添加到地图中
        map.addOverlay(order_marker);               // 将标注添加到地图中
        map.addOverlay(car_marker);               // 将标注添加到地图中
        map.setViewport([warehousepoint,bollpoint,orderpoint]);          //调整到最佳视野
        });
        }
        window.run();
        <?php $this->endBlock()?>
        <?php
        \yii\web\YiiAsset::register($this);
        $this->registerJsFile("https://api.map.baidu.com/api?v=2.0&ak=qrDz4DGnKDfg0WtdDkOYn0Op&s=1",['depends'=>\h5\assets\AppAsset::className()]);
        $this->registerJs($this->blocks['JS_BaiDu'],\yii\web\View::POS_END);
        ?>
    <?php }else{ ?>
        <figure class="info-tips whitebg gray9 p10">
            <i class="iconfont ">&#xe61f;</i>
            <figcaption class="m10"> 当前没有信息</figcaption>
        </figure>
    <?php } ?>

<!--    --><?//=\h5\widgets\Order\OrderShipping::widget()?>


</section>
