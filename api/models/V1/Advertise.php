<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%advertise}}".
 *
 * @property integer $advertise_id
 * @property string $code
 * @property string $name
 * @property string $date_start
 * @property string $date_end
 * @property integer $advertise_position_id
 * @property string $advertise_position_code
 * @property integer $legal_person_id
 * @property string $legal_person_code
 * @property integer $platform_id
 * @property string $platform_code
 * @property integer $priority
 * @property integer $status
 * @property string $date_added
 * @property string $date_modified
 */
class Advertise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%advertise}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['date_start', 'date_end', 'date_added', 'date_modified'], 'safe'],
            [['advertise_position_id', 'legal_person_id', 'platform_id', 'priority', 'status'], 'integer'],
            [['code', 'advertise_position_code', 'legal_person_code', 'platform_code'], 'string', 'max' => 40],
            [['name'], 'string', 'max' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'advertise_id' => 'Advertise ID',
            'code' => 'Code',
            'name' => 'Name',
            'date_start' => '活动开始日期',
            'date_end' => '结束日期',
            'advertise_position_id' => '广告位置ID',
            'advertise_position_code' => 'Advertise Position Code',
            'legal_person_id' => '广告商ID(法人)',
            'legal_person_code' => 'Legal Person Code',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'priority' => '优先级',
            'status' => '有效状态，0=无效，1=生效',
            'date_added' => '创建日期',
            'date_modified' => '修改日期',
        ];
    }
    public function getAdvertiseDetials(){
        return $this->hasMany(AdvertiseDetail::className(), ['advertise_id' => 'advertise_id'])->orderBy("sort_order asc");
    }
    public function getAvailableDetails(){
        return $this->hasMany(AdvertiseDetail::className(), ['advertise_id' => 'advertise_id'])->onCondition(["and", "date_start<'" . date('Y-m-d H:i:s') . "'", "date_end>'" . date('Y-m-d H:i:s') . "'", 'status=1'])->orderBy("sort_order asc");
    }
}
