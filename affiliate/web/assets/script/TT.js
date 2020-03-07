

/* 下拉框 */
// 结构 .dropdown > ( .dropdown-t ~ (.dropdown-c.dn > .item) )
// 调用 $(".dropdown-1").dropdown();

$.fn.dropdown = function(){
   
    var _this = $(this).find(".dropdown-t");
    
    _this.click(function(e){
        // 不显示光标
        if($(this).is("input")){
            if($(this).focus()){
                $(this).blur();
            }
        }

        e.stopPropagation();
        $(".dropdown-c").hide();
        var this_ = $(this);
        var con = this_.siblings(".dropdown-c");
        con.show();
        con.find("li,.item").hover(function(){
            $(this).addClass("cur");
        },function(){
            $(this).removeClass("cur");
        }).click(function(){
            var text = $(this).text();
            if(this_.is("input")){
                this_.val(text);
            }else{
                this_.html(text);
            }
        })

        $(document).click(function(e){
            e.stopPropagation();
            if(e.target != this_[0]){
                con.hide();
            }    
        });

    });
};



/* tab切换 */
// 结构 .tab-box > (.tab-tit>.tab-tit-tri ~ .tab-con>tab-con-list)
// 调用 $(".tab-box1").tab();

$.fn.tab = function(cur){
    var _this = $(this);

    _this.each(function(){
        var tri = $(this).find(".tab-tit-tri");
        var list = tri.parent().siblings(".tab-con").find(".tab-con-list");

        tri.on('mouseover',function(){
            var __this = $(this),
                idx=__this.index();

            var cur0;

            // 默认不传参数的时候，当前状态的class用cur，有参数则用传入的参数
            if(cur){
                cur0 = cur;
            }else{
                cur0 = 'cur';
            }
            __this.addClass(cur0).siblings().removeClass(cur0);

            list.eq(idx).show().siblings(".tab-con-list").hide();

            //$("img.lazy").scrollLoading();
        })
    })  
}



/* 初始化行数，以及显示隐藏 */
// 结构 对象 > (.item ~ .more)
// 调用 $('.rowMore').rowMore(1); 或者 $('.normal-items2').rowMore(1);(以前的命名是normal-items)

$.fn.extend({
    // numline是默认显示几行
    rowMore: function(numline){
        return this.each(function(){

            var box = $(this);
            var boxH = $(this).height();
            var itemH = $(this).find(".item").outerHeight();
            //console.log(itemH);
            //console.log(boxH);

            if(boxH > itemH*parseInt(numline)){
                $(this).height(itemH*parseInt(numline));
                $(this).find(".more").show();
            }

            $(".more").click(function(){
                if(box.height() > itemH*parseInt(numline)){
                    box.height(itemH*parseInt(numline));
                    if($(this).is(".iconfont")){
                        $(this).css({"transform":"rotate(360deg)"})
                    }
                }else{
                    box.height(boxH);
                    if($(this).hasClass("iconfont")){
                        $(this).css({"transform":"rotate(180deg)"})
                    }
                }
            })
      
        });
    }
});



/* 条件过滤器 */
$.fn.filterList = function(){
    var _this = $(this);

    var t = _this.find("dd:nth-child(2)");
    t.each(function(){
        var h = $(this).height();
        if(h > 33){
            $(this).css("height","31px").next('dd').show();
        }
    })
    //使用js对其隐藏，是为了获得h的值，用css提前隐藏的话，会得不到高度
    //$(".filter-list").hide();

    _this.on("click","dd.last",function() {
        $(this).addClass('cur').prev().css("height","auto");
    });
    _this.on("click","dd.last.cur",function() {
        $(this).removeClass('cur').prev().css("height","31px");
    });

    //更多条件
    if(_this.find("dl").length > 3){
        var more = _this.find("dl:gt(2)");
        more.hide();
        _this.find(".filter-more").show().click(function(){
            if(more.is(":visible")){
                more.slideUp();
            }else{
                more.slideDown();
            }

        });
    }
}



$.fn.which = function(){
    var _this = $(this);
    _this.find('.item').click(function(){
        $(this).addClass('cur').siblings().removeClass('cur');
        _this.find('.concel').click(function(e){
            e.stopPropagation();
            $(this).parents('.item').removeClass('cur');
        })
    });
}



/* 圆圈 nav */
$.fn.circlePlus = function(){
    var _this = $(this),
        ava = _this.find('.circle-ava');

    ava.click(function(){
        if(_this.hasClass('on')){
            _this.removeClass('on');       
        }else{
            _this.addClass('on');      
        }
    })
}



