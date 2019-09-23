<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%layout}}".
 *
 * @property integer $layout_id
 * @property string $name
 */
class Layout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'layout_id' => 'Layout ID',
            'name' => 'Name',
        ];
    }
}
