<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace fx\widgets\Checkout;
use yii\widgets\InputWidget;
use yii\helpers\Html;
class Invoice extends InputWidget
{
    public $id;
    public $invoice;

    public function init()
    {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        if(!$this->invoice && ($model=\api\models\V1\Invoice::findOne($this->model->invoice_id))){
                $this->invoice=$model;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            $input= Html::activeHiddenInput($this->model, $this->attribute, ['id' => $this->id]);
        } else {
            $input= Html::hiddenInput($this->name, $this->value, ['id' => $this->id]);
        }
        return $this->render('invoice', ['invoice' => $this->invoice,'input'=>$input]);
    }
}