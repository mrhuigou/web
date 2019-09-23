<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%filter}}".
 *
 * @property integer $filter_id
 * @property integer $filter_group_id
 * @property integer $sort_order
 */
class Filter extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filter_group_id', 'sort_order'], 'required'],
            [['filter_group_id', 'sort_order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_id' => 'Filter ID',
            'filter_group_id' => 'Filter Group ID',
            'sort_order' => 'Sort Order',
        ];
    }
}
