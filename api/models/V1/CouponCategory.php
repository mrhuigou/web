<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%coupon_category}}".
 *
 * @property integer $coupon_id
 * @property integer $category_id
 */
class CouponCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%coupon_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'category_id'], 'required'],
            [['coupon_id', 'category_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'coupon_id' => 'Coupon ID',
            'category_id' => 'Category ID',
        ];
    }
    public function getCategoryDescription(){
        return $this->hasOne(CategoryDescription::className(),['category_id'=>'category_id']);
    }
}
