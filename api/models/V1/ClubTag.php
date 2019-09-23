<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_tag}}".
 *
 * @property integer $tag_id
 * @property string $tag_name
 * @property integer $type_name_id
 * @property integer $total_count
 * @property integer $display_order
 */
class ClubTag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_name'], 'required'],
            [['type_name_id', 'total_count', 'display_order'], 'integer'],
            [['tag_name'], 'string', 'max' => 255],
            [['tag_name', 'type_name_id'], 'unique', 'targetAttribute' => ['tag_name', 'type_name_id'], 'message' => 'The combination of Tag Name and Type Name ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tag_id' => 'Tag ID',
            'tag_name' => 'Tag Name',
            'type_name_id' => 'Type Name ID',
            'total_count' => 'Total Count',
            'display_order' => 'Display Order',
        ];
    }
}
