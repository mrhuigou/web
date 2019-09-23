<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%store_template}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $store_id
 * @property integer $default_index
 * @property integer $status
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
            [['store_id', 'default_index', 'status'], 'integer'],
            [['code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'store_id' => 'Store ID',
            'default_index' => 'Default Index',
            'status' => 'Status',
        ];
    }
    public function getPage(){
        return $this->hasMany(StoreTemplatePage::className(),['store_template_id'=>'id']);
    }
}
