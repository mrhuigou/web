<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%review_store}}".
 *
 * @property integer $review_store_id
 * @property integer $customer_id
 * @property string $author
 * @property integer $store_id
 * @property string $store_code
 * @property string $store_name
 * @property integer $taste
 * @property integer $environment
 * @property integer $service
 * @property integer $delivery
 * @property string $avg_consume
 * @property string $comment
 * @property string $envir_type
 * @property string $recommend
 * @property string $date_added
 * @property integer $order_id
 */
class ReviewStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'store_id', 'taste', 'environment', 'service', 'delivery', 'order_id'], 'integer'],
            [['avg_consume'], 'number'],
            [['comment', 'envir_type'], 'string'],
            [['date_added'], 'safe'],
            [['author', 'store_code'], 'string', 'max' => 40],
            [['store_name'], 'string', 'max' => 125],
            [['recommend'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_store_id' => 'Review Store ID',
            'customer_id' => 'Customer ID',
            'author' => '评论匿称',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'store_name' => 'Store Name',
            'taste' => '口味',
            'environment' => '环境',
            'service' => '服务',
            'delivery' => '送餐速度',
            'avg_consume' => '人均消费',
            'comment' => '评论',
            'envir_type' => '序列化值，合适类型，1=商务宴请，2=家庭聚会，3=朋友聚餐，4=情侣约会，5= 随便吃吃',
            'recommend' => '推荐菜品',
            'date_added' => 'Date Added',
            'order_id' => 'Order ID',
        ];
    }
}
