<?php
namespace h5\controllers;

use api\models\V1\Invoice;
use h5\models\InvoiceForm;


class InvoiceController extends \yii\web\Controller
{

    public function actionIndex()
    {
        //用户的发票信息
        $my_invoices = Invoice::find()->where(['customer_id' => \Yii::$app->user->getId(), 'status' => 1])->all();

        return $this->render('index', ['my_invoices' => $my_invoices]);
    }
    public function actionCreate()
    {
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/invoice/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        $model = new InvoiceForm();
        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            if(strpos($url,'checkout/index') ){
                \Yii::$app->session->set('checkout_invoice_id', $model->invoice_id);
            }
            return $this->redirect($url);
        } else {
            return $this->render('create', [
                'model' => $model,
                //'all_range' => $all_range
            ]);
        }
    }

    public function actionUpdate($id)
    {
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/invoice/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }


        $model = new InvoiceForm($id);

        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            return $this->redirect($url);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/address/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        $invoice = Invoice::findOne(['invoice_id'=>$id]);
        $invoice->status = 0;
        $invoice->save();
        if(\Yii::$app->session->get('checkout_invoice_id')==$id){
            \Yii::$app->session->remove('checkout_invoice_id');
        }
        return $this->redirect($url);
    }
    public function actionMyInvoices(){
        //checkout选择发票页面
        $data[] = ['invoice_id'=>0,'type_invoice'=>'不需要发票','title_invoice'=>'不需要发票'];
        if(\Yii::$app->request->isAjax && \Yii::$app->request->isPost){
            $model = Invoice::find()->where(['customer_id'=>\Yii::$app->user->getId(),'status'=>1])->all();
            if($model){
                foreach($model as $value){
                    if($value->type_invoice == 2){
                        $type_invoice = '企业增值税普票';
                    }elseif($value->type_invoice == 3){
                        $type_invoice = '企业增值税专票';
                    }elseif($value->type_invoice == 1){
                        $type_invoice = '个人发票';
                    }
                    $data[]=[
                        'invoice_id'=>$value->invoice_id,
                        'type_invoice'=>$type_invoice,
                        'title_invoice'=>$value->title_invoice?$value->title_invoice:"错误的发票抬头",
                        'code'=>$value->code,
                        'address_and_phone'=>$value->address_and_phone,
                        'bank_and_account'=>$value->bank_and_account,
                    ];
                }
            }

        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
}
?>