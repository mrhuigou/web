<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_talent}}".
 *
 * @property integer $talent_id
 * @property integer $customer_id
 * @property string $title
 * @property string $tag_id
 * @property integer $is_pop
 * @property integer $is_del
 * @property integer $examine_id
 * @property integer $sub_id
 * @property string $create_time
 */
class ClubTalent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_talent}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'title'], 'required'],
            [['customer_id', 'is_pop', 'is_del', 'examine_id', 'sub_id'], 'integer'],
            [['create_time'], 'safe'],
            [['title', 'tag_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'talent_id' => 'Talent ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'tag_id' => 'Tag ID',
            'is_pop' => 'Is Pop',
            'is_del' => 'Is Del',
            'examine_id' => 'Examine ID',
            'sub_id' => 'Sub ID',
            'create_time' => 'Create Time',
        ];
    }
}
