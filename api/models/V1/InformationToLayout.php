<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%information_to_layout}}".
 *
 * @property integer $information_id
 * @property integer $platform_id
 * @property integer $layout_id
 */
class InformationToLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%information_to_layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['information_id', 'platform_id', 'layout_id'], 'required'],
            [['information_id', 'platform_id', 'layout_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'information_id' => 'Information ID',
            'platform_id' => 'Platform ID',
            'layout_id' => 'Layout ID',
        ];
    }
}
