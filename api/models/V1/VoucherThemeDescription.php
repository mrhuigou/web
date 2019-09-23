<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%voucher_theme_description}}".
 *
 * @property integer $voucher_theme_id
 * @property integer $language_id
 * @property string $name
 * @property integer $value
 */
class VoucherThemeDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%voucher_theme_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voucher_theme_id', 'language_id', 'name'], 'required'],
            [['voucher_theme_id', 'language_id', 'value'], 'integer'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'voucher_theme_id' => 'Voucher Theme ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'value' => 'Value',
        ];
    }
}
