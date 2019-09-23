<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_experience_addon}}".
 *
 * @property integer $experience_addon_id
 * @property integer $exp_id
 * @property string $content
 * @property string $image_array_log
 * @property string $update_time
 */
class ClubExperienceAddon extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_experience_addon}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exp_id', 'update_time'], 'required'],
            [['exp_id'], 'integer'],
            [['content', 'image_array_log'], 'string'],
            [['update_time'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'experience_addon_id' => 'Experience Addon ID',
            'exp_id' => 'Exp ID',
            'content' => 'Content',
            'image_array_log' => 'Image Array Log',
            'update_time' => 'Update Time',
        ];
    }
}
