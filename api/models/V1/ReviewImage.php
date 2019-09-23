<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%review_image}}".
 *
 * @property integer $review_image_id
 * @property integer $customer_id
 * @property integer $from_id
 * @property string $from_table
 * @property string $image
 * @property string $type_id
 * @property string $date_added
 */
class ReviewImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%review_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'from_id'], 'integer'],
            [['date_added'], 'safe'],
            [['from_table'], 'string', 'max' => 40],
            [['image'], 'string', 'max' => 225],
            [['type_id'], 'string', 'max' => 2]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'review_image_id' => 'Review Image ID',
            'customer_id' => 'Customer ID',
            'from_id' => 'From ID',
            'from_table' => 'From Table',
            'image' => 'Image',
            'type_id' => '类型，1=店铺评论表，2=商品评论表',
            'date_added' => 'Date Added',
        ];
    }
}
