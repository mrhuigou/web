<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_group}}".
 *
 * @property integer $group_id
 * @property integer $group_type_id
 * @property integer $customer_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property string $logo
 * @property integer $permission
 * @property string $tag_id
 * @property integer $is_pop
 * @property string $deleted_at
 * @property integer $status
 * @property string $qr_code
 */
class ClubGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_type_id', 'customer_id', 'permission', 'is_pop', 'status'], 'integer'],
            [['description'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'logo', 'tag_id', 'qr_code'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'group_type_id' => '圈子分类',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'logo' => 'Logo',
            'permission' => 'Permission',
            'tag_id' => 'Tag ID',
            'is_pop' => 'Is Pop',
            'deleted_at' => 'Deleted At',
            'status' => 'Status',
            'qr_code' => 'Qr Code',
        ];
    }
}
