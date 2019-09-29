/*
*使用方式
*$(".cls").followit({bizid:"MjM5NjQ3MDk5NA=="});
*/
~!(function(w,$){
	var _opts={
		bizid:"MjM5NjQ3MDk5NA=="
	}
	var _wxutil={}
	_wxutil.followit=function(bizid){
		var href = "https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz="+bizid+"#wechat_redirect";
		w.open(href);
	}
	$.fn.extend({
		 		followit: function (options){
	            var defaults = _opts;
	            var options = $.extend(defaults, options);
	            return this.each(
				function () {
	                var o = options;
	                var obj = $(this);
					$(this).unbind().click(function(){_wxutil.followit(options.bizid)})
					}
				)
				
				console.log(options)
			}
		}
	 );
	 w.wxutil = _wxutil;
})(window,jQuery||Zepto)