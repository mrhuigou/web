<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_tag_content}}".
 *
 * @property integer $tag_id
 * @property integer $type_name_id
 * @property integer $content_id
 */
class ClubTagContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_tag_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'type_name_id', 'content_id'], 'required'],
            [['tag_id', 'type_name_id', 'content_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
        ];
    }
}
