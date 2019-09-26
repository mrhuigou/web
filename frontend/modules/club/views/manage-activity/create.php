<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/15
 * Time: 17:31
 */
?>
<div class="w1100 bc">
    <div class="layout grid-s5m0">
        <div class="col-m">
            <div class="main-w">
                <div class="p20 pr">
                    <!--上传活动海报-->
                    <div class="bd-d pa r0 t0 w300" style="height: 200px;">
                        <div class="tc mt50 pt30">
                            <input type="file" class="w150">
                        </div>
                    </div>
                    <ul class="tit-con">
                        <li>
                            <div class="tit"><span class="red">*</span>活动主题：</div>
                            <div class="con">
                                <input type="text" class="input minput w400" placeholder="10字以内" maxlength="10">
                            </div>
                        </li>
                        <li>
                            <div class="tit"><span class="red">*</span>活动类型：</div>
                            <div class="con">
                                <select class="bd p8">
                                    <option>请选择活动类型</option>
                                    <option>请选择活动类型</option>
                                    <option>请选择活动类型</option>
                                    <option>请选择活动类型</option>
                                    <option>请选择活动类型</option>
                                    <option>请选择活动类型</option>
                                    <option>请选择活动类型</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="tit"><span class="red">*</span>报名截止时间：</div>
                            <div class="con">
                                <div class="bd minput w200 fl">
                                    <i class="iconfont gray9 f20">&#xe626;</i>
                                    <input type="text" class="datepicker-stop" placeholder="报名截止时间" readonly>
                                </div>
                                <select class="bd p8 mt1 ml10">
                                    <option>时分</option>
                                    <option>00:00</option>
                                    <option>00:30</option>
                                    <option selected>09:00</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="tit"><span class="red">*</span>活动开始时间：</div>
                            <div class="con">
                                <div class="bd minput w200 fl">
                                    <i class="iconfont gray9 f20">&#xe626;</i>
                                    <input type="text" class="datepicker-start" placeholder="活动开始时间" readonly>
                                </div>
                                <select class="bd p8 mt1 ml10">
                                    <option>时分</option>
                                    <option>00:00</option>
                                    <option>00:30</option>
                                    <option selected>09:00</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="tit"><span class="red">*</span>活动结束时间：</div>
                            <div class="con">
                                <div class="bd minput w200 fl">
                                    <i class="iconfont gray9 f20">&#xe626;</i>
                                    <input type="text" class="datepicker-end" placeholder="活动结束时间" readonly>
                                </div>
                                <select class="bd p8 mt1 ml10">
                                    <option>时分</option>
                                    <option>00:00</option>
                                    <option>00:30</option>
                                    <option selected>09:00</option>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="tit">活动地点：</div>
                            <div class="con">
                                <input type="text" class="input minput w400" placeholder="请输入详细地址">
                            </div>
                        </li>
                        <li>
                            <div class="tit">活动人数：</div>
                            <div class="con">
                                <label><input type="radio" name="p" class="vm"> <span class="vm">不限</span></label>
                                <label class="ml30 mr5"><input type="radio" name="p" class="vm"> <span class="vm">限制人数</span></label>
                                <input type="text" class="vm input minput w100" placeholder="请输入人数"> 人
                            </div>
                        </li>
                        <li>
                            <div class="tit">活动费用：</div>
                            <div class="con pt5 mt2">
                                <label><input type="radio" name="charge" class="vm noCharge"> <span class="vm">免费</span></label>
                                <label class="ml30 mr5"><input type="radio" name="charge" class="vm isCharge"> <span class="vm">收费</span></label>
                                <div class="w400 graybg p10 mt10 chargeBox none">
                                    <div class="chargeList">
                                        <p class="mb10">
                                            <i class="iconfont fr mt10 cp">&#xe621;</i>
                                            <input type="text" class="vm input minput w350" placeholder="金额">
                                        </p>
                                    </div>
                                    <p class="tc">
                                        <a href="javascript:void(0)" class="btn btn_small mt10 mb10 greenBtn chargeAdd">添加新项</a>
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="tit">报名信息：</div>
                            <div class="con pt5 mt2">
                                <label><input type="checkbox" name="c" class="vm"> <span class="vm">姓名</span></label>
                                <label class="ml30 mr5"><input type="checkbox" name="c" class="vm"> <span class="vm">手机</span></label>

                                <!--自定义添加的节点-->
                                <div class="mt10 customOptBox">
                                    <div class="p10 graybg mb10 clearfix customOptlist">
                                        <i class="iconfont f20 fl mr10">&#xe622;</i>
                                        <div class="fl">
                                            <input type="text" class="input input_small w400" placeholder="这里是一个单行文本">
                                        </div>
                                        <i class="fr iconfont f20 cp gray6 deleOpt">&#xe625;</i>
                                    </div>

                                    <div class="p10 graybg mb10 clearfix customOptlist">
                                        <i class="iconfont f20 fl mr10">&#xe623;</i>
                                        <div class="fl">
                                            <input type="text" class="input input_small w400 mb10" placeholder="这里一个单选问题">
                                            <div>
                                                <input type="text" class="input input_small w400 mb5" placeholder="选项一"> <br>
                                                <input type="text" class="input input_small w400 mb5" placeholder="选项二"> <br>
                                                <input type="text" class="input input_small w400 mb5" placeholder="选项三"> <br>
                                            </div>
                                        </div>
                                        <i class="fr iconfont f20 cp gray6 deleOpt">&#xe625;</i>
                                    </div>

                                    <div class="p10 graybg mb10 clearfix customOptlist">
                                        <i class="iconfont f20 fl mr10">&#xe624;</i>
                                        <div class="fl">
                                            <input type="text" class="input input_small w400 mb10" placeholder="这里一个多选问题">
                                            <div>
                                                <input type="text" class="input input_small w400 mb5" placeholder="选项一"> <br>
                                                <input type="text" class="input input_small w400 mb5" placeholder="选项二"> <br>
                                                <input type="text" class="input input_small w400 mb5" placeholder="选项三"> <br>
                                            </div>
                                        </div>
                                        <i class="fr iconfont f20 cp gray6 deleOpt">&#xe625;</i>
                                    </div>
                                </div>

                                <div class="w400 graybg2 p10 mt10">
                                    <p class="mb10">添加一个自定义填选项</p>
                                    <div class="clearfix f14 custom-opt">
                                        <a href="javascript:void(0)" class="addCText"><i class="iconfont f20 gray6 ">&#xe622;</i> 单行文本</a>
                                        <a href="javascript:void(0)" class="addCSingle"><i class="iconfont f20 gray6 ">&#xe623;</i> 单选问题</a>
                                        <a href="javascript:void(0)" class="addCMultiple"><i class="iconfont f20 gray6 ">&#xe624;</i> 多选问题</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="tit">活动详情：</div>
                            <div class="con">
                                <textarea class="w  mt10 resizeNo" rows="10" placeholder="活动详情..."></textarea>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="tc pb50">
                    <p class="mb20"><input type="checkbox" class="vm"> <span class="vm">同意《每日惠购全城互动协议》</span></p>
                    <a href="#" class="btn btn_middle orgBtn w60 mr5">发布活动</a> <a href="#" class="btn btn_middle grayBtn w60">预览</a>
                </div>
            </div>
        </div>

        <div class="col-s">
            <?=frontend\modules\club\widgets\Menu::widget()?>
        </div>
    </div>
