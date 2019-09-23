<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_share_page}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property string $code
 * @property string $title
 * @property string $description
 * @property string $url
 * @property integer $count
 * @property integer $created_at
 * @property integer $update_at
 */
class CustomerSharePage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_share_page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'count', 'created_at', 'update_at'], 'integer'],
            [['url'], 'string'],
            [['code', 'title', 'description'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_id' => 'Customer ID',
            'code' => 'Code',
            'title' => 'Title',
            'description' => 'Description',
            'url' => 'Url',
            'count' => 'Count',
            'created_at' => 'Created At',
            'update_at' => 'Update At',
        ];
    }
}
