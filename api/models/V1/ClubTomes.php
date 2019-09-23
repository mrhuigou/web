<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_tomes}}".
 *
 * @property integer $t_id
 * @property integer $from_customer_id
 * @property integer $to_customer_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property integer $comment_id
 * @property string $action
 * @property string $created_at
 * @property string $updated_at
 * @property integer $is_read
 */
class ClubTomes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_tomes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['from_customer_id', 'to_customer_id', 'type_name_id', 'content_id', 'action', 'created_at', 'is_read'], 'required'],
            [['from_customer_id', 'to_customer_id', 'type_name_id', 'content_id', 'comment_id', 'is_read'], 'integer'],
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
            't_id' => '主键',
            'from_customer_id' => '来自用户id',
            'to_customer_id' => '接收用户id',
            'type_name_id' => '内容类型id。content_type表主键',
            'content_id' => '内容id。体验活动圈子等表的主键',
            'comment_id' => '评论id，评论表主键',
            'action' => '动作。如“评论””提到“”赞“等',
            'created_at' => '发布时间',
            'updated_at' => '最后更新时间',
            'is_read' => '是否已读。0否，1是。',
        ];
    }
}
