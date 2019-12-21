<div class="floor-container floor1 clearfix">
    <div class="floor-title clearfix">
        <a name="floor1" class="fl"><img src="/assets/images/2015/title-life.png" alt="慧生活"></a>
        <div class="floor-nav clearfix">
            <?php if(isset($data['text_top_position_data']) && !empty($data['text_top_position_data'])){?>
                <?php foreach($data['text_top_position_data'] as $key => $text){?>
                    <?php if($key < 9){?>
                        <a href="<?php echo $text['link']?>" target="_blank"><?php echo $text['title']?></a>
                    <?php }?>

                <?php }?>
            <?php }?>
        </div>
    </div>



    <!--新列表-->
    <div class="w175 fl">
        <div class="category-menu">
            <?php if(isset($data['category_array']) && !empty($data['category_array'])){?>
                <div class="category-nav">
                    <ul class="menu-nav-container">
                        <?php foreach($data['category_array'] as $key => $second){?>
                            <li>
                                <i class="fr iconfont"></i>
                                <?php $count1 = count($second);?>
                                <p>
                                    <?php foreach($second as $k => $second_info){?>

                                        <?php if(is_int($k)){?>
                                            <?php if( $k < ($count1-1) ){?>
                                                <a target="_blank" href="<?php echo $second_info['href']?>"><?php echo $second_info['name']?></a>/
                                            <?php }else{?>
                                                <a target="_blank" href="<?php echo $second_info['href']?>"><?php echo $second_info['name']?></a>
                                            <?php }?>
                                        <?php }?>
                                    <?php }?>
                                </p>
                            </li>
                        <?php }?>
                    </ul>
                </div>
            <?php }?>
            <div class="category-content">
                <?php if(isset($data['category_array']) && !empty($data['category_array'])){?>
                    <?php foreach($data['category_array'] as $key => $second){?>
                        <div class="menu-content-panel">
                            <?php foreach($second as $k => $second_info){?>
                                <h2><a href="<?php echo $second_info['href']?>" target="_blank"><?php echo $second_info['name']?></a></h2>
                                <?php if(isset($second_info['child_category_arrays']) && !empty($second_info['child_category_arrays'])){?>
                                    <p>
                                        <?php foreach($second_info['child_category_arrays'] as $third){?>

                                            <a target="_blank" href="<?php echo yii\helpers\Url::to(['/category/index','cat_id' => $third->category_display_id])?>"><?php echo $third->description->name;?></a>
                                        <?php }?>

                                    </p>
                                <?php }?>
                            <?php }?>
                        </div>
                    <?php }?>
                <?php }?>
            </div>
        </div>
    </div>




    <div class="clearfix">
        <div class="slideBox fl ml10 mr10">
            <?php if(isset($data['silde_position_data']) && !empty($data['silde_position_data'])){?>
                <div class="hd">
                    <ul>
                        <?php $count = count($data['silde_position_data']);?>
                        <?php for($i = 0; $i< $count ;$i++ ){?>
                            <li><?php echo $i+1; ?></li
                        <?php }?>
                    </ul>
                </div>
                <div class="bd">
                    <ul>
                        <?php foreach($data['silde_position_data'] as $banner){?>
                            <li><a target="_blank" href="<?php echo $banner['link'];?>" target="_blank"><img class="db lazy"  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="360" height="310" /></a></li>
                        <?php }?>
                    </ul>
                </div>
            <?php }?>
        </div>

        <div class="w545 fl clearfix img175">
            <?php if(isset($data['focus_position_data']) && !empty($data['focus_position_data'])){?>
                <?php foreach($data['focus_position_data'] as $key => $banner){?>
                    <a target="_blank" href="<?php echo $banner['link'];?>"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="db lazy" /></a>
                <?php }?>
            <?php }?>

        </div>
    </div>

    <div class="clearfix">
        <div class="w545 fl whitebg">
            <div class="clearfix">
                <?php if(isset($data['show_position_fix_left_top_data'])){?>
                    <?php foreach($data['show_position_fix_left_top_data'] as $key => $banner){?>
                        <?php if($key == 0){?>
                            <a target="_blank" href="<?php echo $banner['link'];?>" class="fl"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="db lazy" /></a>
                        <?php }?>
                    <?php }?>

                <?php } ?>
                <div class="picScroll-left two w360 fr">
                    <div class="bd">
                        <?php if(isset($data['show_position_slide_right_top_2_data'])){?>
                            <ul class="picList">
                                <?php foreach($data['show_position_slide_right_top_2_data'] as $key => $banner){?>
                                    <li>
                                        <div class="pic"><a href="<?php echo $banner['link'];?>" target="_blank"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="lazy" /></a></div>
                                    </li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="picScroll-left three mt10">
                <div class="bd">
                    <?php if(isset($data['show_position_slide_right_top_data'])){?>
                        <ul class="picList">
                            <?php foreach($data['show_position_slide_right_top_data'] as $key => $banner){?>
                                <li>
                                    <div class="pic"><a href="<?php echo $banner['link'];?>" target="_blank"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="lazy" /></a></div>
                                </li>
                            <?php }?>
                        </ul>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="w545 fr whitebg">
            <div class="clearfix">
                <?php if(isset($data['show_position_fix_left_top_new_data'])){?>
                    <?php foreach($data['show_position_fix_left_top_new_data'] as $key => $banner){?>
                        <?php if($key == 0){?>
                            <a target="_blank" href="<?php echo $banner['link'];?>" class="fl"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="db lazy" /></a>
                        <?php }?>
                    <?php }?>

                <?php } ?>
                <div class="picScroll-left two w360 fr">
                    <div class="bd">
                        <?php if(isset($data['show_position_slide_right_top_new_2_data'])){?>
                            <ul class="picList">
                                <?php foreach($data['show_position_slide_right_top_new_2_data'] as $key => $banner){?>
                                    <li>
                                        <div class="pic"><a href="<?php echo $banner['link'];?>" target="_blank"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="lazy" /></a></div>
                                    </li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="picScroll-left three mt10">
                <div class="bd">
                    <?php if(isset($data['show_position_slide_right_top_new_data'])){?>
                        <ul class="picList">
                            <?php foreach($data['show_position_slide_right_top_new_data'] as $key => $banner){?>
                                <li>
                                    <div class="pic"><a href="<?php echo $banner['link'];?>" target="_blank"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="lazy" /></a></div>
                                </li>
                            <?php }?>
                        </ul>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix mt10">
        <div class="w545 fl whitebg">
            <div class="clearfix">
                <?php if(isset($data['show_position_fix_left_bottom_data'])){?>
                    <?php foreach($data['show_position_fix_left_bottom_data'] as $key => $banner){?>
                        <?php if($key == 0){?>
                            <a target="_blank" href="<?php echo $banner['link'];?>" class="fl"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="db lazy" /></a>
                        <?php }?>

                    <?php } ?>
                <?php } ?>
                <div class="picScroll-left two w360 fr">
                    <div class="bd">
                        <?php if(isset($data['show_position_slide_right_bottom_2_data'])){?>
                            <ul class="picList">
                                <?php foreach($data['show_position_slide_right_bottom_2_data'] as $key => $banner){?>
                                    <li>
                                        <div class="pic"><a href="<?php echo $banner['link'];?>" target="_blank"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="lazy" /></a></div>
                                    </li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="picScroll-left three mt10">
                <div class="bd">
                    <?php if(isset($data['show_position_slide_right_bottom_data'])){?>
                        <ul class="picList">
                            <?php foreach($data['show_position_slide_right_bottom_data'] as $key => $banner){?>
                                <li>
                                    <div class="pic"><a href="<?php echo $banner['link'];?>" target="_blank"><img  src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="175" height="150" class="lazy" /></a></div>
                                </li>
                            <?php }?>
                        </ul>
                    <?php }?>
                </div>
            </div>
        </div>
        <div class="w545 fr whitebg">
            <div class="w370 fl bdr" style="height:310px;">
                <?php if(isset($data['show_position_f1_bottom_one_data'])){?>
                    <?php foreach($data['show_position_f1_bottom_one_data'] as $key => $banner){?>
                        <a href="<?php echo $banner['link'];?>" target="_blank"><img class="lazy db mb10" src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>" width="360" height="160"></a>
                    <?php }?>
                <?php }?>
                <div class="clearfix" style="height:140px;">
                    <div class="w175 fl pl10">
                        <a href="http://www.mrhuigou.com/exp-4103.html" target="_blank"><img src="http://img1.mrhuigou.com/group1/M00/00/F3/wKgB7lPkLlGAMpaKAABToRjk8W4917.jpg_175x110.jpg" alt="tu" width="175" height="110" /></a>
                        <a href="http://www.mrhuigou.com/exp-4103.html" class="gray6 pb5" target="_blank">“食物酸碱平衡论”有科学...</a>
                    </div>
                    <div class="w175 fl cleafix index-lift-list pt15">
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=31" target="_blank">食在源头</a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=33" target="_blank">食脍经</a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=43" target="_blank">八杯水 </a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=35" target="_blank">烘焙物语</a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=37" target="_blank">红醇</a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=41" target="_blank">茶与咖 </a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=47" target="_blank">时尚前沿</a>
                        <a href="http://www.mrhuigou.com/club/experiencelist?sub_id=65" target="_blank">宠物园</a>
                        <a href="http://www.mrhuigou.com/club/home" target="_blank">更多... </a>
                    </div>
                </div>
            </div>
            <div class="w175 fr">
                <div class="note-list">
                    <h2 class="p5 pl10 fb">每日惠购新闻</h2>
                    <?php if(isset($data['public']) && !empty($data['public'])){?>
                        <ul>
                            <?php foreach($data['public'] as $v){ ?>
                                <li><i class="iconfont"></i><a href=" <?php echo yii\helpers\Url::to(['information/information','information_id'=>$v->information_id])?>" title="<?php echo $v->description->title;?>" target="_blank"><?php echo $v->description->title;?></a></li>
                            <?php }?>
                        </ul>
                    <?php }?>

                </div>

                <ul>
                    <li class="bdt pt5 pb5">
                        <img src="/assets/images/2015/icon1.jpg" alt="手机充值" class="db recharge-tri cp">
                    </li>
                    <li class="bdt pt5 pb5 none">
                        <a href="#"><img src="/catalog/view/theme/web3.0/images/2015/icon2.jpg" alt="客户留言区" class="db"></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>




    <div class="recharge-Pop w350 whitebg pr none">
        <div class="close_btn2"></div>
        <h3 class="f14 p5 pl15 fb graybg bdb">话费充值</h3>
        <div class="w220 bc pb30">
            <dl class="mb10 pl5 clearfix pt20">
                <dt class="w50 fl tr">手机号：</dt>
                <dd class="w140 fr"><input id="telephone" type="text" class="input w110"></dd>
            </dl>
            <dl class="mb10 pl5 clearfix">
                <dt class="w50 fl tr">面值：</dt>
                <dd class="w140 fr">
                    <select name="phonemoney" id="phonemoney" style="width:122px;">
                        <option value="300">300</option>
                        <option selected="selected" value="100">100</option>
                        <option value="50">50</option>
                        <option value="30">30</option>
                    </select>
                </dd>
            </dl>
            <dl class="mb10 pl5 clearfix">
                <dt class="w50 fl tr">价  格：</dt>
                <dd id="phoneOther" class="w140 fr org fb">
                    ¥98-99.90
                </dd>
                <dd id="err_msg"></dd>
            </dl>
            <dl class="mb10 clearfix tc">
                <a href="javascript:void(0)" onclick="submitCharge();" class="btn btn_small redBtn w70 mt5">立即充值</a>
            </dl>
        </div>
    </div>


</div>


<!--品牌-->
<div class="mt10 clearfix">
    <?php if(isset($data['brand_left_data'])){?>
        <?php foreach($data['brand_left_data'] as $key => $banner){?>
            <a href="<?php echo $banner['link'];?>" target="_blank"><img src="/assets/images/placeholder.png" alt="<?php echo $banner['title'];?>"  data-url="<?php echo $banner['image'];?>" width="220" height="304" class="db fl lazy"></a>
        <?php }}?>
    <?php if(isset($data['brand_right_data'])){?>
        <div class="fr w880 brand-list clearfix">
            <?php foreach($data['brand_right_data'] as $key => $banner){?>
                <a href="<?php echo $banner['link'];?>" target="_blank">
                    <img src="/assets/images/placeholder.png" data-url="<?php echo $banner['image'];?>" alt="<?php echo $banner['title'];?>"  width="175" height="75"  class="lazy">
                </a>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<?php $this->beginBlock('JS_END') ?>
    $(document).ready(function(){
        $('#phonemoney').change(function(){
            telequey();
        });
        $('#telephone').keyup(function(){
            telequey();
        });
        $('#telephone').blur(function(){
            telequey();
        });
    });
    function telequey(){

        if($('#telephone').val().length==11) {
            $.ajax({
                url: '<?php echo yii\helpers\Url::to(["recharge/getinfo"])?>',
                type: 'post',
                data: 'telephone=' + $('#telephone').val() + '&value=' + $('#phonemoney').val(),
                dataType: 'json',
                success: function (json) {
                    if (json['result_code'] == 1) {
                        $('#phoneOther').html('￥'+json['result_data']['price']);
                        $('#phonearea dd span').html(json['result_data']['area']);
                        $('#phonearea').show();
                        $('#err_msg').html('').hide();
                    } else {
                        $('#phonearea').hide();
                        $('#phoneOther').html('');
                        $('#err_msg').html(json['err_msg']).show();
                    }
                }
            });
        }else{
            $('#phonearea').hide();
            $('#phoneOther').html('￥'+($('#phonemoney').val()*98.6/100)+'-'+$('#phonemoney').val());
            $('#err_msg').html('').hide();
        }
    }

    function submitCharge(){
        telequey();
        if($('#err_msg').text()=='' && $('#telephone').val()!==11 && $('#phonemoney').val()){
            $.ajax({
                url: '<?php echo yii\helpers\Url::to(["recharge/commit-order"])?>',
                type: 'post',
                data: 'telephone='+$('#telephone').val()+'&value='+$('#phonemoney').val(),
                dataType: 'json',
                success: function(json) {
                    if (json['result_code'] == 1) {
                        //location.href=json['location'];
                    }else{
                        alert(json['err_msg']);
                    }
                }
            });
        }else{
            alert($('#err_msg').text());
        }

    }

    <?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_END);
?>