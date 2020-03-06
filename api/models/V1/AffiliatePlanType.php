<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_plan_type}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $status
 * @property integer $creat_at
 * @property integer $update_at
 */
class AffiliatePlanType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_plan_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'creat_at', 'update_at'], 'integer'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'status' => 'Status',
            'creat_at' => 'Creat At',
            'update_at' => 'Update At',
        ];
    }
}
