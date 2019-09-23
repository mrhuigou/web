<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_promotion_description}}".
 *
 * @property integer $id
 * @property integer $customer_promotion_id
 * @property integer $min_order_count
 * @property integer $max_order_count
 * @property string $min_order_total
 * @property string $max_order_total
 */
class CustomerPromotionDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_promotion_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_promotion_id', 'min_order_count', 'max_order_count'], 'integer'],
            [['min_order_total', 'max_order_total'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_promotion_id' => 'Customer Promotion ID',
            'min_order_count' => 'Min Order Count',
            'max_order_count' => 'Max Order Count',
            'min_order_total' => 'Min Order Total',
            'max_order_total' => 'Max Order Total',
        ];
    }
    public function getCustomerPromotion(){
    	return $this->hasOne(CustomerPromotion::className(),['id'=>'customer_promotion_id']);
    }
    public function getGift(){
    	return $this->hasMany(CustomerPromotionDescriptionGift::className(),['customer_promotion_description_id'=>'id']);
    }
}
