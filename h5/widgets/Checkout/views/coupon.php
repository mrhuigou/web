<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/4/27
 * Time: 16:54
 */
?>
<?php if ($model) { ?>
    <style>
        .weui-popup-container{
            z-index: 9999;
        }
    </style>
<a href="javascript:;" class="open-popup" data-target="#store_coupon_form_<?= $store_id ?>" id="store_coupon_title_<?=$store_id ?>"  data-source="<?=$store_id ?>">
	<div class=" red w p10  tc  bg-red white store_coupon clearfix fb" id="store_coupon_<?= $store_id ?>"  data-source="<?= $store_id ?>">
		优惠券： <span	class="store_coupon_content yellow ">您有<?=count($model['data'])+count($model['order'])?>张可用</span>
		<span class=" fr fn">点击查看</span>
	</div>
</a>
    <!--基础形式及全屏-->
<div id="store_coupon_form_<?= $store_id?>"  class="weui-popup-container">
    <div class="weui-popup-overlay"></div>
    <div class="weui-popup-modal">
		<div class="coupon-contain_form bg-wh" data-source="<?= $store_id ?>">
			<div class="w bdb tc p10 lh200 f16 clearfix">
				<a class="fl close-popup" href="javascript:;">
					<i class="aui-icon aui-icon-left green f28"></i>
				</a>
				我的优惠券
			</div>
			<div class="p10  graybg ">
				<div class="w " style="top: 0px;bottom: 0px;overflow-y: auto;">
					<div class="mb10">
						<?php if ($model['data']) { ?>
							<div class="tit1 bluetit1">
								<h2>商品、分类、品牌、买赠优惠券[多选]<span class="red fb">[选择使用]</span></h2>
							</div>
							<?php foreach ($model['data'] as $value) { ?>
								<label class="flex-col  flex-center tc  mb10 bg-wh db coupon-item-label ">
									<label
										class="label-checkbox item-content flex-item-2 flex-row flex-middle flex-center" style="line-height: 86px;">
										<input type="checkbox" id="coupon-item-<?= $value->customer_coupon_id ?>" class="coupon-item check-box"
										       name="CheckoutForm[coupon][<?= $store_id ?>][]"
										       value="<?= $value->customer_coupon_id ?>"  />

										<div class="item-media"><i class="icon icon-form-checkbox"></i></div>
									</label>
									<div class="flex-item-4 flex-row  flex-middle">
								<span class="red f18" style="line-height: 86px;">
			<?php if($value->coupon->model!=='BUY_GIFTS'){?>
									<?= $value->coupon->type == 'F' ? "￥" . $value->coupon->discount : $value->coupon->getRealDiscount() . "<i class='f12'>折</i>" ?>
				<?php }else{?>
				赠品券
				<?php }?>
								</span>
									</div>
									<div class="flex-item-6 flex-row  flex-middle f12 pt10">
										<p class="red"><?= $value->coupon->name ?></p>
										<p><?= $value->coupon->comment?$value->coupon->comment:$value->coupon->description ?></p>
										<p class="gray6 f12"><?= date('m-d', strtotime($value->start_time)) ?>
											~<?= date('m-d', strtotime($value->end_time)) ?></p>
									</div>
								</label>
							<?php } ?>
						<?php } ?>
						<?php if ($model['order']) { ?>
							<div class="tit1 ">
								<h2>以下优惠券每张订单仅限使用一张<span class="red fb">[选择使用]</span></h2>
							</div>
							<?php foreach ($model['order'] as $value) { ?>
								<label class="flex-col  flex-center  mb10 db  bg-wh coupon-item-label ">
									<label class="label-checkbox item-content  tc flex-item-2 flex-row flex-middle flex-center" style="line-height: 86px;">
										<input type="checkbox" id="coupon-item-<?= $value->customer_coupon_id ?>" class="coupon-item radio-box"
										       name="CheckoutForm[coupon][<?= $store_id ?>][]"
										       value="<?= $value->customer_coupon_id ?>" />
										<div class="item-media"><i class="icon icon-form-checkbox"></i></div>
									</label>
									<div class="flex-item-4 flex-row tc flex-middle">
										<span class="red f18" style="line-height: 86px;">
											<?= $value->coupon->type == 'F' ? "￥" . $value->coupon->discount : $value->coupon->getRealDiscount() . "<i class='f12'>折</i>" ?>
										</span>
									</div>
									<div class="flex-item-6 flex-row  flex-middle f12 pt10" >
										<p class="red"><?= $value->coupon->name ?></p>

										<p><?= $value->coupon->description ?></p>

										<p class="gray6 f12"><?= date('m-d', strtotime($value->start_time)) ?>
											~<?= date('m-d', strtotime($value->end_time)) ?></p>
									</div>
								</label>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="fx-bottom p5 tc bg-wh bdt" style="z-index:9999">
				<a class="btn w lbtn greenbtn  confirm-coupon close-popup" href="javascript:;">确认使用</a>
			</div>
		</div>
    </div>
</div>
    <script>
	<?php $this->beginBlock('JS_END') ?>
    $("body").on("change",".radio-box",function(){
        if($(this).is(":checked")){
            $(this).parents(".coupon-contain_form").find(".radio-box").removeAttr("checked");
            $(this).attr("checked","checked");
        }else{
            $(this).removeAttr("checked");
        }
    });
    $("body").on("change",".check-box",function(){
        if($(this).is(":checked")){
            $(this).attr("checked","checked");
        }else{
            $(this).removeAttr("checked");
        }
    });
    $("body").on("click",".confirm-coupon",function(){
        var store_id=$(this).parents(".coupon-contain_form").attr("data-source");
        var customer_coupon=$(this).parents(".coupon-contain_form").find(".coupon-item:checked");
        var customer_coupon_ids=[];
        if(customer_coupon.length>0){
            customer_coupon.each(function(){
                customer_coupon_ids.push($(this).val());
            });
        }
        $.showLoading("正在加载");
        $.post('<?= \yii\helpers\Url::to('/checkout/order-ajax') ?>',{'store_id':store_id,'customer_coupon_id':customer_coupon_ids},function(data){
            $.hideLoading();
            if(data && data.status){

                $("#store_contain_"+store_id).find(".store_totals").html(data.data);
                $("#store_contain_"+store_id).find(".store-promotion").html(data.store_promotion);
                $(".coupon-item").attr('checked',false);
                if(data.coupon_array){
                    for (var item in data.coupon_array){
                        $("#store_contain_"+store_id).find("#coupon-item-"+data.coupon_array[item]).attr('checked', true);
                    }
                    $("#store_contain_"+store_id).find(".store_coupon_content").html('已使用'+data.coupon_array.length+"张");
                }else{
                    $("#store_contain_"+store_id).find(".store_coupon_content").html('已使用0张');
                }

                var pay_total=0;
                $(".total").each(function(){
                    pay_total=FloatAdd(pay_total,$(this).html());
                });
                $("#pay_total").html(pay_total);
            }else{
                $.alert(data.data);
            }
        },'json');
    });
    $(".coupon-contain_form").each(function(){
        var store_id=$(this).attr("data-source");
        $(this).find(".check-box").attr("checked","checked");
        $(this).find(".radio-box").first().attr("checked","checked");
        var customer_coupon=$(this).find(".coupon-item:checked");
        var customer_coupon_ids=[];
        if(customer_coupon.length>0){
            customer_coupon.each(function(){
                customer_coupon_ids.push($(this).val());
            });
        }
        $.showLoading("正在加载");
        $.post('<?= \yii\helpers\Url::to('/checkout/order-ajax') ?>',{'store_id':store_id,'customer_coupon_id':customer_coupon_ids},function(data){
            $.hideLoading();
            if(data && data.status){

                $("#store_contain_"+store_id).find(".store_coupon_content").html('已使用'+data.coupon_array.length+"张");
                $("#store_contain_"+store_id).find(".store_totals").html(data.data);
                $("#store_contain_"+store_id).find(".store-promotion").html(data.store_promotion);
                $(".coupon-item").attr('checked',false);
                for (var item in data.coupon_array){
                    $("#store_contain_"+store_id).find("#coupon-item-"+data.coupon_array[item]).attr('checked', true);
                }
                var pay_total=0;
                $(".total").each(function(){
                    pay_total=FloatAdd(pay_total,$(this).html());
                });
                $("#pay_total").html(pay_total);
            }else{
                $.alert(data.data);
            }
        },'json');
    });
	<?php $this->endBlock() ?>
    </script>
	<?php
	\yii\web\YiiAsset::register($this);
	$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
	?>
<?php } ?>