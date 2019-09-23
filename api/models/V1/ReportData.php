<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%report_data}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $date_added
 */
class ReportData extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['date_added'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'date_added' => 'Date Added',
        ];
    }
}
