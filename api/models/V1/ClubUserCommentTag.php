<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_user_comment_tag}}".
 *
 * @property integer $comment_id
 * @property integer $tag_id
 */
class ClubUserCommentTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_user_comment_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['comment_id', 'tag_id'], 'required'],
            [['comment_id', 'tag_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'Comment ID',
            'tag_id' => 'Tag ID',
        ];
    }
    public function getTag(){
        return $this->hasOne(Tag::className(),['id'=>'tag_id']);
    }
}
