<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model api\models\V1\Customer */

$this->title = '用户分布';
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-content">
    <!-- BEGIN STYLE CUSTOMIZER -->
    <?=\backend\widgets\Customizer::widget();?>
    <!-- END STYLE CUSTOMIZER -->
    <!-- BEGIN PAGE HEADER-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN PAGE TITLE & BREADCRUMB-->
            <h3 class="page-title">
                <?= Html::encode($this->title) ?> <small>监控、统计、分析</small>
            </h3>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <!-- END PAGE TITLE & BREADCRUMB-->
        </div>
    </div>

<!--百度地图-->
    <div style="width:100%; height: 500px;" id="allmap"></div>
    <form class="form-inline">
        <input id="search-box" type="text" class="form-control" placeholder="搜索关键词...">
        <button type="button" class="btn btn-primary">搜索</button>
    </form>
    <div id="r-result"></div>
<!--腾讯地图-->
    <script type="text/javascript">
        /*function init() {
          // var myLatlng = new qq.maps.LatLng(39.916527,116.397128);
          var myLatlng = new qq.maps.LatLng(36.094406,120.369557);
          var myOptions = {
            zoom: 13,
            center: myLatlng,
            mapTypeId: qq.maps.MapTypeId.ROADMAP
          }
          var map = new qq.maps.Map(document.getElementById("container"), myOptions);
          var customer_json = <?php // echo json_encode($model)?>;
          for(var data in customer_json){
            var latLng = new qq.maps.LatLng(customer_json[data].latitude,customer_json[data].longitude);
            var marker = new qq.maps.Marker({
                map:map,
                position: latLng
            });
            }
        }
          
        function loadScript() {
          var script = document.createElement("script");
          script.type = "text/javascript";
          script.src = "http://map.qq.com/api/js?v=2.exp&key=HNBBZ-MBUHD-AAV46-PPQCE-TW5VO-FVFN6&callback=init";
          document.body.appendChild(script);
        }
          
        window.onload = loadScript;;*/
    </script>
    <!-- <div style="width:100%; height: 650px;" id="container"></div> -->
</div>


<?php $this->beginBlock("JS_Block")?>

// 百度地图API功能
        var map = new BMap.Map("allmap");  // 创建Map实例
        map.centerAndZoom("青岛",13);      // 初始化地图,用城市名设置地图中心点
        map.enableScrollWheelZoom(true);
        // function addMarker(point){
        //     var marker = new BMap.Marker(point);
        //     map.addOverlay(marker);
        // }
        // var customer_json = <?php // echo json_encode($model)?>;
        // for(var data in customer_json){
        //     var point = new BMap.Point(customer_json[data].longitude,customer_json[data].latitude);
        //     addMarker(point);
        // }
        $("button").on("click",function(){
             //var local = new BMap.LocalSearch(map, {
               // renderOptions: {map: map, panel: "r-result"}
            //});
             //local.search($("#search-box").val());

             var myGeo = new BMap.Geocoder();
            // 将地址解析结果显示在地图上,并调整地图视野
            myGeo.getPoint(($("#search-box").val()), function(point){
                if (point) {
                    $.post('/customer/find',{lng: point.lng, lat: point.lat, keyword:$("#search-box").val(), tag:'加油站'},function(data){
                        console.log(data);
                    });
                }else{
                    // alert("您选择地址没有解析到结果!");
                }
            }, "青岛市");
        });
        

<?php $this->endBlock()?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJsFile("http://api.map.baidu.com/api?v=2.0&ak=qrDz4DGnKDfg0WtdDkOYn0Op");
$this->registerJs($this->blocks['JS_Block'],\yii\web\View::POS_END);