<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%page_to_layout}}".
 *
 * @property integer $page_id
 * @property integer $platform_id
 * @property integer $layout_id
 */
class PageToLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_to_layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'platform_id', 'layout_id'], 'required'],
            [['page_id', 'platform_id', 'layout_id'], 'integer']
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
            'layout_id' => 'Layout ID',
        ];
    }
}
