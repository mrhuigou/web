<?php

namespace common\component\Payment\Billpay;

class BillpaySubmit {
    public $billpaykey;
    public $billpaycert;
    function __construct(){
        $this->billpaykey = __DIR__.'/99bill-rsa.pem';
       $this->billpaycert = __DIR__.'/99bill.cert.rsa.20340630.cer';
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

        $sHtml = "<form id='billpay-form' action='".$form['serverUrl']."'  method='".$form['method']."'  _input_charset='".trim(strtolower($form['inputCharset']))."' >";
        while (list ($key, $val) = each ($para_temp)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."</form>";
        $sHtml = $sHtml."<script>  document.getElementById('billpay-form').submit();</script>";

        return $sHtml;
    }
    function justify_params($kq_va,$kq_na){
        if($kq_va == ""){
            $kq_va="";
        }else{
            return $kq_va=$kq_na.'='.$kq_va.'&';
        }
    }
}
?>
