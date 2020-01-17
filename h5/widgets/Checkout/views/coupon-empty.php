<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/27
 * Time: 16:54
 */
?>
<script>
    <?php $this->beginBlock('JS_END') ?>

    var sub_total1 = <?= $sub_total;?>;
    var shipping1 = <?= $shipping;?>;

    if(shipping1 > 0 && sub_total1 < 68){
        layer.open({
            type: 1,
            closeBtn: 2,
            title: false,
            shadeClose: true,
            shade:0.3,
            content: $('#layerCon').html(),
            // btn: ['去结算']
        });
        $(".layer-close").click(function(){
            layer.closeAll();
        })
        $('#shipping_script').show();
        $('#free_return').show();
    }
    <?php $this->endBlock() ?>
</script>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
?>
