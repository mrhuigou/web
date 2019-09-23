<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%category_to_price}}".
 *
 * @property integer $id
 * @property integer $category_id
 * @property double $start
 * @property double $end
 */
class CategoryToPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category_to_price}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id'], 'integer'],
            [['start', 'end'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_id' => 'Category ID',
            'start' => 'Start',
            'end' => 'End',
        ];
    }
}
