<li class="pb10">
    <a href="<?=\yii\helpers\Url::to(['/club-try/detail',"id"=>$model->id])?>" class="db ">
    <p class="mb10 fb f12">
        <?=$model->title?>
    </p>
        <div class="lh200 fb clearfix">
            <p class="fl">价值：<span class="red  f14">￥<?=$model->product?$model->product->price:"---"?></span></p>
            <p class="fr">免费提供：<span class="red  f14"><?=$model->quantity?$model->quantity."份":"数量不限"?></span></p>
        </div>
        <img  data-original="<?=\common\component\image\Image::resize($model->image,640,270)?>" alt="<?=$model->title?>" class=" lazy db mb10 w">
    </a>
</li>