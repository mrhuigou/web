<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%occupation}}".
 *
 * @property integer $occupation_id
 * @property string $name
 * @property integer $weight
 */
class Occupation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%occupation}}';
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
            'occupation_id' => 'Occupation ID',
            'name' => 'Name',
            'weight' => 'Weight',
        ];
    }
}
