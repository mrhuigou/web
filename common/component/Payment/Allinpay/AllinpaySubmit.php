<?php

namespace common\component\Payment\Allinpay;

class AllinpaySubmit {
    public $allinpaykey;
    function __construct(){
        $this->allinpaykey = __DIR__.'/publickey.txt';
    }



    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function buildRequestForm($form,$para_temp) {
        //待请求参数数组

        $sHtml = "<form id='allinpay-form' action='".$form['serverUrl']."'  _input_charset='".trim(strtolower($form['inputCharset']))."' method='".$form['method']."'>";
        while (list ($key, $val) = each ($para_temp)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."</form>";
        $sHtml = $sHtml."<script>document.getElementById('allinpay-form').submit();</script>";

        return $sHtml;
    }
}
?>
