<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_description}}".
 *
 * @property integer $store_id
 * @property integer $language_id
 * @property string $title
 * @property string $meta_description
 * @property string $meta_keyword
 * @property string $description
 */
class StoreDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'language_id'], 'required'],
            [['store_id', 'language_id'], 'integer'],
            [['meta_description', 'description'], 'string'],
            [['title', 'meta_keyword'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'store_id' => 'Store ID',
            'language_id' => 'Language ID',
            'title' => 'Title',
            'meta_description' => 'Meta Description',
            'meta_keyword' => 'Meta Keyword',
            'description' => 'Description',
        ];
    }
}
