<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_rule_attribute}}".
 *
 * @property integer $rule_id
 * @property string $code
 * @property string $name
 * @property string $value
 */
class SaleRuleAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_rule_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rule_id'], 'required'],
            [['rule_id'], 'integer'],
            [['code', 'name', 'value'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'rule_id' => 'Rule_ID',
            'code' => 'Code',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }
}
