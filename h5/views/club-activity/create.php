<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use api\models\V1\ClubActivityCategory;
$this->title='创建活动';
?>
<div id="foo">
		<header class="bs-bottom whitebg lh44">
			<a href="javascript:history.back();" class="his-back">返回</a>
			<h2 class="tc f18">创建活动</h2>
			<div class="pa-rt">
				<button onclick="javascript:$('#activity-form').submit();" class="btn mr10 w50 lh240 bluebtn" data-role="none">发布</button>
			</div>
		</header>
		<section class="veiwport">
			<?php $form = ActiveForm::begin(['id' => 'activity-form','fieldConfig' => [
				'errorOptions'=>['class'=>'error db']
			],  ]); ?>
			<?=$form->field($model,'title',['template'=>'{input}',"inputOptions"=>['placeholder'=>"活动名称（最多 30 字）",'class'=>'input minput w mt10 mb10']])?>
			<?=$form->field($model,'image',['template'=>'{input}'])->hiddenInput()?>
			<div class="bd p5 whitebg pr mb10">
				<div class="act-avaimg cp" id="chooseImage">
					<!--未上传图片-->
					<p class="upload_pic">上传图片</p>
					<!--图片已上传状态-->
					<img src="/assets/images/eg/img.jpg" alt="上传图片" width="65" height="80" class="bd dn" id="activity_theme_pic">
				</div>
				<div class="pl50 ml30">
					<div class="lh44 w bdb clearfix">
						<span class="fl gray6">开始时间</span>
						<?=$form->field($model,'begin_datetime',['template'=>'{input}',"inputOptions"=>['class'=>'fl ml15 lh44 pw70 scroller-start']])?>
					</div>
					<div class="lh44 clearfix">
						<span class="fl gray6">结束时间</span>
						<!-- <input type="text" class="fl ml15 lh44" id="scroller-end"> -->
						<?=$form->field($model,'end_datetime',['template'=>'{input}',"inputOptions"=>['class'=>'fl ml15 lh44 pw70 scroller-end']])?>
					</div>
				</div>
			</div>
			<a class="db bdt whitebg lh44 pr clearfix" id="openmap">
				<h3 class="pl10 fl">活动地址</h3>
				<?= $form->field($model,'address',['template'=>'{input}',"inputOptions"=>['placeholder'=>"点此搜索活动地点",'class'=>'fl ml10 lh44 pw70']])?>
				<?=$form->field($model,'lat',['template'=>'{input}'])->hiddenInput()?>
				<?=$form->field($model,'lng',['template'=>'{input}'])->hiddenInput()?>
			</a>
			<a class="db bdt bdb whitebg lh44 pr clearfix">
				<h3 class="pl10 fl">截止时间</h3>
				<?=$form->field($model,'signup_end',['template'=>'{input}',"inputOptions"=>['class'=>'fl pl15 lh44 pw80 scroller-close']])?>
			</a>
			<a href="#" class="db bdb whitebg lh44 pr fee-tri">
				<h3 class="pl10">费用</h3>
				<div class="pa-rt pr10 gray9"><em>免费</em><i class="iconfont rightarr f14"></i></div>
				<?=$form->field($model,'fee',['template'=>'{input}'])->hiddenInput(['value'=>'0'])?>
			</a>
			<a href="#" class="db bdb whitebg lh44 pr reminder-tri">
				<h3 class="pl10">提醒</h3>
				<div class="pa-rt pr10 gray9"><em>请选择</em><i class="iconfont rightarr f14"></i></div>
				<?=$form->field($model,'alert_time',['template'=>'{input}'])->hiddenInput()?>
			</a>
			<a href="#" class="db bdb whitebg lh44 pr cata-tri">
				<h3 class="pl10">类别</h3>
				<div class="tit-right pr10 gray9"><em>请选择</em><i class="iconfont rightarr f14"></i></div>
				<?=$form->field($model,'activity_category_id',['template'=>'{input}'])->hiddenInput()?>
			</a>
			<?=$form->field($model,'description')->widget(h5\widgets\H5Editor\Widget::className(),['id'=>'activityform-description'])?>
			<div class="p10">
				<input type="checkbox" checked class="fl mt2 mr5">同意<a href="<?=\yii\helpers\Url::to(['/site/about'])?>" class="blue">《每日惠购服务协议》</a>
			</div>
			<?= $form->errorSummary($model,['header'=>'']); ?>
			<div class="p10">
				<?= Html::submitButton('提交',['class'=>'btn mbtn w white bc bluebg','data-role'=>'none','id'=>'submitbtn']);?>
			</div>
	    <?php ActiveForm::end(); ?>
		</section>
		</div>

		<div id="bar" class="dn">
			<header class="bs-bottom whitebg lh44 fx-top">
				<a href="javascript:void(0);" class="his-back ui-link" id="back">返回</a>
				<h2 class="tc f18">选择位置</h2>
				<div class="pa-rt">
					<button class="btn mr10 w50 lh240 bluebtn" data-role="none" onclick="saveaddress()">确定</button>
				</div>
			</header>
			<section>
				<div id="r-result" class="w p5" style="position: absolute;z-index: 9999;top:45px;">
				    <input type="text" id="suggestId" placeholder="搜索位置..." size="20" class='w db input sinput bs' />
				    <i class="iconfont pa-rt cp gray6 f14" style="top: 12px;right: 14px;">&#xe612;</i>
				</div>
				<div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
				<div id="l-map" style="width:100%;height:100%;position: absolute;"></div>
				<div id="l-suggest" style="width:100%;height:40%;position: absolute;top:60%;overflow: scroll; background-color: white;">
					<ul class="result-list">
						<li class="p10 tc">暂无相关搜索结果</li>
					</ul>
				</div>
			</section>
			</div>

	<!--提醒时间-->
	<div class="ml10 mr10 tc gray6 dn reminder">
	<div class="whitebg bdt br5">
		<p class="bdb p10 gray6">参加活动的人会在活动开始前收到提醒</p>
		<p class="bdb p10 blue f14" val="900">提前15分钟</p>
		<p class="bdb p10 blue f14" val="1800">提前30分钟</p>
		<p class="bdb p10 blue f14" val="7200">提前2小时</p>
		<p class="bdb p10 blue f14" val="86400">提前一天</p>
	</div>
		<span class="btn w mbtn graybtn mt5 mb5 br5">取消</span>
	</div>

	<!--费用-->
	<div class="ml10 mr10 tc gray6 dn fee">
	<div class="whitebg bdt br5">
		<p class="bdb p10 gray6">活动费用</p>
		<p class="bdb p10 blue f14" val="0">免费</p>
		<p class="bdb p10 blue f14" val="2">AA制</p>
	</div>
		<span class="btn w mbtn graybtn mt5 mb5 br5">取消</span>
	</div>

	<!--类别-->
	<div class="ml10 mr10 tc gray6 dn cata">
	   <div class="whitebg bdt br5">
		   <p class="bdb p10 gray6 ">请选择活动类别</p>
		<?php foreach (ClubActivityCategory::find()->all() as $key => $value) {?>
		<p class="bdb p10 f14 blue" val="<?=$value->activity_category_id?>"><?=$value->title?></p>
		<?php }?>
		</div>
			<span class="btn w mbtn graybtn mt5 mb5 br5">取消</span>
	</div>
