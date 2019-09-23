<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel api\models\V1\AffiliateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户地址统计热力图';
$this->params['breadcrumbs'][] = $this->title;
?>

    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=qrDz4DGnKDfg0WtdDkOYn0Op"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>

    <style type="text/css">



        #container{height:700px;width:100%;}

    </style>

<div class="page-content">
    <div class="return-base-search">

        <?php $form = ActiveForm::begin([
            'action' => ['heat-maps'],
            'method' => 'get',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-4\">{input}</div>\n<div class=\"col-md-4\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-1 control-label','style'=>'width:90px;'],
            ],
        ]); ?>


        <?php  echo  $form->field($model, 'begin_date')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9999-99-99',
        ]);?>
        <?php  echo  $form->field($model, 'end_date')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '9999-99-99',
        ]);?>
        <?php echo $form->field($model,'low_price')->label("最低金额")?>
        <?php echo $form->field($model,'high_price')->label("最高金额")?>
        <div class="form-actions top fluid ">
            当前检索出订单数：<?php echo $count_order;?>
            <div class="col-md-offset-1 col-md-9" style="margin-left: 90px;">
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
<div id="container"></div>

</div>

    <?php $this->beginBlock('JS_END') ?>
    var map = new BMap.Map("container");          // 创建地图实例


    var point = new BMap.Point(120.39179227127, 36.067567063479);
    map.centerAndZoom(point, 15);             // 初始化地图，设置中心点坐标和地图级别
    map.enableScrollWheelZoom(); // 允许滚轮缩放

    var points = <?php if(!empty($data)){ echo json_encode($data);} ?>;


    if(!isSupportCanvas()){
        alert('热力图目前只支持有canvas支持的浏览器,您所使用的浏览器不能使用热力图功能~')
    }
    //详细的参数,可以查看heatmap.js的文档 https://github.com/pa7/heatmap.js/blob/master/README.md
    //参数说明如下:
    /* visible 热力图是否显示,默认为true
     * opacity 热力的透明度,1-100
     * radius 势力图的每个点的半径大小   
     * gradient  {JSON} 热力图的渐变区间 . gradient如下所示
     *	{
     .2:'rgb(0, 255, 255)',
     .5:'rgb(0, 110, 255)',
     .8:'rgb(100, 0, 255)'
     }
     其中 key 表示插值的位置, 0~1.
     value 为颜色值.
     */
    heatmapOverlay = new BMapLib.HeatmapOverlay({"radius":20});
    map.addOverlay(heatmapOverlay);
    heatmapOverlay.setDataSet({data:points,max:100});

    closeHeatmap();
    function setGradient(){
        /*格式如下所示:
         {
         0:'rgb(102, 255, 0)',
         .5:'rgb(255, 170, 0)',
         1:'rgb(255, 0, 0)'
         }*/
        var gradient = {};
        var colors = document.querySelectorAll("input[type='color']");
        colors = [].slice.call(colors,0);
        colors.forEach(function(ele){
            gradient[ele.getAttribute("data-key")] = ele.value;
        });
        heatmapOverlay.setOptions({"gradient":gradient});
    }
    //判断浏览区是否支持canvas
    function isSupportCanvas(){
        var elem = document.createElement('canvas');
        return !!(elem.getContext && elem.getContext('2d'));
    }
    $(document).ready(function(){
//显示热力地图
        heatmapOverlay.show();
    });

<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>