<?php

namespace api\models\V1;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "jr_club_activity_category".
 *
 * @property string $activity_category_id
 * @property string $title
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 */
class ClubActivityCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jr_club_activity_category';
    }

    public function behaviors()
    {
      return [
          'timestamp' => [
                'class' => TimestampBehavior::className(),
                // 'createdAtAttribute' => 'create_time',
                // 'updatedAtAttribute' => 'update_time',
                'value' => new Expression('NOW()'),
            ], 
      ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_category_id' => 'Activity Category ID',
            'title' => '标题',
            'description' => '描述',
            'created_at' => '创建时间',
            'updated_at' => '最后更新',
        ];
    }
}
