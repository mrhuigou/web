<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%tax_class}}".
 *
 * @property integer $tax_class_id
 * @property string $title
 * @property string $description
 * @property string $date_added
 * @property string $date_modified
 */
class TaxClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tax_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['date_added', 'date_modified'], 'safe'],
            [['title'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tax_class_id' => 'Tax Class ID',
            'title' => 'Title',
            'description' => 'Description',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
