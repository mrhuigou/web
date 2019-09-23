<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_album_exp}}".
 *
 * @property integer $club_album_exp_id
 * @property integer $album_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property string $add_time
 * @property integer $is_own
 */
class ClubAlbumExp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_album_exp}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['album_id', 'type_name_id', 'content_id', 'add_time'], 'required'],
            [['album_id', 'type_name_id', 'content_id', 'is_own'], 'integer'],
            [['add_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'club_album_exp_id' => 'Club Album Exp ID',
            'album_id' => 'Album ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
            'add_time' => 'Add Time',
            'is_own' => 'Is Own',
        ];
    }
}
