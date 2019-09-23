<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_behavior}}".
 *
 * @property string $id
 * @property integer $item_id
 * @property integer $customer_id
 * @property string $type
 * @property integer $datetime
 */
class CustomerBehavior extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_behavior}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['item_id', 'customer_id', 'datetime'], 'integer'],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'item_id' => 'Item ID',
            'customer_id' => 'Customer ID',
            'type' => 'Type',
            'datetime' => 'Datetime',
        ];
    }
    public function getProductbase(){
        return $this->hasOne(ProductBase::className(),['product_base_id'=>'item_id']);
    }
}
