<div class="floor-container clearfix pb5">
    <!--分类导航-->
    <div class="w210 fl">
        <div class="category-menu">
            <div class="category-nav">
                <?php if(!empty($category_arrays)){?>
                <ul class="menu-nav-container">
                        <?php foreach($category_arrays as $sec_categorys){?>
                    <li>
                                <h3 class="clearfix">
                                    <p class="fl">
                                            <a target="_blank" href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$sec_categorys->category_display_id])?>"><?php echo $sec_categorys->description->name;?></a>
                                    </p>
                                    <i class="fr iconfont"></i>
                                </h3>
                        <?php if(!empty($sec_categorys->child)){?>
                            <div class="menu-content-panel clearfix" style="display: none;">
                                <div class="menu-content-panel-l fl">
                            <?php foreach($sec_categorys->child as $sec_category){?>
                                <h2><a href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$sec_category->category_display_id])?>" class="gray6 fr">更多</a>
                                    <span><a href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$sec_category->category_display_id])?>"><?php echo $sec_category->description->name;?></a></span></h2>
                                    <p>
                                        <?php if(!empty($sec_category->child)){?>
                                            <?php foreach($sec_category->child as $third_category){?>
                                                    <a href="<?php echo \yii\helpers\Url::to(['product/category','cat_id'=>$third_category->category_display_id])?>"><?php echo $third_category->description->name;?></a>
                                                <?php }?>
                                        <?php }?>
                                    </p>
                            <?php }?>
                                </div>
                                <?php if($sec_categorys->brand){?>
                                <div class="menu-content-panel-r fr clearfix">
                                    <?php foreach($sec_categorys->brand as $brand){ ?>
                                    <a href="<?=\yii\helpers\Url::to(['/category/index','brand_id'=>$brand->brand_id])?>"><img width="95" height="45" class="db"  src="<?=\common\component\image\Image::resize($brand->image,95,45)?>"></a>
                                    <?php } ?>
                                    </div>
                                <?php }?>
                            </div>
                            <?php }?>
                    </li>
                        <?php }?>
                </ul>
                <?php }?>
            </div>
        </div>
    </div>
    <!--主幻灯片区-->
    <div class="slideBox fl banner800">
        <?php if(!empty($data['silde_position_data'])){?>
        <div class="hd">
            <ul>
                <?php $count = count($data['silde_position_data']);?>
                <?php for($i = 0; $i< $count ;$i++ ){?>
                    <li><?php echo $i+1; ?></li>
                <?php }?>
            </ul>
        </div>
        <div class="bd">
            <ul>
                <?php foreach($data['silde_position_data'] as $banner){?>
                    <li><a href="<?php echo $banner->link_url;?>" target="_blank"><img class="db lazy scaleimg" src="/assets/images/placeholder.png" data-url="<?=\common\component\image\Image::resize($banner->source_url,$banner->width,$banner->height)?>" alt="<?php echo $banner->title;?>" width="800" height="330" /></a></li>
                <?php }?>
            </ul>
        </div>
        <?php }?>
    </div>

    <div class="w190 fr whitebg h330">
        <div class="note-list">
            <h2 class="p5 pl10 bd_dashB f14 mb5">家润快报</h2>
            <ul>
                <?php if($data['news']){?>
                <?php foreach($data['news'] as $news){?>
                <li><i class="iconfont"></i><a href="<?=$news->link_url?$news->link_url:\yii\helpers\Url::to(['/news/index','news_id'=>$news->news_id])?>" class="<?=$news->highlight?'red':''?>"><?=$news->title?></a></li>
                <?php } ?>
                <?php }else{ ?>
                <li><i class="iconfont"></i><a href="#">还没有任何信息</a></li>
                <?php } ?>
            </ul>
        </div>
        <div class="bd_dashT tc pt15">
            <img src="/assets/images/wx.jpg" alt="" width="75" height="75">
            <p>关注公众号<br>分享还可抢红包</p>
        </div>
    </div>
</div>


<?php if(!empty($data['focus_position_data'])){?>
<div class="clearfix hotlist-wrap sld pr">
    <div class="hd">
        <span class="next iconfont fb gray9">&#xe608;</span>
        <span class="prev iconfont fb gray9">&#xe609;</span>
    </div>

    <div class="bd mt5" style="border: 0;">
        <ul class="clearfix">
            <?php foreach($data['focus_position_data'] as  $value){?>
            <li class="hotlist clearfix">
                <a href="<?=\yii\helpers\Url::to(['/product/index','product_code'=>$value->product_code,'shop_code'=>$value->store_code])?>" class="fl scaleimg"><img src="<?=\common\component\image\Image::resize($value->source_url,145,145)?>" class="db" width="145" height="145"></a>
                <div class="p10 pb5 fr w125">
                    <p class="f14 fb mb5" style="max-height: 45px;overflow: hidden;"><?=$value->product?$value->product->description->name:$value->title?></p>
                    <div class="green p5 bdt bdb">
                        <p class="mxh20"><?=$value->product?$value->product->description->meta_description:''?></p>
                    </div>
                    <p class="red mt5">
                        <span class="del fr grayc">￥<?=$value->product?$value->product->price:''?></span>
                        <span class="f14">￥</span><span class="f16 fb"><?=$value->product?$value->product->getPrice():''?></span>
                    </p>
                </div>
            </li>
            <?php }?>
        </ul>
    </div>
</div>
<?php }?>
<?php $this->beginBlock('JS_END') ?>
//楼层分类导航
$(".category-menu-nav dt").click(function(e){
var _this=$(this),
odt=_this.parents("dl").siblings("dl").find("dt"),
dd=_this.next();
if(dd.is(":visible")){
_this.find('i').html("&#xf000e;").removeClass('jian');
dd.hide();
odt.show();
}else{
_this.find('i').html("&#xf000d;").addClass('jian');
dd.show();
odt.hide();
}
})
$(".category-menu-nav dd a:even").each(function(){
$(this).css({"paddingLeft":"20px","paddingRight":"2px"})
})
$(".category-menu-nav dd a:odd").each(function(){
$(this).css({"paddingLeft":"2px","paddingRight":"20px"})
})
jQuery(".hotlist-wrap").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"leftLoop",autoPlay:true,scroll:4,vis:4});
<?php $this->endBlock() ?>
<?php
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>
