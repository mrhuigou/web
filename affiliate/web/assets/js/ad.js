/**
 * Created by mac on 2017/6/12.
 */
//var source = getSourceParms();

function Ad_Sys_Code(code,nocache) {
    $(document).ready(function(){
        $.get('/ad/ad-code',{code:code,nocache:nocache},function (res) {
            if(res.status){
                loadImage(res.image,function () {
                    var tpl='<a class="pa-rt iconfont  close-pop-box whitebg ava sava" href="javascript:;" style="top: -10px;right:0px;line-height:40px; ">&#xe612;</a>'+
                        '<a href="'+res.href+'">'+'<img src="'+this.src+'" class="pw90" style="max-width: 414px;">'+'</a>';
                    layer.open({
                        time:300000,
                        content: tpl,
                        style: 'background:none; border:none;',
                    });
                    $("body").on('click','.close-pop-box', function () {  layer.closeAll();   $.hideLoading(); });
                })
            }
        })
    });

}
function Ad_Sys_Base(code) {
    $.get('/ad/base-code',{code:code},function (res) {
        if(res.status){
            loadImage(res.image,function () {
                var tpl='<a class="pa-rt iconfont  close-pop-box whitebg ava sava" href="javascript:;" style="top: -10px;right:0px;line-height:40px; ">&#xe612;</a>'+
                    '<a href="'+res.href+'">'+'<img src="'+this.src+'" class="pw90" style="max-width: 414px;">'+'</a>';
                layer.open({
                    time:300000,
                    content: tpl,
                    style: 'background:none; border:none;',
                });
                $("body").on('click','.close-pop-box', function () {  layer.closeAll();  });
            })
        }
    })
}
function loadImage(url, callback) {
    var img = new Image(); //创建一个Image对象，实现图片的预下载
    img.src = url;

    if(img.complete) { // 如果图片已经存在于浏览器缓存，直接调用回调函数
        callback.call(img);
        return; // 直接返回，不用再处理onload事件
    }
    img.onload = function () { //图片下载完毕时异步调用callback函数。
        callback.call(img);//将回调函数的this替换为Image对象
    };
};
