<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_plan}}".
 *
 * @property integer $affiliate_plan_id
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $date_start
 * @property string $date_end
 * @property string $ship_end
 * @property string $minbookcash
 * @property string $deliverycash
 * @property integer $store_id
 * @property string $store_code
 * @property integer $status
 */
class AffiliatePlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_plan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['date_start', 'date_end', 'ship_end'], 'safe'],
            [['store_id', 'status'], 'integer'],
            [['code', 'store_code'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
            [['description', 'type','position','source_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'affiliate_plan_id' => 'Affiliate Plan ID',
            'code' => 'Code',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'ship_end' => 'Ship End',
            'store_id' => 'Store ID',
            'store_code' => 'Store Code',
            'status' => 'Status',
        ];
    }

    public function getPlanType(){
        return $this->hasOne(AffiliatePlanType::className(),['code'=>'type']);
    }

    public function getAffiliatePlanDetailByPositionCode($code = '')
    {
        $plan=AffiliatePlan::find()->where(['and','date_start<=NOW()','date_end>=NOW()','status=1'])->andWhere(['position'=>$code])->all();
         return $plan;
    }

}
