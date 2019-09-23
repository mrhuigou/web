<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_feeds}}".
 *
 * @property integer $feed_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property integer $customer_id
 * @property integer $events_id
 * @property integer $group_id
 * @property string $action
 * @property string $created_at
 * @property string $updated_at
 */
class ClubFeeds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_feeds}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name_id', 'content_id', 'action'], 'required'],
            [['type_name_id', 'content_id', 'customer_id', 'events_id', 'group_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['action'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'feed_id' => '主键',
            'type_name_id' => '内容类型id。content_type表主键',
            'content_id' => '内容id。体验活动圈子等表的主键',
            'customer_id' => '用户id',
            'events_id' => '活动id',
            'group_id' => '圈子id',
            'action' => '动作。如“发布”“参与”等',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
