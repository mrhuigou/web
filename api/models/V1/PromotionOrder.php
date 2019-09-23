<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion_order}}".
 *
 * @property integer $promotion_order_id
 * @property string $promotion_order_code
 * @property string $promotion_order_title
 * @property string $promotion_order_image
 * @property integer $promotion_id
 * @property string $promotion_code
 * @property integer $customer_group_id
 * @property string $member_type_code
 * @property integer $store_id
 * @property string $store_code
 * @property string $begin_date
 * @property string $end_date
 * @property integer $need_hour
 * @property string $date_start
 * @property string $date_end
 * @property string $stairtype
 * @property integer $begin_quantity
 * @property integer $end_quantity
 * @property string $begin_amount
 * @property string $end_amount
 * @property integer $priority
 * @property string $pricetype
 * @property string $price
 * @property string $rebate
 * @property string $uplimit_type
 * @property integer $uplimit_quantity
 * @property integer $limit_quantity
 * @property string $stop_buy_type
 * @property integer $stop_buy_quantity
 * @property integer $behave_gift
 * @property integer $status
 * @property integer $sort_order
 */
class PromotionOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_order}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_order_code', 'promotion_id'], 'required'],
            [['promotion_id', 'customer_group_id','store_id', 'need_hour', 'begin_quantity', 'end_quantity', 'priority', 'uplimit_quantity', 'stop_buy_quantity', 'behave_gift', 'status', 'sort_order'], 'integer'],
            [['date_start', 'date_end'], 'safe'],
            [['begin_amount', 'end_amount', 'price', 'rebate'], 'number'],
            [['promotion_order_code', 'promotion_code', 'store_code','member_type_code'], 'string', 'max' => 255],
            [['promotion_order_title', 'promotion_order_image', 'stairtype'], 'string', 'max' => 255],
            [['pricetype', 'uplimit_type', 'stop_buy_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_order_id' => '明细ID',
            'promotion_order_code' => '明细编码',
            'promotion_order_title' => '标题',
            'promotion_order_image' => '图片',
            'promotion_id' => '方案ID',
            'promotion_code' => '方案编码',
            'customer_group_id' => '会员组ID',
            'member_type_code' => '会员组编码',
            'store_id' => '店铺ID',
            'store_code' => '店铺编码',
            'need_hour' => '小时约束',
            'date_start' => '起始时间',
            'date_end' => '终止时间',
            'stairtype' => '阶梯类型',
            'begin_quantity' => '起始数量',
            'end_quantity' => '终止数量',
            'begin_amount' => '起始金额',
            'end_amount' => '终止金额',
            'priority' => '使用优先级',
            'pricetype' => '取价类型,单价折扣',
            'price' => '单价(折扣金额)',
            'rebate' => 'Rebate',
            'uplimit_type' => '上限类型：指定数量',
            'uplimit_quantity' => '上限数量(购买次数)',
            'stop_buy_type' => '限购类型',
            'stop_buy_quantity' => '限购数量（次数）',
            'behave_gift' => '是否有赠品',
            'status' => '有效状态',
            'sort_order' => '排序/优先级',
        ];
    }
    public function getGifts(){
        return $this->hasMany(PromotionDetailGift::className(),['promotion_detail_id'=>'promotion_order_id'])->where(['jr_promotion_detail_gift.promotion_type'=>'ORDER','jr_promotion_detail_gift.status'=>1]);
    }
    public function getHistory(){
        return $this->hasMany(PromotionHistory::className(),['promotion_detail_id'=>'promotion_order_id'])->where(['jr_promotion_history.promotion_type'=>'ORDER','jr_promotion_history.status'=>1]);
    }
    public function getPromotion(){
        return $this->hasOne(Promotion::className(),['promotion_id'=>'promotion_id']);
    }
    public function getStore(){
        return $this->hasOne(Store::className(),['store_id'=>'store_id']);
    }
}
