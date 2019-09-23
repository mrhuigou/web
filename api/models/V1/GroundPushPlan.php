<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%ground_push_plan}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $type
 * @property string $name
 * @property string $begin_date_time
 * @property string $end_date_time
 * @property string $shipping_end_time
 * @property string $contact_name
 * @property string $contact_tel
 * @property integer $status
 * @property integer $create_at
 * @property integer $ground_push_id
 */
class GroundPushPlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ground_push_plan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date_time', 'end_date_time', 'shipping_end_time'], 'safe'],
            [['status', 'create_at','ground_push_point_id'], 'integer'],
            [['code', 'name', 'contact_name', 'contact_tel'], 'string', 'max' => 255],
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
            'begin_date_time' => 'Begin Date Time',
            'end_date_time' => 'End Date Time',
            'shipping_end_time' => 'Shipping End Time',
            'contact_name' => 'Contact Name',
            'contact_tel' => 'Contact Tel',
            'status' => 'Status',
            'create_at' => 'Create At',
	        'ground_push_point_id'=>'Ground Push Point Id'
        ];
    }
    public function getPoint(){
        return $this->hasOne(GroundPushPoint::className(),['id'=>'ground_push_point_id']);
    }
}
