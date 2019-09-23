<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace h5\widgets\Checkout;
use yii\widgets\InputWidget;
use yii\helpers\Html;
class Address extends InputWidget
{   public $id;
    public $address;

    public function init()
    {
        $this->id = $this->hasModel() ? Html::getInputId($this->model, $this->attribute) : $this->getId();
        if(!$this->address && ($model=\api\models\V1\Address::findOne($this->model->address_id))){
            if($model->ifInRange){
                $this->address=$model;
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
        return $this->render('address', ['address' => $this->address,'input'=>$input]);
    }
} 