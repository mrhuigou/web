<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_promotion_detail}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $start_time
 * @property string $end_time
 * @property string $title
 * @property string $image
 * @property integer $be_have_gift
 * @property integer $be_have_limit
 * @property integer $be_have_stop_buy
 * @property integer $be_free_develiery
 * @property integer $be_have_discount
 * @property integer $status
 * @property integer $store_id
 * @property integer $promotion_id
 */
class SalePromotionDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_promotion_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'be_have_gift', 'be_have_limit', 'be_have_stop_buy', 'be_free_develiery', 'be_have_discount', 'status', 'store_id', 'promotion_id'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['code', 'title', 'image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'title' => 'Title',
            'image' => 'Image',
            'be_have_gift' => 'Be Have Gift',
            'be_have_limit' => 'Be Have Limit',
            'be_have_stop_buy' => 'Be Have Stop Buy',
            'be_free_develiery' => 'Be Free Develiery',
            'be_have_discount' => 'Be Have Discount',
            'status' => 'Status',
            'store_id' => 'Store ID',
            'promotion_id' => 'Promotion ID',
        ];
    }
}
