<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_rule}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $ref_type
 * @property string $ref_sub_type
 * @property integer $be_main
 */
class SaleRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_rule}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['be_main'], 'integer'],
            [['code', 'name', 'ref_type', 'ref_sub_type'], 'string', 'max' => 255]
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
            'ref_type' => 'Ref Type',
            'ref_sub_type' => 'Ref Sub Type',
            'be_main' => 'Be Main',
        ];
    }
}
