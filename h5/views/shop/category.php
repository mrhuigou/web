<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
/* @var $this yii\web\View */
$this->title ='商品分类';
?>
<header class="header w" id="header">
    <a class="pa-lt iconfont leftarr" href="javascript:history.back();" ></a>
    <h2 class="tc f18"><?= Html::encode($this->title) ?></h2>
    <a href="<?=\yii\helpers\Url::to(['/shop/index','shop_code'=>$store->store_code])?>" class="header-cate iconfont">&#xe602;</a>
</header>
<section class="veiwport">
            <a class="whitebg lh44 pl15 bdb db"  href="<?=\yii\helpers\Url::to(['shop/search','shop_code'=>$store->store_code])?>">全部商品</a>
            <?php foreach($model as $value){ ?>
                <a class="whitebg lh44 pl15 bdb db"  href="<?=\yii\helpers\Url::to(['shop/search','store_cate_code'=>$value['category_store_code'],'shop_code'=>$store->store_code])?>"><?=$value['name']?></a>
                    <?php if(isset($value['children'])) {
                        ?>
                            <?php
                            foreach ($value['children'] as $v) {
                                ?>
                                <a class="f5bg lh44 pl30 bdb db" href="<?=\yii\helpers\Url::to(['shop/search','store_cate_code'=>$v['category_store_code'],'shop_code'=>$store->store_code])?>"><?=$v['name']?></a>
                            <?php }?>
                    <?php }?>
            <?php }?>
</section>

<?=\h5\widgets\StoreMainMenu::widget(['code'=>$store->store_code])?>