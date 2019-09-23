<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%page_description}}".
 *
 * @property integer $page_id
 * @property integer $language_id
 * @property string $title
 * @property string $description
 * @property string $meta_keyword
 * @property string $meta_description
 */
class PageDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id'], 'required'],
            [['page_id'], 'integer'],
            [['description', 'meta_keyword', 'meta_description'], 'string'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'language_id' => 'Language ID',
            'title' => '标题',
            'description' => '内容',
            'meta_keyword' => 'Meta 关键词',
            'meta_description' => 'Meta 描述',
        ];
    }
}
