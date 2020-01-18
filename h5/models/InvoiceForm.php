<?php
namespace h5\models;
use api\models\V1\Invoice;
use Yii;
class InvoiceForm extends Invoice {
    public function __construct($invoice_id=0, $config = [])
    {
        if($model = Invoice::findOne(['invoice_id'=>$invoice_id,'customer_id'=>Yii::$app->user->getId()])){
            $this->invoice_id = $invoice_id;
            $this->type_invoice = $model->type_invoice;
            $this->title_invoice = $model->title_invoice;
            $this->code = $model->code;
            $this->address_and_phone = $model->address_and_phone;
            $this->bank_and_account = $model->bank_and_account;
        }else{
            $this->type_invoice = 2;
        }
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['code','address_and_phone','bank_and_account', 'title_invoice'], 'safe'],
            [['type_invoice'],'ValidateInvoice']
        ];
    }
    public function ValidateInvoice($attribute, $params){
        if($this->type_invoice){

            if($this->type_invoice == 1){
                if(!$this->title_invoice){
                    $this->addError('title_invoice', '个人姓名不能为空!');
                }
                if ( strlen($this->code) !== 18) {
                    $this->addError('code', '身份证号必须是18位!');
                }
            }
            if ($this->type_invoice == 2) {
                if(!$this->title_invoice){
                    $this->addError('title_invoice', '单位名称不能为空!');
                }
                if (strlen($this->code) !== 15 && strlen($this->code) !== 18 && strlen($this->code) !== 20) {
                    $this->addError('code', '单位税号必须是15、18位或20位!');
                }
            }
            if ($this->type_invoice == 3) {
                if(!$this->title_invoice){
                    $this->addError('title_invoice', '单位名称不能为空!');
                }
                if (!$this->code) {
                    $this->addError('code', '纳税人识别号不能为空!');
                }
                if (strlen($this->code) !== 15 && strlen($this->code) !== 18 && strlen($this->code) !== 20) {
                    $this->addError('code', '单位税号必须是15、18位或20位!');
                }
                if (!$this->address_and_phone) {
                    $this->addError('address_and_phone', '注册地址和电话不能为空!');
                }
                if (!$this->bank_and_account) {
                    $this->addError('bank_and_account', '开户行及账号不能为空!');
                }

            }


        }
    }
    public function submit(){
        if ($this->validate()) {
            if($this->invoice_id){
                $model = Invoice::findOne(['invoice_id'=>$this->invoice_id]);
            }else{
                $model = new Invoice();
                $model->customer_id = Yii::$app->user->getId();
            }
            $model->type_invoice = $this->type_invoice;
            $model->title_invoice = $this->title_invoice;
            $model->code = $this->code;
            $model->address_and_phone = $this->address_and_phone;
            $model->bank_and_account = $this->bank_and_account;
            $model->status = 1;
            $model->save();
            $this->invoice_id = $model->invoice_id;
            return true;
        }
    }
}