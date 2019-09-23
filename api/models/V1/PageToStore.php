<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%page_to_store}}".
 *
 * @property integer $page_id
 * @property integer $store_id
 */
class PageToStore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_to_store}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'store_id'], 'required'],
            [['page_id', 'store_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'store_id' => 'Store ID',
        ];
    }
}
