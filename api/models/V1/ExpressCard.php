<?php

namespace api\models\V1;

use common\models\LegalPerson;
use Yii;

/**
 * This is the model class for table "{{%express_card}}".
 *
 * @property integer $id
 * @property integer $legal_person_id
 * @property string $code
 * @property string $name
 * @property string $remark
 * @property string $begin_datetime
 * @property string $end_datetime
 * @property integer $status
 */
class ExpressCard extends \yii\db\ActiveRecord
{
    public $legal_person_name;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%express_card}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['legal_person_id', 'status'], 'integer'],
            [['remark'], 'string'],
            [['begin_datetime', 'end_datetime'], 'safe'],
            [['code', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'legal_person_id' => '法人ID',
            'legal_person_name' => '法人姓名',
            'code' => '卡CODE',
            'name' => '卡名称',
            'remark' => '标注',
            'begin_datetime' => '开始时间',
            'end_datetime' => '结束时间',
            'status' => '状态',
        ];
    }
    public function getLegalPerson(){
        return $this->hasOne(LegalPerson::className(),['legal_person_id'=>'legal_person_id']);
    }
}
