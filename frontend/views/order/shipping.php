<?php
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
$this->title = '订单物流跟踪';
$this->params['breadcrumbs'][] = ['label' => '用户中心', 'url' => ['/account']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="" style="min-width:1100px;">
    <div class="w1100 bc ">
        <!--面包屑导航-->
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            'tag' => 'p',
            'options' => ['class' => 'gray6 pb5 pt5'],
            'itemTemplate' => '<a class="f14">{link}</a> > ',
            'activeItemTemplate' => '<a class="f14">{link}</a>',
        ]) ?>
        <div class="bc  clearfix simsun">
            <div class="fl w100 mr10 menu-tree">
                <?= frontend\widgets\UserSiderbar::widget() ?>
            </div>
            <div class="fl w990 ">
                <div class="whitebg">
                    <h2 class="titStyle3 f14 green graybg2">订单编号：<span
                            class="pr30"><?php echo $model->order_no; ?></span>
                        状态： <span class="org pr30"><?= $model->orderStatus->name; ?></span>
                        下单时间：<?php echo date('Y-m-d H:i:s', strtotime($model->date_added)); ?> </h2>
                    <?php if ($order_path) { ?>
                        <ul class="graybg row p10 bdb ">
                            <li style="height: 4em" class="col-3"><p class="lh200 vm">配送人员</p><em
                                    class="vm fb"><?= $order_path['name'] ? $order_path['name'] : "---" ?></em></li>
                            <li style="height: 4em" class="col-3"><p class="lh200 vm">物流电话</p><em
                                    class="vm fb"><?= $order_path['mobile'] ? $order_path['mobile'] : "---" ?></em></li>
                            <li style="height: 4em" class="col-3"><p class="lh200 vm">车牌号码</p><em
                                    class="vm fb"><?= $order_path['vehicleNo'] ? $order_path['vehicleNo'] : "---" ?></em>
                            </li>
                        </ul>
                        <div class="p5 tc whitebg ">
                            <div id="map" class="tc" style="height: 300px"></div>
                        </div>
                        <?php $this->beginBlock("JS_BaiDu") ?>
                        // 百度地图API功能
                        var map = new BMap.Map("map");
                        map.centerAndZoom(new BMap.Point(<?= $order_path['warehouselng'] ?>,<?= $order_path['warehouselat'] ?>), 15);
                        map.addControl(new BMap.NavigationControl());               // 添加平移缩放控件
                        map.addControl(new BMap.ScaleControl());                    // 添加比例尺控件
                        map.addControl(new BMap.OverviewMapControl());              //添加缩略地图控件
                        var warehousepoint=new BMap.Point(<?= $order_path['warehouselng'] ?>,<?= $order_path['warehouselat'] ?>);    //起点-仓库
                        var bollpoint=new BMap.Point(<?= $order_path['bollng'] ? $order_path['bollng'] : $order_path['warehouselng'] ?>,<?= $order_path['bollat'] ? $order_path['bollat'] : $order_path['warehouselat'] ?>);
                        var orderpoint=new BMap.Point(<?= $order_path['orderlng'] ? $order_path['orderlng'] : $order_path['warehouselng'] ?>,<?= $order_path['orderlat'] ? $order_path['orderlat'] : $order_path['warehouselat'] ?>);

                        window.run = function (){
                        map.clearOverlays();                        //清除地图上所有的覆盖物
                        var driving = new BMap.DrivingRoute(map);    //创建驾车实例
                        driving.search(warehousepoint, bollpoint);                 //第一个驾车搜索
                        driving.search(bollpoint, orderpoint);                 //第二个驾车搜索
                        driving.setSearchCompleteCallback(function(){
                        var pts = driving.getResults().getPlan(0).getRoute(0).getPath();    //通过驾车实例，获得一系列点的数组
                        var polyline = new BMap.Polyline(pts);
                        map.addOverlay(polyline);
                        var warehouseIcon = new BMap.Icon("/assets/images/shipping/warehouse1.png", new BMap.Size(22,29));
                        var carIcon = new BMap.Icon("/assets/images/shipping/car.png", new BMap.Size(42,55));
                        var orderIcon = new BMap.Icon("/assets/images/shipping/user1.png", new BMap.Size(22,29));
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
                        <?php $this->endBlock() ?>
                        <?php
                        \yii\web\YiiAsset::register($this);
                        $this->registerJsFile("https://api.map.baidu.com/api?v=2.0&ak=qrDz4DGnKDfg0WtdDkOYn0Op&s=1", ['depends' => \frontend\assets\AppAsset::className()]);
                        $this->registerJs($this->blocks['JS_BaiDu'], \yii\web\View::POS_END);
                        ?>
                    <?php } else { ?>
                        <div class="whitebg p10 lh200 tc"> 当时没有任何记录...</div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
