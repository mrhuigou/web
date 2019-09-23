var browser = {};

var ua = navigator.userAgent.toLowerCase();

browser.msie = (/msie ([\d.]+)/).test(ua);
browser.firefox = (/firefox\/([\d.]+)/).test(ua);
browser.chrome = (/chrome\/([\d.]+)/).test(ua);

function SetNodeStyle(doc, node, name, value)
{
    if (node.innerHTML == undefined)
    {
        return node;
    }
    else
    {
        node.style[name] = value;

        for (var i = 0; i < node.childNodes.length; i++)
        {
            var cn = node.childNodes[i];
            if (node.innerHTML != undefined)
            {
                SetNodeStyle(doc, cn, name, value);
            }
        }

        return node;
    }
}

function SetStyle(doc, html, name, value)
{
    var dom = doc.createElement("DIV");
    dom.innerHTML = html;

    for (var i = 0; i < dom.childNodes.length; i++)
    {
        var node = dom.childNodes[i];

        if (node.innerHTML == undefined)
        {
            var span = doc.createElement("SPAN");
            span.style[name] = value;
            if (node.nodeValue != undefined) span.innerHTML = node.nodeValue.replace(/\</ig, function() { return "&lt;"; });
            else if (node.textContent != undefined) span.innetHTML = node.textContent.replace(/\</ig, function() { return "&lt;"; });
            dom.replaceChild(span, node);
        }
        else
        {
            SetNodeStyle(doc, node, name, value);
        }
    }

    return dom.innerHTML;
}

function GetInnerHTML(nodes)
{
    var builder = [];
    for (var i = 0; i < nodes.length; i++)
    {
        if (nodes[i].innerHTML != undefined)
        {
            builder.push(nodes[i].innerHTML);
        }
        else
        {
            if (nodes[i].textContent) builder.push(nodes[i].textContent.replace(/\</ig, function() { return "&lt;"; }));
            else if (nodes[i].nodeValue) builder.push(nodes[i].nodeValue.replace(/\</ig, function() { return "&lt;"; }));
        }
    }
    return builder.join("");
}

function SelectionRange(doc, range)
{
    this.GetSelectedHtml = function()
    {
        if (range == null) {
            return "";
        }else{
            return GetInnerHTML(range.cloneContents().childNodes);
        }
    }

    this.Replace = function(html)
    {
        if (range != null)
        {
                if (range.deleteContents != undefined && range.insertNode != undefined)
                {
                    var temp = doc.createElement("DIV");
                    temp.innerHTML = html;

                    var elems = [];
                    for (var i = 0; i < temp.childNodes.length; i++)
                    {
                        elems.push(temp.childNodes[i]);
                    }

                    range.deleteContents();

                    for (var i in elems)
                    {
                        temp.removeChild(elems[i]);
                        range.insertNode(elems[i]);
                    }
                    return true;
                }

        }
        return false;
    }
}

function GetSelectionRange(win)
{
    var range = null;

    var sel = win.getSelection();
    if (sel.rangeCount > 0){
        range = sel.getRangeAt(0);
    }

    return new SelectionRange(win.document, range);
}

function H5Editor(container,bindObj, width, height)
{
    container.innerHTML =
        '<iframe frameborder="0" style="padding: 0px;width: 100%; "></iframe>'+
        '<div class="simditor-toolbar">' +
        '<a class="toolbar-item toolbar-item-bold " href="javascript:;" title="Bold ( Ctrl + b )"><span class="simditor-icon simditor-icon-bold"></span></a>' +
        '<a class="toolbar-item toolbar-item-ul" href="javascript:;" title="Unordered List ( Ctrl + . )"><span class="simditor-icon simditor-icon-list-ul"></span></a>'+
        '<a class="toolbar-item toolbar-item-color" href="javascript:;" title="Text Color"><span class="simditor-icon  simditor-icon-tint"></span></a>'+
        '<div class="toolbar-menu toolbar-menu-color">' +
        '<ul class="color-list">' +
        '<li><a href="javascript:;" class="font-color font-color-1" data-bgColor="#e33737"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-2" data-bgColor="#e28b41"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-3" data-bgColor="#c8a732"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-4" data-bgColor="#209361"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-5" data-bgColor="#418caf"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-6" data-bgColor="#aa8773"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-7" data-bgColor="#999999"></a></li>' +
        '<li><a href="javascript:;" class="font-color font-color-default" data-bgColor="#333333"></a></li>' +
        '</ul>' +
        '</div>' +
        '<a class="toolbar-item toolbar-item-image" href="javascript:;" title="Insert Image"><span class="simditor-icon simditor-icon-picture-o"></span></a>'+
        '<a class="toolbar-item toolbar-item-left" href="javascript:;" title="左对齐"><span class="simditor-icon simditor-icon-align-left"></span></a>'+
        '<a class="toolbar-item toolbar-item-center" href="javascript:;" title="居中"><span class="simditor-icon simditor-icon-align-center"></span></a>'+
        '<a class="toolbar-item toolbar-item-right" href="javascript:;" title="右对齐"><span class="simditor-icon simditor-icon-align-right"></span></a>'+
    '</div>';
    var editor = container.childNodes[0];
    var toolbar = container.childNodes[1];
    var editorDoc = editor.contentWindow.document;
    var editorWindow = editor.contentWindow;
    editor.style.width = width;
    editor.style.height =height;
    editor.onload = function()
    {
        editorDoc.designMode = "on";
    }
    editorDoc.open();
    editorDoc.write("<html><head></head><body style='margin:0px; padding: 0px; width: 100%;'></body></html>");
    editorDoc.close();
    editorDoc.body.innerHTML=bindObj.value;
    toolbar.childNodes[0].onclick = function()
    {
        editorDoc.execCommand("Bold", false, null);

    }

    toolbar.childNodes[1].onclick = function()
    {
        editorDoc.execCommand("InsertUnorderedList");
    }
    toolbar.childNodes[4].onclick = function () {
        wx.chooseImage({
            success: function (res) {
                if(res.localIds.length!==1){
                    alert("只能上传一张图片");
                }else{
                    wx.uploadImage({
                        localId: res.localIds[0],
                        success: function (res) {
                            $.get('/club-comment/download',{media_id:res.serverId},function(data){
                                    var range = GetSelectionRange(editorWindow);
                                    range.Replace("<img src='" + data + "' width='100%';></img>");
                            });
                        },
                        fail: function (res) {
                            alert(JSON.stringify(res));
                        }
                    });
                }
            }});
    }
    toolbar.childNodes[5].onclick = function()
    {
        editorDoc.execCommand("JustifyLeft");
    }
    toolbar.childNodes[6].onclick = function()
    {
        editorDoc.execCommand("JustifyCenter");
    }
    toolbar.childNodes[7].onclick = function()
    {
        editorDoc.execCommand("JustifyRight");
    }
    $(".toolbar-item-color").click(function(){
        var offx=$(this).offset().left;
        var offy=$(this).offset().top;
        $(".toolbar-menu-color").css({"top":offy+40,"left":offx}).evToggle();
    })
    $(".font-color").click(function(){
        var sColor=$(this).attr('data-bgColor');
        $(".toolbar-item-color span").css("color",sColor);
        editorDoc.execCommand("ForeColor",false,sColor);
        $(".toolbar-menu-color").hide();
    })

    editorDoc.body.oninput =function(){
        bindObj.value= editorDoc.body.innerHTML;
    }
    editorDoc.body.onpropertychange =function(){
        bindObj.value= editorDoc.body.innerHTML;
    }
}