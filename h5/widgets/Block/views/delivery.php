<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/14
 * Time: 11:43
 */
use \common\component\Helper\Datetime;

?>
<?php if (!$type) { ?>
    <div class="delivery-container" data-date=""
         data-time="">
        <a href="javascript:;" id="delivery_time_cur">
            <div class="line-a flex-col w flex-middle delivery   p10 mb5 mt5">
                <div class="flex-item-3">
                    配送时间：
                </div>
                <div class="flex-item-7 delivery-default tr">
                    家润配送<br>
                    <p class="delivery_time_des">时间不限</p>
                </div>
                <div class="flex-item-2 tr green">
                    默认<i class="iconfont" style="color: #47b34f;"></i>
                </div>
            </div>
        </a>
    </div>
<?php } else { ?>
    <div class="delivery-container" data-date="<?= date('Y-m-d', strtotime($data['method_date'])) ?>"
         data-time="<?= $data['method_time'] ?>">
        <a href="javascript:;" class="open-popup" data-target="#delivery_form" id="delivery_time_cur">
            <div class="line-a flex-col w flex-middle delivery   p10 mb5 mt5">
                <div class="flex-item-3">
                    配送时间：
                </div>
                <div class="flex-item-7 delivery-default tr">
                    家润配送<br>
                    <p class="delivery_time_des"><?= date('m-d', strtotime($data['method_date'])) ?>
                        &nbsp;&nbsp;<?= Datetime::getWeekDay(strtotime($data['method_date'])) ?>
                        &nbsp;&nbsp;<?= $data['method_time'] ?></p>
                </div>
                <div class="flex-item-2 tr green">
                    修改<i class="iconfont" style="color: #47b34f;"></i>
                </div>
            </div>
        </a>
        <!--基础形式及全屏-->
        <div id="delivery_form" class="weui-popup-container">
            <div class="weui-popup-overlay"></div>
            <div class="weui-popup-modal">
                <div class="w bdb tc lh44 bg-wh bdb">
                    <a class="pa-tl close-popup" href="javascript:;">
                        <i class="aui-icon aui-icon-left green f28"></i>
                    </a>
                    <span class="f16">选择配送时间</span>
                </div>
                <div class="delivery-form">
                    <div class="w  delivery-content">
                        <div class="delivery-time-content">
                            <h2 class="p5 bdb">配送时间</h2>
                            <div class="row">
								<?php foreach (array_slice($data['method_times'], 0, 6) as $time) { ?>
                                    <div class="col-3">
                                        <div class="bd m5 p5 whitebg cp delivery-time <?= ($data['method_date'] == $time['date'] && $data['method_time'] == $time['time']) ? "cur" : "" ?>"
                                             data-date="<?= $time['date'] ?>" data-time="<?= $time['time'] ?>"
                                             data-week="<?= Datetime::getWeekDay(strtotime($time['date'])) ?>">
                                            <p class="pb5 bdb <?= $time['css'] ?> title"
                                               style="letter-spacing: 4px;"><?= $time['label'] ?></p>
                                            <p class="pt5 pb5 f10 lh150">
												<?= date('m-d', strtotime($time['date'])) ?>
                                                &nbsp;&nbsp;<?= Datetime::getWeekDay(strtotime($time['date'])) ?>
                                                <br>
												<?= $time['time'] ?>
                                            </p>
                                        </div>
                                    </div>
								<?php } ?>
                            </div>
                        </div>

                    </div>
                    <div class="fx-bottom p5 tc bg-wh bdt" style="z-index: 9999;">
                        <a class="btn w lbtn greenbtn  confirm-delivery close-popup" href="javascript:;">关闭</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php $this->beginBlock('JS_END') ?>
    $("body").on("click",".delivery-time",function(){
    $(this).parents(".delivery-time-content").find('.delivery-time').removeClass('cur');
    $(this).addClass('cur');
    $(this).parents(".delivery-container").find(".delivery_time_des").html($(this).attr('data-date')+'&nbsp;&nbsp;'+$(this).attr('data-week')+'&nbsp;&nbsp;'+$(this).attr('data-time'));
    $(this).parents(".delivery-container").attr('data-date',$(this).attr('data-date'));
    $(this).parents(".delivery-container").attr('data-time',$(this).attr('data-time'));
    $.closePopup();
    });
	<?php $this->endBlock() ?>
	<?php
	$this->registerJs($this->blocks['JS_END'], \yii\web\View::POS_READY);
	?>
<?php } ?>