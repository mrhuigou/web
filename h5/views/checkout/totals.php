<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/26
 * Time: 15:36
 */
$models=[];
$discount=0;
foreach($model as $total){
    if($total['code']=='coupon'){
        $discount=bcadd($discount,$total['value'],2);
        $models[$total['code']]=[
            'title'=>'优惠券金额',
            'value'=>number_format($discount,2,'.',''),
            'code'=>'coupon',
            'code_id'=>$total['code_id']
        ];
    }else{
        $models[]=$total;
    }
}

?>
<?php foreach ($models as $total) { ?>
    <p class="mb5 clearfix lh150">
        <span class="fr red fb">￥<em class="<?=$total['code']?>"><?=$total['value']?></em></span>
        <span class="fl fb"><?=$total['title']?>：</span>
    </p>

    <?php   if($total['code'] == 'coupon'){ ?>

   <?php foreach($model as $v){ ?>
            <p class="mb5 clearfix lh150">
        <?php if($v['code']=='coupon'){
            $coupon = \api\models\V1\Coupon::findOne(['coupon_id'=>$v['code_id']]);?>


            <span class="fr grey ">(￥<em class=""><?= $v['value'];?>)</em></span>
            <span class="fl ti2"><?= $coupon->name;?>：</span>

      <?php  }?>
            </p>
    <?php } ?>

    <?php } ?>

<?php } ?>
