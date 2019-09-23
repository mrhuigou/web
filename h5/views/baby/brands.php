<?php ?>
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/9/25
 * Time: 上午9:37
 */
<article>

            <section>

                <div class="content" style="top: 38px;">

                    <div class="tab-box tab-box2">
                        <ul class="tab-tit filter filter-blue four pf-t bs-b">
                            <li class="tab-tit-tri cur"><a href="#n1">奶粉辅食</a></li>
                            <li class="tab-tit-tri"><a href="#n2">尿裤湿巾</a></li>
                            <li class="tab-tit-tri"><a href="#n3">用品洗护</a></li>
                            <li class="tab-tit-tri"><a href="#n4">妈妈专区</a></li>
                        </ul>
                    </div>


                    <!-- 奶粉辅食 -->
                    <div class="tit1 tit1-green" id="n1">
                        <h2>奶粉辅食</h2>
                    </div>

                    <?php if($f11){?>
                        <?php foreach (array_slice($f11,0,1) as $key => $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb10">
                            </a>
                        <?php }?>
                    <?php }?>


                    <div class="tab-box tab-boxList pt10 pr5 bg-wh">
                        <div class="tab-tit">


                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray btn-red fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F01">奶粉</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">辅食</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">营养品</a>
                        </div>

                        <div class="tab-con">
                            <div class="row pb5 tab-con-list">
                                <?php if($f12){?>
                                    <?php foreach (array_slice($f12,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                            <div class="row pb5 tab-con-list" style="display: none;">
                                <?php if($f13){?>
                                    <?php foreach (array_slice($f13,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                            <div class="row pb5 tab-con-list" style="display: none;">
                                <?php if($f14){?>
                                    <?php foreach (array_slice($f14,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                        </div>

                    </div>


                    <!-- 尿裤湿巾 -->
                    <div class="tit1 tit1-org" id="n2">
                        <h2>尿裤湿巾</h2>
                    </div>

                    <?php if($f21){?>
                        <?php foreach (array_slice($f21,0,1) as $key => $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb10">
                            </a>
                        <?php }?>
                    <?php }?>

                    <div class="tab-box tab-boxList pt10 pr5 bg-wh">
                        <div class="tab-tit">
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray btn-red fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F01">尿裤</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">湿巾</a>
                        </div>

                        <div class="tab-con">
                            <div class="row pb5 tab-con-list">
                                <?php if($f22){?>
                                    <?php foreach (array_slice($f22,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                            <div class="row pb5 tab-con-list" style="display: none;">
                                <?php if($f23){?>
                                    <?php foreach (array_slice($f23,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                        </div>

                    </div>


                    <!-- 用品洗护 -->
                    <div class="tit1 tit1-blue" id="n3">
                        <h2>用品洗护</h2>
                    </div>


                    <?php if($f31){?>
                        <?php foreach (array_slice($f31,0,1) as $key => $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb10">
                            </a>
                        <?php }?>
                    <?php }?>

                    <div class="tab-box tab-boxList pt10 pr5 bg-wh">
                        <div class="tab-tit">
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray btn-red fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F01">喂养用品</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">洗护用品</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">洗浴类</a>
                        </div>

                        <div class="tab-con">
                            <div class="row pb5 tab-con-list">
                                <?php if($f32){?>
                                    <?php foreach (array_slice($f32,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                            <div class="row pb5 tab-con-list" style="display: none;">
                                <?php if($f33){?>
                                    <?php foreach (array_slice($f33,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                        </div>

                    </div>


                    <!-- 妈妈用品 -->
                    <div class="tit1 tit1-red" id="n4">
                        <h2>妈妈用品</h2>
                    </div>

                    <?php if($f41){?>
                        <?php foreach (array_slice($f41,0,1) as $key => $value){?>
                            <a href="<?= $value->link_url ?>">
                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w mb10">
                            </a>
                        <?php }?>
                    <?php }?>

                    <div class="tab-box tab-boxList pt10 pr5 bg-wh">
                        <div class="tab-tit">
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray btn-red fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F01">孕妇护肤营养</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">妈咪用品</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">孕妇内衣</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">产后塑身</a>
                            <a href="javascript:void(0)" class="btn btn-xs btn-pill btn-gray fl ml10 mb10 tab-tit-tri" data-content="H5-2LXS-1F02">绘本</a>
                        </div>

                        <div class="tab-con">
                            <div class="row pb5 tab-con-list">
                                <?php if($f42){?>
                                    <?php foreach (array_slice($f42,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>

                            </div>

                            <div class="row pb5 tab-con-list" style="display: none;">
                                <?php if($f43){?>
                                    <?php foreach (array_slice($f43,0,10) as $key => $value){?>
                                        <div class="col-5 p5">
                                            <a href="<?= $value->link_url ?>">
                                                <img src="<?= \common\component\image\Image::resize($value->source_url) ?>" class="w img-circle bd">
                                            </a>
                                        </div>
                                    <?php }?>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </article>
    <script type="text/javascript">
        <?php $this->beginBlock("JS") ?>
        jQuery(document).ready(function() {
            $('.tab-box2').tab();
            $('.tab-boxList').tab('btn-red');
        });
        <?php $this->endBlock() ?>
    </script>
<?php
$this->registerJs($this->blocks['JS'], \yii\web\View::POS_READY);
?>