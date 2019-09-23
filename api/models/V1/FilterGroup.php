<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%filter_group}}".
 *
 * @property integer $filter_group_id
 * @property integer $sort_order
 */
class FilterGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filter_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order'], 'required'],
            [['sort_order'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'filter_group_id' => 'Filter Group ID',
            'sort_order' => 'Sort Order',
        ];
    }
}
