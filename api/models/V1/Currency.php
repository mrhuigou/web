<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%currency}}".
 *
 * @property integer $currency_id
 * @property string $title
 * @property string $code
 * @property string $symbol_left
 * @property string $symbol_right
 * @property string $decimal_place
 * @property double $value
 * @property integer $status
 * @property string $date_modified
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%currency}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'code', 'symbol_left', 'symbol_right', 'decimal_place', 'value', 'status'], 'required'],
            [['value'], 'number'],
            [['status'], 'integer'],
            [['date_modified'], 'safe'],
            [['title'], 'string', 'max' => 32],
            [['code'], 'string', 'max' => 3],
            [['symbol_left', 'symbol_right'], 'string', 'max' => 12],
            [['decimal_place'], 'string', 'max' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currency_id' => 'Currency ID',
            'title' => 'Title',
            'code' => 'Code',
            'symbol_left' => 'Symbol Left',
            'symbol_right' => 'Symbol Right',
            'decimal_place' => 'Decimal Place',
            'value' => 'Value',
            'status' => 'Status',
            'date_modified' => 'Date Modified',
        ];
    }
}
