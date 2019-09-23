<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_life_diary}}".
 *
 * @property integer $life_diary_id
 * @property integer $customer_id
 * @property string $title
 * @property string $intro
 * @property string $start_time
 * @property string $end_time
 * @property string $cover_image
 * @property string $background_image
 * @property integer $display_oder
 * @property integer $total_follower
 * @property integer $total_comment
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $permission
 * @property integer $is_pop
 */
class ClubLifeDiary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_life_diary}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'title', 'start_time', 'end_time', 'created_at'], 'required'],
            [['customer_id', 'display_oder', 'total_follower', 'total_comment', 'permission', 'is_pop'], 'integer'],
            [['intro'], 'string'],
            [['start_time', 'end_time', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['title', 'cover_image', 'background_image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'life_diary_id' => 'Life Diary ID',
            'customer_id' => 'Customer ID',
            'title' => 'Title',
            'intro' => 'Intro',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'cover_image' => 'Cover Image',
            'background_image' => 'Background Image',
            'display_oder' => 'Display Oder',
            'total_follower' => 'Total Follower',
            'total_comment' => 'Total Comment',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'permission' => 'Permission',
            'is_pop' => 'Is Pop',
        ];
    }
}
