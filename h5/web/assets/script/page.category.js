/*
 * 分类页
 *
 * @date : 2017-3-3
 * @author : Jlp
 *
 */

var array=new Array();

// 每个分类对应父级的偏移存入array数组
$('.cate-sidenav a').each(function(){ 
	array.push($(this).position().top); 
});

$('.cate-sidenav a').click(function() {
	var index=$(this).index();
	var next = parseInt(index-3)<= 0 ? 0 : array[parseInt(index-3)];
	$('.cate-sidenav').delay(200).animate({scrollTop:next},200);
	$(this).addClass('cur').siblings().removeClass('cur');
});
