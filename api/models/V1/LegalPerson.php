<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%legal_person}}".
 *
 * @property integer $legal_person_id
 * @property string $legal_no
 * @property string $name
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
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
            [['legal_no'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'legal_person_id' => 'Legal Person ID',
            'legal_no' => '编号 JAVA传递',
            'name' => '法人名称',
            'status' => '有效状态，1=有效，0=无效',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
        ];
    }
}