</div>
<?php $this->beginBlock('JS_END') ?>
//日期选择控件
$( ".datepicker-stop,.datepicker-start,.datepicker-end").datepicker({
dateFormat : 'yy-mm-dd'
});
/*活动费用*/
$(".isCharge").click(function(){
if($(".isCharge").is(":checked")){
$(".chargeBox").show();
}
})
$(".noCharge").click(function(){
if($(".noCharge").prop("checked")){
$(".chargeBox").hide();
}
})

/*增加收费项*/
$(".chargeAdd").click(function(){
var cloneC='<p class="mb10"><i class="iconfont fr mt10 cp">&#xe621;</i><input type="text" class="vm input minput w350" placeholder="金额"></p>';
$(".chargeList").append(cloneC);
})
/*删除收费项*/
$(".chargeList").on("click","i",function(){
$(this).parent().remove();
})

/*增加自定义项*/
var customText='<div class="p10 graybg mb10 clearfix customOptlist"><i class="iconfont f20 fl mr10">&#xe622;</i><div class="fl"><input type="text" class="input input_small w400" placeholder="这里是一个单行文本"></div><i class="fr iconfont f20 cp gray6 deleOpt">&#xe625;</i></div>';
var customSingle='<div class="p10 graybg mb10 clearfix customOptlist"><i class="iconfont f20 fl mr10">&#xe623;</i><div class="fl"><input type="text" class="input input_small w400 mb10" placeholder="这里一个单选问题"><div><input type="text" class="input input_small w400 mb5" placeholder="选项一"> <br><input type="text" class="input input_small w400 mb5" placeholder="选项二"> <br><input type="text" class="input input_small w400 mb5" placeholder="选项三"> <br></div></div><i class="fr iconfont f20 cp gray6 deleOpt">&#xe625;</i></div>';
var customMultiple='<div class="p10 graybg mb10 clearfix customOptlist"><i class="iconfont f20 fl mr10">&#xe624;</i><div class="fl"><input type="text" class="input input_small w400 mb10" placeholder="这里一个多选问题"><div><input type="text" class="input input_small w400 mb5" placeholder="选项一"> <br><input type="text" class="input input_small w400 mb5" placeholder="选项二"> <br><input type="text" class="input input_small w400 mb5" placeholder="选项三"> <br></div></div><i class="fr iconfont f20 cp gray6 deleOpt">&#xe625;</i></div>';
//单行文本
$(".addCText").click(function(){
$(".customOptBox").append(customText);
});

//单选问题
$(".addCSingle").click(function(){
$(".customOptBox").append(customSingle);
});

//多选问题
$(".addCMultiple").click(function(){
$(".customOptBox").append(customMultiple);
});

/*删除自定义项*/
$(".customOptBox").on("click",".customOptlist .deleOpt",function(){
$(this).parent().remove();
})
<?php $this->endBlock() ?>
<?php
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_END'],\yii\web\View::POS_READY);
?>

