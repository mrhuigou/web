<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%filter_description}}".
 *
 * @property integer $filter_id
 * @property integer $language_id
 * @property integer $filter_group_id
 * @property string $name
 */
class FilterDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filter_id', 'language_id', 'filter_group_id', 'name'], 'required'],
            [['filter_id', 'language_id', 'filter_group_id'], 'integer'],
            [['name'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_id' => 'Filter ID',
            'language_id' => 'Language ID',
            'filter_group_id' => 'Filter Group ID',
            'name' => 'Name',
        ];
    }
}
