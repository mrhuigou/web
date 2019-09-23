<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%product_base_attribute}}".
 *
 * @property integer $product_base_attribute
 * @property integer $product_base_id
 * @property string $product_base_code
 * @property integer $attribute_id
 * @property string $attribute_code
 * @property integer $language_id
 * @property string $text
 */
class ProductBaseAttribute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_base_attribute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_base_id', 'product_base_code', 'attribute_id', 'attribute_code', 'language_id', 'text'], 'required'],
            [['product_base_id', 'attribute_id', 'language_id'], 'integer'],
            [['text'], 'string'],
            [['product_base_code', 'attribute_code'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'product_base_attribute' => 'Product Base Attribute',
            'product_base_id' => 'Product Base ID',
            'product_base_code' => 'Product Base Code',
            'attribute_id' => 'Attribute ID',
            'attribute_code' => 'Attribute Code',
            'language_id' => 'Language ID',
            'text' => 'Text',
        ];
    }
    public function getAttribute_name(){
        return $this->hasOne(AttributeDescription::className(),['attribute_code'=>'attribute_code']);
    }
    public function getAttribute_base(){
        return $this->hasOne(Attribute::className(),['attribute_code'=>'attribute_code']);
    }
    public function getIssearch(){
        $model=$this->attribute_base;
        if($model && $model->is_search){
            return true;
        }else{
            return false;
        }
    }
}
