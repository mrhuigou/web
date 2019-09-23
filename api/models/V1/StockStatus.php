<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%stock_status}}".
 *
 * @property integer $stock_status_id
 * @property integer $language_id
 * @property string $name
 */
class StockStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%stock_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['language_id', 'name'], 'required'],
            [['language_id'], 'integer'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'stock_status_id' => 'Stock Status ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
