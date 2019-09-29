/**
 * Created by mac on 2015/3/6.
 */
$.showIndicator = function () {
    $("body").append('<div class="preloader-indicator-overlay"></div><div class="preloader-indicator-modal"><span class="preloader preloader-white"></span></div>');
};
$.hideIndicator = function () {
    $('.preloader-indicator-overlay, .preloader-indicator-modal').remove();
};
var wait=90;
function time(t) {
    if (wait == 0) {
        t.removeAttr("disabled");
        t.css("cursor","pointer");
        t.text("获取验证码");
        wait = 90;
    } else {
        t.attr("disabled", true);
        t.css("cursor","wait");
        t.text("重新发送(" + wait + ")");
        wait--;
        setTimeout(function() {
                time(t)
            },
            1000)
    }
}
function timer(intDiff,obj){
    window.setInterval(function(){
        var day=0,
            hour=0,
            minute=0,
            second=0;//时间默认值
        if(intDiff > 0){
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
		if (hour <= 9) hour = '0' + hour;
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        obj.find('.day_show').html(day+"天");
        obj.find('.hour_show').html(hour);
        obj.find('.minute_show').html(minute);
        obj.find('.second_show').html(second);
        intDiff--;
    }, 1000);
}
//浮点数加法运算
function FloatAdd(arg1,arg2){
    var r1,r2,m;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2));
    return (arg1*m+arg2*m)/m;
}

//浮点数减法运算
function FloatSub(arg1,arg2){
    var r1,r2,m,n;
    try{r1=arg1.toString().split(".")[1].length}catch(e){r1=0}
    try{r2=arg2.toString().split(".")[1].length}catch(e){r2=0}
    m=Math.pow(10,Math.max(r1,r2));
    //动态控制精度长度
    n=(r1>=r2)?r1:r2;
    return ((arg1*m-arg2*m)/m).toFixed(n);
}

//浮点数乘法运算
function FloatMul(arg1,arg2){
    var m=0,s1=arg1.toString(),s2=arg2.toString();
    try{m+=s1.split(".")[1].length}catch(e){}
    try{m+=s2.split(".")[1].length}catch(e){}
    return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m);
}


//浮点数除法运算
function FloatDiv(arg1,arg2){
    var t1=0,t2=0,r1,r2;
    try{t1=arg1.toString().split(".")[1].length}catch(e){}
    try{t2=arg2.toString().split(".")[1].length}catch(e){}
    with(Math){
        r1=Number(arg1.toString().replace(".",""));
        r2=Number(arg2.toString().replace(".",""));
        return (r1/r2)*pow(10,t2-t1);
    }
}
/**
 * 对Date的扩展，将 Date 转化为指定格式的String
 * 月(M)、日(d)、12小时(h)、24小时(H)、分(m)、秒(s)、周(E)、季度(q) 可以用 1-2 个占位符
 * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
 * eg:
 * (new Date()).pattern("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
 * (new Date()).pattern("yyyy-MM-dd E HH:mm:ss") ==> 2009-03-10 二 20:09:04
 * (new Date()).pattern("yyyy-MM-dd EE hh:mm:ss") ==> 2009-03-10 周二 08:09:04
 * (new Date()).pattern("yyyy-MM-dd EEE hh:mm:ss") ==> 2009-03-10 星期二 08:09:04
 * (new Date()).pattern("yyyy-M-d h:m:s.S") ==> 2006-7-2 8:9:4.18
 */
