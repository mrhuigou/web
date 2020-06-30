<div id="coupon_pop" style="display: none">
        <?php $affiliate_id = Yii::$app->session->get("from_affiliate_uid",0);?>
        <?php if($affiliate_id == 9 ){//地铁新用户?>

            <a href="<?php echo $share_url;?>">

                <div class="popr tc" style="
		width: 27rem;
		height: 41.45rem;
		background: url('../assets/images/new_guy_pop.jpeg') no-repeat center top;
		background-size:100%;
		padding-top:14rem;">
                </div>
            </a>
        <?php }else{?>
<a href="<?php echo \yii\helpers\Url::to(['/share/index'])?>">

        <div class="popr tc" style="
		width: 27rem;
		height: 41.45rem;
		background: url('../assets/images/new_guy_pop.jpeg') no-repeat center top;
		background-size:100%;
		padding-top:14rem;">
        </div>
</a>
   <?php }?>
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