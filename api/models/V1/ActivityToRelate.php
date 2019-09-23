<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%activity_to_relate}}".
 *
 * @property integer $activity_relate_to_id
 * @property integer $activity_theme_id
 * @property integer $product_id
 * @property string $code
 * @property string $pucode
 * @property integer $sort_order
 * @property string $date_added
 */
class ActivityToRelate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity_to_relate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['activity_theme_id', 'product_id', 'sort_order'], 'integer'],
            [['date_added'], 'safe'],
            [['code', 'pucode'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'activity_relate_to_id' => 'Activity Relate To ID',
            'activity_theme_id' => 'Activity Theme ID',
            'product_id' => 'Product ID',
            'code' => 'Code',
            'pucode' => 'Pucode',
            'sort_order' => 'Sort Order',
            'date_added' => 'Date Added',
        ];
    }
}
