<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%user_group}}".
 *
 * @property integer $user_group_id
 * @property string $name
 * @property string $permission
 */
class UserGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'permission'], 'required'],
            [['permission'], 'string'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_group_id' => 'User Group ID',
            'name' => 'Name',
            'permission' => 'Permission',
        ];
    }
}
