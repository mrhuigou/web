<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%securityquestion}}".
 *
 * @property integer $securityquestion_id
 * @property integer $belong
 * @property string $name
 * @property integer $status
 * @property string $date_added
 * @property integer $sort_order
 */
class Securityquestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%securityquestion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['belong', 'status', 'sort_order'], 'integer'],
            [['name'], 'string'],
            [['date_added'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'securityquestion_id' => 'Securityquestion ID',
            'belong' => '归属，0未系统自定义问题；带customer_id表示该用户的自定义问题',
            'name' => 'Name',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'sort_order' => 'Sort Order',
        ];
    }
}
