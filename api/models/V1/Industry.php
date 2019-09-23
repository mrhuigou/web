<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%industry}}".
 *
 * @property integer $industry_id
 * @property string $industry_code
 * @property string $name
 * @property string $description
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class Industry extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%industry}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['industry_code'], 'required'],
            [['status'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['industry_code'], 'string', 'max' => 32],
            [['name', 'description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'industry_id' => 'Industry ID',
            'industry_code' => 'Industry Code',
            'name' => 'Name',
            'description' => 'Description',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
