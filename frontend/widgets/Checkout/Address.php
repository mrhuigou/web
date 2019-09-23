<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace frontend\widgets\Checkout;

use yii\widgets\InputWidget;
use yii\helpers\Html;
class Address extends InputWidget
{
    public $address_id;
    public $address_list;

    public function init()
    {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->id;
        $this->address_id = $this->model->address_id;
        if ($model = \api\models\V1\Address::find()->where(['customer_id' => \Yii::$app->user->getId()])->orderBy('address_id desc')->all()) {
            foreach ($model as $key=>$address) {
                if(!$this->address_id && $key==0){
                    $this->address_id=$address->address_id;
                }
               $this->address_list[] = $address;
            }
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
        return $this->render('address', ['address_list' => $this->address_list, 'address_id' => $this->address_id,'input'=>$input]);
    }
} 