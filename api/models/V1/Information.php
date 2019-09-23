<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%information}}".
 *
 * @property integer $information_id
 * @property integer $bottom
 * @property integer $sort_order
 * @property integer $status
 * @property string $show_type
 */
class Information extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%information}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bottom', 'sort_order', 'status'], 'integer'],
            [['show_type'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'information_id' => 'Information ID',
            'bottom' => '底部展示',
            'sort_order' => '排序',
            'status' => '状态',
            'show_type' => '展示方式',
        ];
    }
    public function getDescription(){
        return $this->hasOne(InformationDescription::className(),['information_id'=>'information_id']);
    }
}
