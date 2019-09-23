<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%club_activity_items}}".
 *
 * @property integer $id
 * @property integer $activity_id
 * @property string $name
 * @property integer $quantity
 * @property string $fee
 * @property integer $status
 */
class ClubActivityItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%club_activity_items}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'activity_id', 'quantity', 'status'], 'integer'],
            [['fee'], 'number'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity ID',
            'name' => '收费项目',
            'quantity' => 'Quantity',
            'fee' => 'Fee',
            'status' => 'Status',
        ];
    }
    public function getUser(){
        return $this->hasMany(ClubActivityUser::className(),['activity_id'=>'activity_id','activity_items_id'=>'id'])->andOnCondition(['status'=>1]);
    }
    public function getQty(){
        $total=0;
        $model=ClubActivityUser::find()->where(['activity_id'=>$this->activity_id,'activity_items_id'=>$this->id,'status'=>1])->sum('quantity');
        if($model){
            $total=$model;
        }
        return $total;
    }
    public function getActivity(){
        return $this->hasOne(ClubActivity::className(),['id'=>'activity_id']);
    }
}
