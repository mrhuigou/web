
$(function() {
    var hit = 10; // 下拉提示列表长度，可修改
    var searchBox = $('#searchBox');
    var ajaxTimer = null;
    var delay = 500; // 单位ms


    window.setTimeout(function() {
        searchBox.autocomplete({
            source: function(request, response) {
                // 输入结束才发送请求
                if (ajaxTimer) {
                    window.clearTimeout(ajaxTimer);
                }

                ajaxTimer = window.setTimeout(function () {
                    // 只有input有值才发送请求
                    searchBox.val() !== '' && $.ajax({
                        url: '/search/get-suggester' /*输入你的serverUrl*/,
                        xhrFields: {
                            withCredentials: true
                        },
                        dataType: 'json',
                        data: {
                            query: searchBox.val()
                        },

                        success: function(data) {
                            if(data.length >= hit) {
                                response(data.slice(0, hit));
                            } else {
                                response(data);
                            }
                        }
                    });
                }, delay);
            }
        }).bind('input.autocomplete', function () {
            // 修复Firefox不支持中文
            searchBox.autocomplete('search', searchBox.val());
        });
    }, 0);
});