<div id="coupon_pop" style="display: none">
<a href="<?php echo \yii\helpers\Url::to(['/share/index'])?>">

        <div class="popr tc" style="
		width: 27rem;
		height: 41.45rem;
		background: url('../assets/images/new_guy_pop.jpeg') no-repeat center top;
		background-size:100%;
		padding-top:14rem;">
        </div>
</a>
</div>


    <?php $this->beginBlock("JS_NEW_GUY") ?>

    (function (doc, win) {
        var docEl = doc.documentElement,
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function () {
                var clientWidth = docEl.clientWidth;
                if (!clientWidth){
                    return;
                }else if(clientWidth>640){
                    docEl.style.fontSize =20 + 'px';
                }else{
                    docEl.style.fontSize = 20 * (clientWidth / 640) + 'px';
                }
            };
        if (!doc.addEventListener) return;
        win.addEventListener(resizeEvt, recalc, false);
        doc.addEventListener('DOMContentLoaded', recalc, false);
    })(document, window);



    layer.open({
        time:300000,
        content: $("#coupon_pop").html(),
        style: 'background:none; border:none;',
    });
    $("body").on('click','.layer-close', function () {  layer.closeAll();  });

    <?php $this->endBlock() ?>

<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_NEW_GUY'],\yii\web\View::POS_END);
?>