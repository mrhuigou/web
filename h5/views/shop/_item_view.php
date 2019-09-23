    <div class="whitebg">
        <a href="<?=\yii\helpers\Url::to(['/product/index','product_base_id'=>$model->product->product_base_id],true)?>"><img src="<?=\common\component\image\Image::resize($model->product->productBase->image,200,200)?>" class="db w"></a>
        <div class="p5">
            <p class="mxh20"><?=$model->product->description->name?></p>
            <p class="org mxh20"><?=$model->product->description->meta_keyword?></p>
            <div class="pt5">
                <span class="red"><?=$model->product->productBase->price?></span>
            </div>
        </div>
    </div>