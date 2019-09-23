<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%news_log}}".
 *
 * @property integer $id
 * @property integer $customer_id
 * @property integer $new_id
 * @property integer $creat_at
 */
class NewsLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%news_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'new_id', 'creat_at'], 'integer'],
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
            'new_id' => 'New ID',
            'creat_at' => 'Creat At',
        ];
    }
}
