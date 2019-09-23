<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%information_to_platform}}".
 *
 * @property integer $information_id
 * @property integer $platform_id
 */
class InformationToPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%information_to_platform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['information_id', 'platform_id'], 'required'],
            [['information_id', 'platform_id'], 'integer']
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
        ];
    }
}
