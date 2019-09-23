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
        ];
    }else{
        $models[]=$total;
    }
}
?>
<?php foreach ($models as $total) { ?>
    <p><?= $total['title'] ?> :<i class="org">￥<em class="<?=$total['code']?>"><?= number_format($total['value'], 2,'.','')?></em></i></p>
<?php } ?>
