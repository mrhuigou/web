<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%layout_route}}".
 *
 * @property integer $layout_route_id
 * @property integer $layout_id
 * @property integer $platform_id
 * @property string $route
 */
class LayoutRoute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%layout_route}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['layout_id', 'platform_id', 'route'], 'required'],
            [['layout_id', 'platform_id'], 'integer'],
            [['route'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'layout_route_id' => 'Layout Route ID',
            'layout_id' => 'Layout ID',
            'platform_id' => 'Platform ID',
            'route' => 'Route',
        ];
    }
}