<?=h5\widgets\Wx\Upload::widget()?>
<?php $this->beginBlock("JS_BaiDu")?>
$(function() {
		    var newjavascript = {
		        plugdatetime: function($dateTxt, type) {
		            var opt = {}
		            opt.time = {
		                preset: type
		            };
		            opt.date = {
		                preset: type
		            };
		            opt.datetime = {
		                preset: type,
		                minDate: new Date(2010, 1, 01, 00, 00),
		                maxDate: new Date(2020, 12, 31, 24, 59),
		                stepMinute: 1
		            };
		            $dateTxt.val('').scroller('destroy').scroller(
		                $.extend(opt[type], {
		                    theme: "android-ics light",
		                    mode: "scroller",
		                    display: "modal",
		                    lang: "zh",
		                    monthText: "月",
		                    dayText: "日",
		                    yearText: "年",
		                    hourText: "时",
		                    minuteText: "分",
		                    ampmText: "上午/下午",
		                    setText: '确定',
		                    cancelText: '取消',
		                    dateOrder: 'yymmdd',
		                    dateFormat: 'yy-mm-dd',
		                    timeWheels: 'HHiiss',
		                    timeFormat: 'HH:ii:ss'
		                })
		            );
		        }
		    }
		    newjavascript.plugdatetime($(".scroller-start"), "datetime")
		    newjavascript.plugdatetime($(".scroller-end"), "datetime")
		    newjavascript.plugdatetime($(".scroller-close"), "datetime")


		    //提醒时间
		    $(".reminder-tri").click(function(){
			    maskdiv($(".reminder"),"bottom");
		    })
		    $(".reminder p,.reminder .btn").click(function(){
		    	$(".maskdiv").fadeOut();
				$(".reminder").slideUp();
				if($(this).is("p")){
					$(".reminder-tri .pa-rt em").text($(this).text());
					$(".reminder-tri input").val($(this).attr("val"));
				}
		    })

		    //类别
		    $(".cata-tri").click(function(){
			    maskdiv($(".cata"),"bottom");
		    })
		    $(".cata p,.cata .btn").click(function(){
		    	$(".maskdiv").fadeOut();
				$(".cata").slideUp();
				if($(this).is("p")){
					$(".cata-tri .tit-right em").text($(this).text());
					$(".cata-tri input").val($(this).attr("val"));
				}
		    })

		    //费用
		    $(".fee-tri").click(function(){
			    maskdiv($(".fee"),"bottom");
		    })
		    $(".fee p,.fee .btn").click(function(){
		    	$(".maskdiv").fadeOut();
				$(".fee").slideUp();
				if($(this).is("p")){
					$(".fee-tri .pa-rt em").text($(this).text());
					$(".fee-tri input").val($(this).attr("val"));
				}
		    })

		});

	$(".dwb,#submitbtn").live("click",function(){
		var beginDate=$(".scroller-start").val();  
		var endDate=$(".scroller-end").val();  
		var closeDate=$(".scroller-close").val();  
		var d1 = new Date(beginDate.replace(/\-/g, "\/"));  
		var d2 = new Date(endDate.replace(/\-/g, "\/"));  
		var d3 = new Date(closeDate.replace(/\-/g, "\/"));  
		var d4 = new Date();  

		if(beginDate!=""&&endDate!=""&&d1 >=d2)  
		{  
			alert("开始时间不能大于结束时间！"); 
			return false; 
		}
		if(endDate!=""&&closeDate!=""&&d2 <=d3)  
		{  
			alert("截止时间不能大于结束时间！"); 
			return false;  
		}
		if(endDate!="" && d2 <=d4)  
		{  
			alert("结束时间不能小于当前时间！"); 
			return false;  
		}
		if(closeDate!="" && d3 <=d4)  
		{  
			alert("截止时间不能小于当前时间！"); 
			return false;  
		}
	});

	jQuery.fn.extend({
	  zh: function(o,tar) {
	  	o.fadeOut();
	  	tar.fadeIn();
	  }
	});
	$("#mt0").hide();
	
	$("#openmap").click(function(){
		$("").zh($("#foo,#footer"),$("#bar"));
	});
	$("#back").click(function(){
		$("").zh($("#bar"),$("#foo,#footer"));
	});

	
	// 百度地图API功能
		function G(id) {
			return document.getElementById(id);
		}

		var map = new BMap.Map("l-map");
		var point = new BMap.Point('120.369557','36.094406'); // 创建点坐标
		map.centerAndZoom(point,15);                   // 初始化地图,设置城市和地图级别。

		var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
			{"input" : "suggestId"
			,"location" : map
		});

		ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
		var str = "";
			var _value = e.fromitem.value;
			var value = "";
			if (e.fromitem.index > -1) {
				value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			}    
			str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
			
			value = "";
			if (e.toitem.index > -1) {
				_value = e.toitem.value;
				value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			}    
			str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
			G("searchResultPanel").innerHTML = str;
		});

		var myValue;
		var lat,lng,address;
		ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
		var _value = e.item.value;
			myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
			G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
			
			setPlace();
		});

		function setPlace(){
			map.clearOverlays();    //清除地图上所有覆盖物
			function myFun(){
				var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
				map.centerAndZoom(pp, 18);
				var marker = new BMap.Marker(pp);
				map.addOverlay(marker);    //添加标注
				lat = pp.lat
				lng = pp.lng;
				address = $("#suggestId").val();
				marker.enableDragging();
				marker.addEventListener("dragend", function(e){
				  lat=e.point.lat;
				  lng=e.point.lng;
				});  
				$.post('/club-activity/find',{lng: pp.lng, lat: pp.lat, keyword:$("#suggestId").val()},function(data){
					if(data){
						data = $.parseJSON(data); 
						var lis = '';
						$.each(data.pointList,function(i,value){
							lis += '<li class="p10" lng="'+value.location.lng+'" lat="'+value.location.lat+'" address="'+value.address+'">'+value.name+'</li>';
						});
						$("#l-suggest ul").html(lis);
						$(".result-list li:odd").addClass("f0bg");
					}
				});
			}
			var local = new BMap.LocalSearch(map, { //智能搜索
			  onSearchComplete: myFun
			});
			local.search(myValue);
		}

		function setFromlist(li){
			map.clearOverlays();
			var pp = new BMap.Point(li.attr("lng"),li.attr("lat"));
			map.centerAndZoom(pp, 16);
			var marker = new BMap.Marker(pp);
			map.addOverlay(marker);    //添加标注
			lat = pp.lat
			lng = pp.lng;
			$("#suggestId").val(li.text()+'（'+li.attr("address")+'）');
			marker.enableDragging();
			marker.addEventListener("dragend", function(e){
			  lat=e.point.lat;
			  lng=e.point.lng;
			});  
		}

		$(document).on("click","#l-suggest ul li",function(){
			setFromlist($(this));
		});

		function saveaddress(){
			address = $("#suggestId").val();
			$("").zh($("#bar"),$("#foo,#footer"));
			$("#activityform-address").val($("#suggestId").val());
			$("#activityform-lng").val(lng);
			$("#activityform-lat").val(lat);
		}

		$("#r-result i.iconfont").click(function(){
			$("#suggestId").val("");
			map.clearOverlays();
			lat = '';
			lng = '';
		});


		/*var editor = new wysihtml5.Editor('editor', {
		    toolbar: 'toolbar',
		    stylesheets: ["http://yui.yahooapis.com/2.9.0/build/reset/reset-min.css"],
		    parserRules:  wysihtml5ParserRules // defined in file parser rules javascript
		  });*/


		$(".toolbar-item-title").click(function(){
		    $(".toolbar-menu-title").evToggle();
		})
		$(".toolbar-menu-title li").click(function(){
		    $(".toolbar-menu-title").hide();
		})

		 $(".toolbar-item-color").click(function(){
		    $(".toolbar-menu-color").evToggle();
		})
		$(".font-color").click(function(){
		    $(".toolbar-menu-color").hide();
		})

		 $(".toolbar-item-alignment").click(function(){
		    $(".toolbar-menu-alignment").evToggle();
		})
		$(".toolbar-menu-alignment .menu-item").click(function(){
		    $(".toolbar-menu-alignment").hide();
		})
<?php $this->endBlock()?>
<?php
$this->registerCssFile("/assets/stylesheet/mobiscroll.custom-2.5.0.min.css");
$this->registerJsFile("/assets/script/jquery.mobile-1.3.0.min.js",['depends'=>'\h5\assets\AppAsset']);
$this->registerJsFile("/assets/script/mobiscroll.custom-2.5.0.min.js",['depends'=>'\h5\assets\AppAsset']);
$this->registerJsFile("http://api.map.baidu.com/api?v=2.0&ak=qrDz4DGnKDfg0WtdDkOYn0Op");
\yii\web\YiiAsset::register($this);
$this->registerJs($this->blocks['JS_BaiDu'],\yii\web\View::POS_END);
?>