<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%prize_box}}".
 *
 * @property integer $id
 * @property string $type
 * @property integer $coupon_id
 * @property integer $probability
 * @property string $image
 * @property string $date_start
 * @property string $date_end
 * @property integer $status
 */
class PrizeBox extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prize_box}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id', 'probability', 'status'], 'integer'],
            [['date_start', 'date_end'], 'safe'],
            [['image','type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type'=>'类型',
            'coupon_id' => '优惠券ID',
            'probability' => '概率',
            'image' => '图片',
            'date_start' => '活动开始时间',
            'date_end' => '活动结束时间',
            'status' => '状态',
        ];
    }
    public function getCoupon(){
        return $this->hasOne(Coupon::className(),['coupon_id'=>'coupon_id']);
    }
}
