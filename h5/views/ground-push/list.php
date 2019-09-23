<?php
?>
<div class="tit1 orgtit1">
    <h2>今日地推点列表</h2>
</div>
<div class="panel" id="CDT01">
    <?php if($point_lists){?>
        <?php foreach ($point_lists as $point_list){?>
            <div class="flex-col m5 br5 bg-wh ">
                <div class="flex-item-4 p5">

                    <p class="f12 mt5">负责人：<?php echo $point_list->contact_name;?></p>
                    <p class=" f10">电话：<?php echo $point_list->contact_tel;?></p>
                </div>
                <div class="flex-item-6 p5">
                    <span class="f18 red"><?php echo $point_list->name;?></span>
                    <p class=" f10">地址：<?php echo $point_list->address;?></p>
                </div>
                <div class="flex-item-2 tc bg-red  pt20 pb20">
                    <a href="javascript:void(0)" onclick="openLayer(<?php echo $point_list->id?>)" class="btn graybtn sbtn f12 coupon-item-btn" data-id="<?php echo $point_list->id?>" >登录</a>
                </div>
<!--                <div class=" bdb  mb10 whitebg tc">-->
<!--                    <img src="--><?php //echo \yii\helpers\Url::to(['/ground-push/get-point-qrcode','ground_push_point_id'=>$point_list->id],true)?><!--">-->
<!--                </div>-->
            </div>
        <?php }?>
    <?php }?>

</div>
<style>
    ..layui-m-layercont{
        padding: 20px 30px;
    }
</style>
<div id="layer_content" style="display: none" data-id="">
   <section class="login-bodyer">
            <input id="pass" type="text" class="input-text w bd" placeholder="请输入口令" >
    </section>
</div>
    <script>
<?php $this->beginBlock('JS_END') ?>


    function openLayer(point_id) {
        var pass=prompt("请输入口令","");
        if(pass){
            $.post('<?php echo \yii\helpers\Url::to(["/ground-push/ajax-pass"])?>',{point_id:point_id,pass:pass},function(data){
                if(data.status){
                   location.href=data.redirect;
                }else {

                    $.modal({
                        title: '错误',
                        text: data.msg,
                        buttons: [
                            { text: "确定", className: "default"}
                        ]
                    });
//                    layer.open({
//                            content: data.msg
//                            ,btn: '确定'
//                        }
//                    )
                }
            },'json');
        }
    }

<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_END);
?>
    </script>
