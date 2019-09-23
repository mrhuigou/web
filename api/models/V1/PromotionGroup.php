<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion_group}}".
 *
 * @property integer $promotion_group_id
 * @property string $promotion_group_code
 * @property string $promotion_group_title
 * @property string $promotion_group_image
 * @property integer $promotion_id
 * @property string $promotion_code
 * @property integer $customer_group_id
 * @property string $member_type_code
 * @property integer $product_group_id
 * @property string $product_group_code
 * @property integer $base_quantity
 * @property integer $product_quantity
 * @property integer $product_group_id2
 * @property string $product_group_code2
 * @property integer $base_quantity2
 * @property integer $product_quantity2
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
 * @property string $stop_buy_type
 * @property integer $stop_buy_quantity
 * @property integer $behave_gift
 * @property integer $status
 * @property integer $sort_order
 */
class PromotionGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['promotion_group_code'], 'required'],
            [['promotion_id', 'customer_group_id', 'product_group_id', 'base_quantity', 'product_quantity', 'product_group_id2', 'base_quantity2', 'product_quantity2', 'need_hour', 'begin_quantity', 'end_quantity', 'priority', 'uplimit_quantity', 'stop_buy_quantity', 'behave_gift', 'status', 'sort_order', 'limit_quantity'], 'integer'],
            [['date_start', 'date_end', 'begin_date', 'end_date'], 'safe'],
            [['begin_amount', 'end_amount', 'price', 'rebate'], 'number'],
            [['promotion_group_code', 'promotion_code', 'member_type_code', 'product_group_code', 'product_group_code2'], 'string', 'max' => 32],
            [['promotion_group_title', 'promotion_group_image', 'stairtype'], 'string', 'max' => 255],
            [['pricetype', 'uplimit_type', 'stop_buy_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_group_id' => 'Promotion Group ID',
            'promotion_group_code' => 'Promotion Group Code',
            'promotion_group_title' => 'Promotion Group Title',
            'promotion_group_image' => 'Promotion Group Image',
            'promotion_id' => 'Promotion ID',
            'promotion_code' => 'Promotion Code',
            'customer_group_id' => 'Customer Group ID',
            'member_type_code' => '会员类型码，网站会员',
            'product_group_id' => '商品组ID',
            'product_group_code' => 'Product Group Code',
            'base_quantity' => '商品组数量基础（购买此组数量基数）',
            'product_quantity' => '商品基数（购买商品种类数量基数）',
            'product_group_id2' => '商品组ID2',
            'product_group_code2' => 'Product Group Code2',
            'base_quantity2' => '商品组2数量基础（购买此组数量基数）',
            'product_quantity2' => '商品基数（购买商品种类数量基数）',
            'need_hour' => '小时约束',
            'date_start' => '起始时间',
            'date_end' => '终止时间',
            'stairtype' => 'Stairtype',
            'begin_quantity' => '起始数量',
            'end_quantity' => '终止数量',
            'begin_amount' => '起始金额',
            'end_amount' => '终止金额',
            'priority' => 'Priority',
            'pricetype' => 'Pricetype',
            'price' => 'Price',
            'rebate' => 'Rebate',
            'uplimit_type' => '上限类型：指定数量、实际库存',
            'uplimit_quantity' => '上限数量(购买次数)',
            'stop_buy_type' => '限购类型，无、单张订单、当日、促销期间',
            'stop_buy_quantity' => '限购数量',
            'behave_gift' => '是否有赠品',
            'status' => '有效状态，0=无效，1=生效',
            'sort_order' => '排序/优先级',
        ];
    }
}
