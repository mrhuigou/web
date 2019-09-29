/* 弹层 */
//maskdiv($(".aaaaa"),"bottom");
function maskdiv(con,direction){

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

	if(direction=="center"){//中间位置

		con.css({
			"top":"50%",//conTop+"px"
			"left":"50%",//conLeft+"px"
			"margin-left":-(con.width()/2),
			"margin-top":-(con.height()/2),
			"position":"fixed",
			"zIndex":"8001"
		}).show();
		
	}else if(direction=="bottom"){//底部位置

		con.css({
			"bottom":"0",
			"left":"0",
			"right":"0",
			"position":"fixed",
			"zIndex":"8001"
		}).slideDown();

	}
	//点击蒙板关闭层
	maskdiv.click(function(){
		$(this).fadeOut();
		con.fadeOut();
	});
}