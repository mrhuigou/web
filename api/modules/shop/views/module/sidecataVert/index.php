<?php
use yii\helpers\Url;
?>
<div class="J_TModule" data-title="宝贝分类（竖向）">
<div class="skin-box tb-module tshop-pbsm tshop-pbsm-shop-item-cates">
    <s class="skin-box-tp"><b></b></s>

    <div class="skin-box-hd">
        <i></i>

        <h3>
			<span>
				宝贝分类
		    </span>
        </h3>

        <div class="skin-box-act disappear">
            <a href="#">更多</a>
        </div>
    </div>
    <div class="skin-box-bd">
    
        <ul class="J_TCatsTree cats-tree J_TWidget">

            <li class="cat fst-cat float">
                <h4 class="cat-hd fst-cat-hd">
                    <i class="cat-icon fst-cat-icon acrd-trigger active-trigger " ></i>
                    <a href="" class="cat-name fst-cat-name" title="查看所有宝贝">查看所有宝贝</a>
                </h4>
                <ul class="fst-cat-bd " >
                        <a href="" class="cat-name" title="按默认" rel="nofollow">按综合</a>
                		<a href="" class="cat-name" title="按销量" rel="nofollow">按销量</a>
	                    <a href="" class="cat-name" title="按新品" rel="nofollow">按新品</a>
	                    <a href="" class="cat-name" title="按价格" rel="nofollow">按价格</a>
               </ul>
            </li>
            <?php if (isset($data['display']['shop_data']) && $data['display']['shop_data'] ) {
                foreach($data['display']['shop_data'] as $data ){
            ?>
            <li class="cat fst-cat <?php echo isset($data['children']) && $data['children'] ?'' :'no-sub-cat';?> ">
                <h4 class="cat-hd fst-cat-hd">
                    <i class="cat-icon fst-cat-icon acrd-trigger active-trigger"></i>
                    <a class="cat-name fst-cat-name" href="<?=$data['url']?>"> <?php echo $data['name'];?></a>
                </h4>
                <?php if(isset($data['children']) && $data['children']){ ?>
                <ul class="fst-cat-bd" >
                    <?php  foreach($data['children'] as $value ){ ?>
                    <li class="cat snd-cat <?php echo isset($value['children']) && $value['children'] ? '' :'no-sub-cat';?>   ">
                        <h4 class="cat-hd snd-cat-hd " >
                            <i class="cat-icon fst-cat-icon acrd-trigger active-trigger"></i>
                            <a class="cat-name snd-cat-name" href="<?=$value['url']?>"><?php echo $value['name'];?></a>
                        </h4>
                        <?php if(isset($value['children']) && $value['children']){ ?>
                        <ul class="snd-cat-bd" >
                            <?php  foreach($value['children'] as $val ){ ?>
                            <li class="cat  no-sub-cat ">
                                <h4 class="cat-hd trd-cat-hd " >
                                    <i class="cat-icon  "></i>
                                    <a class="cat-name " href="<?=$val['url']?>"><?php echo $val['name'];?></a>
                                </h4>
                                </li>
                            <?php } ?>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php } ?>
                </ul>
                <?php } ?>
            </li>
            <?php } ?>
            <?php } ?>
 </ul>
    </div>
    <s class="skin-box-bt"><b></b></s>
    </div>
	</div>