<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%advertise_related}}".
 *
 * @property integer $advertise_related_id
 * @property integer $advertise_id
 * @property integer $product_base_id
 * @property integer $product_id
 * @property integer $status
 */
class AdvertiseRelated extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertise_related}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['advertise_id', 'product_base_id', 'product_id', 'status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advertise_related_id' => 'Advertise Related ID',
            'advertise_id' => 'Advertise ID',
            'product_base_id' => 'Product Base ID',
            'product_id' => 'Product ID',
            'status' => 'Status',
        ];
    }
    public function getAdvertise(){
        return $this->hasOne(Advertise::className(),['advertise_id'=>'advertise_id']);
    }
}
