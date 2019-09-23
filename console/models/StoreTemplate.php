<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "{{%store_template}}".
 *
 * @property integer $id
 * @property integer $store_id
 * @property integer $template_id
 * @property string $creat_datetime
 * @property string $update_datetime
 * @property integer $status
 *
 * @property Template $template
 * @property Store $store
 */
class StoreTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%store_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['store_id', 'template_id', 'status'], 'integer'],
            [['creat_datetime', 'update_datetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '店铺模板ID',
            'store_id' => '店铺表_店铺ID',
            'template_id' => '模板表_模板ID',
            'creat_datetime' => '创建时间',
            'update_datetime' => '更新时间',
            'status' => '状态',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }
}
