<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%theme_column}}".
 *
 * @property string $theme_column_id
 * @property string $theme_column_code
 * @property integer $theme_id
 * @property string $name
 * @property string $type
 * @property integer $rowslimit
 * @property integer $status
 * @property string $remark
 */
class ThemeColumn extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%theme_column}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['theme_id', 'rowslimit', 'status'], 'integer'],
            [['theme_column_code', 'type'], 'string', 'max' => 32],
            [['name', 'remark'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'theme_column_id' => 'Theme Column ID',
            'theme_column_code' => 'Theme Column Code',
            'theme_id' => 'Theme ID',
            'name' => 'Name',
            'type' => 'Type',
            'rowslimit' => 'Rowslimit',
            'status' => 'Status',
            'remark' => 'Remark',
        ];
    }
}