/* flycart */
/*
    <span class="fly-btn">加入购物车</span>
    <img src="images/img2.jpg" width="178" height="232" class="fly-obj">
    <div class="fly-tar">购物车</div>
*/
$(".fly-btn").click(function(){
    var obj=$(".fly-obj");
    var flyobj = obj.clone();
    $("body").append(flyobj);
    flyobj.css({
        'z-index':  9000,
        'display':  'block',
        'position': 'absolute',
        'top':      obj.offset().top +'px',
        'left':     obj.offset().left +'px'
    });
    flyobj.animate({
        top: $('.fly-tar').offset().top,
        left: $('.fly-tar').offset().left+10,
        width: 20,
        height: 32
    }, 'slow', function() {
        flyobj.remove();
    });
});

// 飞动效果 A点 到 B点
// 锚点 距离顶部的高度可以自定义



/* 基于类 插件开发 */
/* 加法减法 */
// 调用 var i = $.add(5,6);
// 调用 var j = $.minus(5,6);
$.extend({
    add: function(a, b){return a+b},
    minus: function(a, b){return a-b}
});



/* 基于对象 插件开发 */
/* 表单的选择、取消选择 */
// 调用 $('input[type=checkbox]').check();
$.fn.extend({
    check: function(){
        return this.each(function(){
            this.checked = true;
        });
    },
    uncheck: function(){
        return this.each(function(){
            this.checked = false;
        });
    }
});



/* 文本框和密码框的切换 */
$.fn.pwdTextSwitch = function(){
    var _switch = $(this).find(".pwd-text-switch"),
        input = $(this).find(".pwd-text-input");

    _switch.click(function(){
        var _this=$(this);

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
    })
}



/* num */
//基础代码
$.fn.num = function(){
    var wrap = $(this),
        add = wrap.find(".num-add"),
        lower = wrap.find(".num-lower"),
        text = wrap.find(".num-text");

    add.click(function(){
        var a = text.val()
        a++;
        text.val(a);
        if(text.val() == 0){
            lower.addClass("first");
        }else{
            lower.removeClass("first");
        }
    });
    lower.click(function(){
        var a = text.val();
        if(a>0){
            a--;
        }
        text.val(a);
        if(text.val() == 0){
            lower.addClass("first");
        }else{
            lower.removeClass("first");
        }
    });
    //显示隐藏的需求： 有此需求的需要在跟num-wrap平级div上加上class名num-dynamic
    if(wrap.hasClass("num-dynamic")){
        var _this = $(this),
            t = _this.find(".num-text"),
            a = _this.find(".num-add"),
            l = _this.find(".num-lower");

        t.each(function(){
            if(parseInt($(this).val()) > 0){
                l.show();
            }else{
                l.hide();
            }
        });
        a.click(function(){
            if(parseInt(t.val()) > 0){
                l.show();
                t.show();
            }else{
                l.hide();
                t.hide();
            }
        })
        l.click(function(){
            if(parseInt(t.val()) > 0){
                $(this).show();
                t.show();
            }else{
                $(this).hide();
                t.hide();
            }
        })
    }
}



/* fadetop */
$.fn.fadetop = function(){
    var _this = $(this);
    $(window).scroll(function(){
        if($(window).scrollTop()>50){
            _this.fadeIn();
        }else{
            _this.fadeOut();
        }
    })
}



/* faderight */
$.fn.faderight = function(){
    var _this = $(this);
    setTimeout(function(){_this.animate({right: 0},300);},500);
}



/* 返回顶部 */
// 参数 box: 是滑动的容器，有时是window的滑动，有时是某个容器内的滑动  :window or "class"
// 调用 $.backtop(".content");

$.backtop = function(box){
    var backtopTitle = "返回顶部";
    var backtopEle = $('<div class="backtop iconfont"></div>');
    backtopEle.appendTo($("body")).attr("title", backtopTitle).css({
        // opacity: .4
    }).click(function(){
        $("html, body, .content").animate({
            scrollTop: 0
        }, 120);
    });

    backtopScroll = function(){
        var st = $(box).scrollTop(), winh = $(window).height();
        (st > 0) ? backtopEle.show() : backtopEle.hide();
        // IE6下的定位
        if(!window.XMLHttpRequest){
            backtopEle.css("top", st + winh -166);
        }
    };

    $(box).bind("scroll", backtopScroll);
    $(function(){
        backtopScroll();
    })
}
/* 自己写的返回顶部 对比上面发现不同*/
// function st(){
//     if($(window).scrollTop() > 50){
//         $(".backtop").fadeIn();
//     }else{
//         $(".backtop").hide();
//     }
// };
// st();
// $(window).scroll(function(){
//     st();
// });

// $(".backtop").click(function(){
//     $('body,html').stop().animate({scrollTop:0},500);
// });





/* 点击展开收起 待更新 */
// $.fn.extend({
//     evToggle:function(){
//         if($(this).is(":visible"))
//         {
//             $(this).hide();
//         }else{
//             $(this).show();
//         }
//     }
    
// });

//  jQuery.fn.extend({
//       zh: function(o,tar) {
//         o.fadeOut();
//         tar.fadeIn();
//       }
// });




