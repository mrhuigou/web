//引用
//maskdiv($(".aaaaa"));
function maskdiv(con,tit,maskclose){
	//标题
	con.find(".popTitle i").text(tit);

	var maskdiv = $(".maskdiv");
	if(!maskdiv.length){
		maskdiv=$('<div class="maskdiv" style="display:none;"></div>');
		maskdiv.appendTo($("body"));
	}
	
	var w,h;
	var ww=$(window).width(),wh=$(window).height();
	var dw=$(document).width(),dh=$(document).height();
	
	w = ww >= dw ? ww : dw;
	h = wh >= dh ? wh : dh;
		
	maskdiv.css({
		"position" : "fixed",
		"top" : "0px",
		"left" : "0px",
		"width" : w + "px",
		"height" : h + "px",
		"background" : "#000",
		"opacity" : 0.5,
		"filter": "alpha(opacity=30)",
		"z-index" : "8000"
	}).show();
	
	var conLeft = (ww-con.width())/2;
	var conTop = (wh-con.height())/2+$(window).scrollTop();
	con.css({
		"top":"50%",//conTop+"px"
		"left":"50%",//conLeft+"px"
		"margin-left":-(con.width()/2),
		"margin-top":-(con.height()/2),
		"position":"fixed",
		"zIndex":"8001"
	}).show();
	
	
	if(maskclose){
		maskdiv.click(function(){
			$(this).hide();
			con.hide();
		});
	}
}

function hidemaskdiv(obj){
	$(".maskdiv").hide();
	obj.hide();
}