var imgLoad = (function(){
	return function(url, callback, errorCb){
		var img = new Image();

		img.src = url;

		if (img.complete) {
            callback.call(img);
            return;
        };

        img.onload = function () {
        	callback.call(this);
            img = img.onload = img.onerror = null;
        };

        img.onerror = errorCb || function(){};
	}
})();

$(function(){
	var lotteryEnd = false,
		curObj = $('#swipe li').eq(config.swipeCur),
		curSrc = getSrc(curObj.find('div'));

	// 判断是否需要立刻 模糊初始化
	if(curObj.data('role') == 'blur'){
		imgLoad(curSrc, function(){
			curObj.stackBlur({img: this, radius: 10, callback: function(){
				curObj.data('role', '');
				initLottery();
			}});
		}, initLottery);
	}else{
		imgLoad(curSrc, initLottery, initLottery);
	}
	

	function getSrc(obj){
		return obj.css('background-image').replace(/^url\(|\)$/g, '');
	}
	function loadImg(){
		$('#swipe li').each(function(i){
			if(i == config.swipeCur) return;

			var src = getSrc($(this).find('div')),
				img = new Image(),
				_this = this;

			img.src = src;

			if($(this).data('role') == 'blur'){
				img.onload = function(){
					$(_this).stackBlur({img: img, radius: 10, callback: function(){
						$(_this).data('role', '');
					}});
				}				
			}
		});
	}

	function initLottery(){
		if(config.coverUrl){
			imgLoad(config.coverUrl, function(){
				$('#lottery').lottery({
					coverType: 'image',
		            cover: config.coverUrl,
		            width: 640,
		            height: $(window).height(),
		            //cbdelay: 300,
		            callback: function(percent){
		            	if(percent > 50 && !lotteryEnd){
		            		lotteryEnd = true;
		            		$(this).parent().removeClass('show').on('webkitTransitionEnd', function(){
		            			initSwipe();

			            		$(this).hide();
		            		});
		            	}
		            },
		            success: function(){
		            	setTimeout(loadImg, 500);
		            	$(this.conNode).addClass('show').on('webkitTransitionEnd', function(){
		            		$('#swipe li').eq(config.swipeCur).show();
		            	});
		            }
				});
			}, failLottery);
		}else{
			failLottery();
		}
	}

	function failLottery(){
		loadImg();
		initSwipe();
		$('#lottery').hide();
	}

	function initSwipe(){
		var isInitSwipe = $('#swipe li').length > 1;

		if(isInitSwipe){
			$('#swipe').swipe({
				cur: config.swipeCur,
				dir: config.swipeDir,
				success: function(){
					$(this).find('li').eq(config.swipeCur).removeAttr('style');
					// $('.f-hide').removeClass('f-hide');

				}
			});
		}else{
			$('#swipe li').eq(0).show();
			initPage(isInitSwipe);
		}
	}


});