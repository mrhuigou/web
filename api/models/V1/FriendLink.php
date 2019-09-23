<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%friend_link}}".
 *
 * @property integer $friend_link_id
 * @property string $name
 * @property string $link
 * @property integer $weight
 */
class FriendLink extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%friend_link}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight'], 'integer'],
            [['name', 'link'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'friend_link_id' => 'Friend Link ID',
            'name' => 'Name',
            'link' => 'Link',
            'weight' => 'Weight',
        ];
    }
}
