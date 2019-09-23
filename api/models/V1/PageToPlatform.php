<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%page_to_platform}}".
 *
 * @property integer $page_id
 * @property integer $platform_id
 */
class PageToPlatform extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_to_platform}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'platform_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'platform_id' => 'Platform ID',
        ];
    }
}
