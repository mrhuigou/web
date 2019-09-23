<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_share_page_log}}".
 *
 * @property string $id
 * @property integer $customer_share_page_id
 * @property integer $customer_id
 * @property string $from
 * @property string $url
 * @property integer $create_at
 */
class CustomerSharePageLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_share_page_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'customer_share_page_id', 'customer_id', 'create_at'], 'integer'],
	        [['url'], 'string'],
	        ['from', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'customer_share_page_id' => 'Customer Share Page ID',
            'customer_id' => 'Customer ID',
	        'from'=>'From',
	        'url'=>'Url',
            'create_at' => 'Create At',
        ];
    }
}
