<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%voucher_theme}}".
 *
 * @property integer $voucher_theme_id
 * @property string $image
 * @property integer $sort_order
 */
class VoucherTheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%voucher_theme}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'required'],
            [['sort_order'], 'integer'],
            [['image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'voucher_theme_id' => 'Voucher Theme ID',
            'image' => 'Image',
            'sort_order' => 'Sort Order',
        ];
    }
}
