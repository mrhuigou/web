<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_shares}}".
 *
 * @property integer $share_id
 * @property integer $type_name_id
 * @property integer $content_id
 * @property integer $customer_id
 * @property string $created_at
 * @property string $updated_at
 */
class ClubShares extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_shares}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name_id', 'content_id', 'customer_id', 'created_at', 'updated_at'], 'required'],
            [['type_name_id', 'content_id', 'customer_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'share_id' => '主键',
            'type_name_id' => '内容类型id。content_type表主键。',
            'content_id' => '内容id。体验活动圈子等表的主键',
            'customer_id' => '用户id',
            'created_at' => '创建时间',
            'updated_at' => '最后更新时间',
        ];
    }
}