Date.prototype.pattern=function(fmt) {
    var o = {
        "M+" : this.getMonth()+1, //月份
        "d+" : this.getDate(), //日
        "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时
        "H+" : this.getHours(), //小时
        "m+" : this.getMinutes(), //分
        "s+" : this.getSeconds(), //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S" : this.getMilliseconds() //毫秒
    };
    var week = {
        "0" : "\\u65e5",
        "1" : "\\u4e00",
        "2" : "\\u4e8c",
        "3" : "\\u4e09",
        "4" : "\\u56db",
        "5" : "\\u4e94",
        "6" : "\\u516d"
    };
    if(/(y+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
    }
    if(/(E+)/.test(fmt)){
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\\u661f\\u671f" : "\\u5468") : "")+week[this.getDay()+""]);
    }
    for(var k in o){
        if(new RegExp("("+ k +")").test(fmt)){
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        }
    }
    return fmt;
}
function AddCart(id){
    time = (new Date()).valueOf();
    $.showIndicator();
    $("#pop_sku .cart-pop").remove();
    $("#pop_sku").load('/product/sku?id='+id+'&timetmp='+time,function(){
        $.hideIndicator();
        maskdiv($(".cart-pop"),"bottom");
    });
}
//获得对象的key
function getObjKeys(obj) {
    if (obj !== Object(obj)) throw new TypeError('Invalid object');
    var keys = [];
    for (var key in obj)
        if (Object.prototype.hasOwnProperty.call(obj, key))
            keys[keys.length] = key;
    return keys;
}

//把组合的key放入结果集SKUResult
function add2SKUResult(combArrItem, sku) {
    var key = combArrItem.join(";");
    if(SKUResult[key]) {//SKU信息key属性·
        SKUResult[key].count += sku.count;
        SKUResult[key].prices.push(sku.price);
        SKUResult[key].saleprices.push(sku.sale_price);
    } else {
        SKUResult[key] = {
            count : sku.count,
            prices : [sku.price],
            saleprices:[sku.sale_price]
        };
    }
}
//初始化得到结果集
function initSKU() {
    var i, j, skuKeys = getObjKeys(sku_datas);
    for(i = 0; i < skuKeys.length; i++) {
        var skuKey = skuKeys[i];//一条SKU信息key
        var sku = sku_datas[skuKey];	//一条SKU信息value
        var skuKeyAttrs = skuKey.split(";"); //SKU信息key属性值数组
        skuKeyAttrs.sort(function(value1, value2) {
            return parseInt(value1) - parseInt(value2);
        });

//对每个SKU信息key属性值进行拆分组合
        var combArr = combInArray(skuKeyAttrs);
        for(j = 0; j < combArr.length; j++) {
            add2SKUResult(combArr[j], sku);
        }

//结果集接放入SKUResult
        SKUResult[skuKeyAttrs.join(";")] = {
            count:sku.count,
            prices:[sku.price],
            saleprices:[sku.sale_price]
        }
    }
}

/**
 * 从数组中生成指定长度的组合
 * 方法: 先生成[0,1...]形式的数组, 然后根据0,1从原数组取元素，得到组合数组
 */
function combInArray(aData) {
    if(!aData || !aData.length) {
        return [];
    }

    var len = aData.length;
    var aResult = [];

    for(var n = 1; n < len; n++) {
        var aaFlags = getCombFlags(len, n);
        while(aaFlags.length) {
            var aFlag = aaFlags.shift();
            var aComb = [];
            for(var i = 0; i < len; i++) {
                aFlag[i] && aComb.push(aData[i]);
            }
            aResult.push(aComb);
        }
    }

    return aResult;
}


/**
 * 得到从 m 元素中取 n 元素的所有组合
 * 结果为[0,1...]形式的数组, 1表示选中，0表示不选
 */
function getCombFlags(m, n) {
    if(!n || n < 1) {
        return [];
    }

    var aResult = [];
    var aFlag = [];
    var bNext = true;
    var i, j, iCnt1;

    for (i = 0; i < m; i++) {
        aFlag[i] = i < n ? 1 : 0;
    }

    aResult.push(aFlag.concat());

    while (bNext) {
        iCnt1 = 0;
        for (i = 0; i < m - 1; i++) {
            if (aFlag[i] == 1 && aFlag[i+1] == 0) {
                for(j = 0; j < i; j++) {
                    aFlag[j] = j < iCnt1 ? 1 : 0;
                }
                aFlag[i] = 0;
                aFlag[i+1] = 1;
                var aTmp = aFlag.concat();
                aResult.push(aTmp);
                if(aTmp.slice(-n).join("").indexOf('0') == -1) {
                    bNext = false;
                }
                break;
            }
            aFlag[i] == 1 && iCnt1++;
        }
    }
    return aResult;
}
function addToWishList(item_id,type){
    $.showIndicator();
    $.post( '/collect/add',{'item_id':item_id,'type':type},function(data){
        $.hideIndicator();
        layer.open({
            content: data.message,
            time: 1
        });
    },'json');
}
$(document).ready(function(){
    $("img.lazy").scrollLoading();
    $(".search-btn").click(function(){
            if($('input[name="keyword"]').val()){
                $("#search_form").submit();
            }else{
                layer.open({
                    content: '请输入搜索内容',
                    time: 2
                });
            }

        }
    );
    /*~~ 文本框输入内容时出现删除图标 ~~*/
    $(".input-text").keyup(function() {
        if($(this).val()){
            $(this).next(".input-setup").find(".input-del").show();
        }else{
            $(this).next(".input-setup").find(".input-del").hide();
        }
    });
    /*~~ 点击删除图标 重置文本框内容~~*/
    $(".input-del").click(function() {
        $(this).hide().parent(".input-setup").prev(".input-text").val("").focus();
    });
    /*~~ 密码框可见加密的切换 ~~*/
    $(".pwd-btn").click(function(){
        var _this=$(this),
            input=_this.parent(".input-setup").prev(".input-text");

        if(_this.is(".right")){
            //切换可见图标
            _this.removeClass('right').addClass('left');
            //切换为普通文本框
            input.attr("type","text").focus();

        }else if(_this.is(".left")){
            //切换加密图标
            _this.addClass('right').removeClass('left');
            //切换为密码框
            input.attr("type","password").focus();
        }
    });

    $("#send-vcode").on('click',function (){
        if($(".telephone").length > 0 ){
            var phone_num = $(".telephone").val();
        }else{
            var phone_num = $(".telephone").val();
        }

        var myreg = /^1[3456789]\d{9}$/;
        if(!myreg.test(phone_num)){
            alert('请输入正确的手机号');
            return false;
        }else{

            time( $("#send-vcode"));
            $.showLoading();

            $.post('/site/sendcode',{telephone:phone_num},function (data) {
                if(data.status){
                    alert(data.msg);
                }else{
                    alert(data.msg);
                }
                $.hideLoading();
            },'json');


        }
        });

    //筛选
    $(".filter-tri").click(function() {
        if($(".filter-list").is(":visible")){
            $(".filter-list").hide();
        }else{
            $(".filter-list").show();
            //已选择条件
            var t=$(".filter-list dd:nth-child(2)");

            t.each(function(){
                var h=$(this).height();
                //  alert(h);
                if(h>30){
                    $(this).css("height","26px").next('dd').show();
                }
            })
        }
    });
    $(".filter-list dd.last").live("click",function() {
        $(this).addClass('cur').prev().css("height","auto");
    });
    $(".filter-list dd.last.cur").live("click",function() {
        $(this).removeClass('cur').prev().css("height","26px");
    });

    /*tab切换
     *html结构
     <div class="tab-box">
        <div class="tab-tit">
            <a href="javascript:void(0)" class="tab-tit-tri">项目一</a>
            <a href="javascript:void(0)" class="tab-tit-tri">项目二</a>
        </div>
        <div class="tab-con">
            <div class="tab-con-list">内容一</div>
            <div class="tab-con-list">内容二</div>
        </div>
     </div>
    */
    $(".tab-tit-tri").click(function(){
        var _this=$(this),
            idx=_this.index(),
            con=_this.parents(".tab-box").find(".tab-con-list");
        if(_this.hasClass("cur")){
            //关闭
            _this.removeClass("cur");
            con.eq(idx).hide()
        }else{
            _this.addClass("cur").siblings().removeClass("cur");
            con.eq(idx).show().siblings(".tab-con-list").hide();
            $("img.lazy").scrollLoading();
        }
    })


    $("body").on('blur',".item-num-text",function(){
        var num_obj=$(this);
        var max=num_obj.attr('max');
        var min=num_obj.attr('min');
        var qty=parseInt(num_obj.val());
        if(!max){
            max=100;
        }
        if(!min){
            min=1;
        }
        if(isNaN(qty) && qty < min){
            qty=min;
        }
        if(qty <= parseInt(max) && qty >= parseInt(min)){
            num_obj.val(qty);
        }else{
            num_obj.val(min);
        }
    });
    $("body").on('click',".item-num-add",function(){
        var num_obj=$(this).siblings(".item-num-text");
        var max=num_obj.attr('max');
        var min=num_obj.attr('min');
        var qty=parseInt(num_obj.val());
        if(!max){
            max=100;
        }
        if(!min){
            min=1;
        }
        qty++;
        if(isNaN(qty) && qty < min ){
            qty=min;
        }
        if(qty <= parseInt(max) && qty >= parseInt(min)){
            num_obj.val(qty);
        }else{
            return;
        }
    });
    $("body").on('click',".item-num-lower",function(){
        var num_obj=$(this).siblings(".item-num-text");
        var max=num_obj.attr('max');
        var min=num_obj.attr('min');
        var qty=parseInt(num_obj.val());
        if(!max){
            max=100;
        }
        if(!min){
            min=1;
        }
        qty--;
        if(isNaN(qty) && qty < min ){
            qty=min;
        }
        if(qty <= parseInt(max) && qty >= parseInt(min)){
            num_obj.val(qty);
        }else{
            return;
        }
    });

    //选择地址点击整行选中
    $(".label-addr").click(function(){
        $(this).parents("table").find("input").attr("checked",false);
        $(this).prev().find("input").attr("checked",true);
    });
    /*展开促销、包装详情*/
    $(".shipping_date").click(function(){
        $(this).hide();
        $(".shipping_time").show();
    });

    /*展开促销、包装详情*/
    $("dl.line-a dt").toggle(function(){
        $(this).addClass('cur').next("dd").show();
    },function(){
        $(this).removeClass('cur').next("dd").hide();
    });
    $("body").on('click','.close-pop',function(e){
        e.stopPropagation();
        $(".cart-pop").slideUp();
        $(".maskdiv").remove();
    });
    /*下载APP*/
    $(".app-tips .close").click(function(event) {
        $(this).parents(".app-tips").hide();
    });
    $(".backtop").click(function(){
        $('body,html').stop().animate({scrollTop:0},500);
    });
    $("body").on('click','#J_LinkBuy',function(){
        $.showIndicator();
        if(Sku!=""){
            var qty=parseInt($(":input[name='qty']").val());
            if(isNaN(qty)){
                qty=1;
            }
            if(sku_datas[Sku].count<qty){
                $.hideIndicator();
                layer.open({
                    content: '库存不足，最大可购买'+sku_datas[Sku].count+'件！',
                    skin: 'msg',
                    time: 2
                });
            }else {
            $.post('/cart/buynow',{'product_base_id':product_base_id,'sku':Sku,'qty':qty},function(data){
                $.hideIndicator();
                if(data.status){
                    $(".close-pop").click();
                    $("#pop_sku .cart-pop").remove();
                    location.href=data.data;
                }else{
                    layer.open({
                        content: data.message,
                        skin: 'msg',
                        time: 2
                    });
                }
                },'json');
            }
        }else{
            $.hideIndicator();
            layer.open({
                content: '请选择包装/规格',
                skin: 'msg',
                time: 2
            });
        }
    });
    $("body").on('click','#J_LinkBasket',function(){
        $.showIndicator();
        if(Sku!=""){
            var qty=parseInt($(":input[name='qty']").val());
            if(isNaN(qty)){
                qty=1;
            }
            if(sku_datas[Sku].count<qty){
                $.hideIndicator();
                layer.open({
                    content: '库存不足，最大可购买'+sku_datas[Sku].count+'件！',
                    skin: 'msg',
                    time: 2
                });
            }else {
                $.post('/cart/add-to-cart', {
                    'product_base_id': product_base_id,
                    'sku': Sku,
                    'qty': qty
                }, function (data) {
                    $.hideIndicator();
                    if(data.status){
                        $(".close-pop").click();
                        $(".cart_qty").text(data.data);
                        $(".cart_qty").show();
                        $("#pop_sku .cart-pop").remove();
                        layer.open({
                            content: '添加购物车成功',
                             skin: 'msg',
                            time: 2 //2秒后自动关闭
                        });
                    }else{
                        layer.open({
                            content:data.message,
                            skin: 'msg',
                            time: 2
                        });
                    }
                },'json');
            }
        }else{
            $.hideIndicator();
            layer.open({
                content: '请选择包装/规格',
                skin: 'msg',
                time: 2
            });
        }
    });
    $(".followit").followit(
        {bizid:"MjM5NjQ3MDk5NA=="}
    );
    $("body").on('click',".coupon-item-apply",function(){
        $.showLoading("正在加载");
        var coupon_code = $(this).attr('data-content');
        $.post('/coupon/ajax-apply',{coupon_code:$(this).attr('data-content')},function(data){
            $.hideLoading();
            $.modal({
               // title: '提示',
                text: data.message,
                buttons: [
                    { text: "确定", className: "default"},
                    { text: "可使用商品", onClick: function(){  window.location.href = "/coupon/view?code="+coupon_code; } }
                ]
            });
        });
    });
});
function autoSourceFrom(code) {
    var s='sourcefrom='+code
        ,as=document.getElementsByTagName('a');
    for(var i=0;i<as.length;i++){
        as[i].href=as[i].href+(as[i].href.indexOf('?')==-1?'?':'&')+s
    }
}
function getSourceParms() {
    var sourcefrom = GetQueryString('sourcefrom');
    var neednt_refresh = GetQueryString('neednt_refresh');
    if(sourcefrom){
         var result =  'sourcefrom='+ sourcefrom;
    }else{
        var result = '';
    }
    if(neednt_refresh){
        if(result){
            result +=  '&neednt_refresh=1';
        }else{
            result =  'neednt_refresh=1';
        }

    }
    result = '';
    return result;

}
function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}


