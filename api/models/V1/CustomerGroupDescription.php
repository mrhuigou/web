<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%customer_group_description}}".
 *
 * @property integer $customer_group_id
 * @property integer $language_id
 * @property string $name
 * @property string $description
 */
class CustomerGroupDescription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%customer_group_description}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_group_id', 'language_id', 'name', 'description'], 'required'],
            [['customer_group_id', 'language_id'], 'integer'],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 32]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'customer_group_id' => 'Customer Group ID',
            'language_id' => 'Language ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }
}
