<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/18
 * Time: 10:13
 */
use yii\widgets\LinkPager;
?>

<?php if($model){ ?>
    <!--累计评价-->
    <table cellpadding="0" cellspacing="0" class="comments-list w">
    <?php foreach($model as $key=>$val){ ?>
        <tr>
            <td width="60%">
                <div><?=$val->text?></div>
                <span class="gray9"><?=$val->date_added?></span>
            </td>
            <td width="20%">
                <?php if($val->sku){
                    foreach($val->sku as $sku_str){
                        list($option_name,$option_value)=explode(":",$sku_str);
                 ?>
                        <p title="<?=$sku_str?>"><?=$option_name?>：<span class="gray9"><?=$option_value?></span></p>
                <?php
                    }
                }?>
            </td>
            <td>
               <span >（<?=$val->author?$val->author:'匿名'?> ）</span>
            </td>
        </tr>
        <?php } ?>
    </table>
    <div class="tc p20"><?= LinkPager::widget(['pagination' => $pages]); ?></div>
    <?php $this->beginBlock('J_Reviews') ?>
    $('#J_Reviews .pagination a').live('click',function(){
    $.ajax({
    url:$(this).attr('href'),
    success:function(html){
    $('#J_Reviews').html(html);
    }
    });
    return false;//阻止a标签
    });
    <?php $this->endBlock() ?>
    <?php
    \yii\web\YiiAsset::register($this);
    $this->registerJs($this->blocks['J_Reviews'],\yii\web\View::POS_END);
    ?>
    <?php }else{ ?>
    <!--没有记录时-->
    <div class="tc p20">Hi～该商品还没有人写评价呢</div>
    <?php } ?>
