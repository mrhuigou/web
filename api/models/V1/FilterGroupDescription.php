<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%filter_group_description}}".
 *
 * @property integer $filter_group_id
 * @property integer $language_id
 * @property string $name
 */
class FilterGroupDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter_group_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filter_group_id', 'language_id', 'name'], 'required'],
            [['filter_group_id', 'language_id'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_group_id' => 'Filter Group ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
        ];
    }
}
