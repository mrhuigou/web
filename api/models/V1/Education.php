<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%education}}".
 *
 * @property integer $education_id
 * @property string $name
 * @property integer $weight
 */
class Education extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%education}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weight'], 'integer'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'education_id' => 'Education ID',
            'name' => 'Name',
            'weight' => 'Weight',
        ];
    }
}
