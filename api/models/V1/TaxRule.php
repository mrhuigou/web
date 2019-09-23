<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%tax_rule}}".
 *
 * @property integer $tax_rule_id
 * @property integer $tax_class_id
 * @property integer $tax_rate_id
 * @property string $based
 * @property integer $priority
 */
class TaxRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tax_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tax_class_id', 'tax_rate_id', 'based'], 'required'],
            [['tax_class_id', 'tax_rate_id', 'priority'], 'integer'],
            [['based'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_rule_id' => 'Tax Rule ID',
            'tax_class_id' => 'Tax Class ID',
            'tax_rate_id' => 'Tax Rate ID',
            'based' => 'Based',
            'priority' => 'Priority',
        ];
    }
}
