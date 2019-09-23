<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%theme}}".
 *
 * @property integer $theme_id
 * @property string $theme_name
 * @property string $type
 * @property string $theme_code
 * @property string $theme_color_code
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class Theme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%theme}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'theme_code'], 'required'],
            [['status'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['theme_name'], 'string', 'max' => 255],
            [['type','theme_code', 'theme_color_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'theme_id' => 'Theme ID',
            'theme_name' => 'Theme Name',
            'theme_code' => 'theme_code.tpl',
            'type'=>'Type',
            'theme_color_code' => 'theme_color_codebox',
            'status' => 'Status',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
