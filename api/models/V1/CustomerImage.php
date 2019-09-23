<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_image}}".
 *
 * @property string $customer_image_id
 * @property integer $customer_id
 * @property string $image
 * @property string $code
 * @property string $date_added
 */
class CustomerImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_image}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id'], 'required'],
            [['customer_id'], 'integer'],
            [['date_added'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_image_id' => 'Customer Image ID',
            'customer_id' => 'Customer ID',
            'image' => 'Image',
            'code' => 'idcard_front代表身份证正面，idcard_other身份证反面，face头像',
            'date_added' => 'Date Added',
        ];
    }
}
