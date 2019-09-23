<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_permission}}".
 *
 * @property string $permission_id
 * @property integer $allow_type_name_id
 * @property integer $allow_content_id
 * @property integer $type_name_id
 * @property integer $content_id
 */
class ClubPermission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_permission}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['allow_type_name_id', 'allow_content_id', 'type_name_id', 'content_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'permission_id' => 'Permission ID',
            'allow_type_name_id' => 'Allow Type Name ID',
            'allow_content_id' => 'Allow Content ID',
            'type_name_id' => 'Type Name ID',
            'content_id' => 'Content ID',
        ];
    }
}
