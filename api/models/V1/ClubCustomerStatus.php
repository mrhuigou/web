<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_customer_status}}".
 *
 * @property integer $s_id
 * @property integer $customer_id
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class ClubCustomerStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_customer_status}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'content', 'created_at'], 'required'],
            [['customer_id'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['content'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            's_id' => '主键',
            'customer_id' => '用户id',
            'content' => '状态内容',
            'created_at' => '创建时间',
            'updated_at' => '最后更新时间',
            'deleted_at' => '删除时间（软删除）',
        ];
    }
}
