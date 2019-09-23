<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%legal_person}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 *
 * @property Store[] $stores
 */
class LegalPerson extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%legal_person}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['date_added', 'date_modified'], 'safe'],
            [['code'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '编号 JAVA传递',
            'name' => '法人名称',
            'status' => '状态',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStores()
    {
        return $this->hasMany(Store::className(), ['legal_person_id' => 'id']);
    }
}
