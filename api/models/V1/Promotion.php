<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%promotion}}".
 *
 * @property integer $promotion_id
 * @property string $code
 * @property string $name
 * @property string $type
 * @property integer $priority
 * @property integer $sort_order
 * @property integer $status
 * @property string $date_start
 * @property string $date_end
 * @property string $description
 * @property integer $store_id
 * @property string $store_code
 * @property string $source
 * @property integer $platform_id
 * @property string $platform_code
 * @property string $date_added
 * @property string $date_modified
 * @property string $subject
 * @property integer $subject_id
 * @property string $image_url
 */
class Promotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code'], 'required'],
            [['priority', 'sort_order','subject_id', 'status', 'store_id', 'platform_id'], 'integer'],
            [['date_start', 'date_end', 'date_added', 'date_modified', 'image_url'], 'safe'],
            [['description', 'platform_code'], 'string'],
            [['code', 'type', 'store_code',  'subject'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 100],
            [['source'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'promotion_id' => 'Promotion ID',
            'code' => '促销活动编码',
            'name' => '促销活动名称',
            'type' => '促销类型，单品促销、组合促销、总价促销',
            'priority' => '优先级',
            'sort_order' => '排序/优先级',
            'status' => '有效状态，0=无效，1=生效',
            'date_start' => '活动开始日期',
            'date_end' => '结束日期',
            'description' => '描述',
            'store_id' => '法人编码',
            'store_code' => 'Store Code',
            'source' => 'Source',
            'platform_id' => 'Platform ID',
            'platform_code' => 'Platform Code',
            'date_added' => 'Date Added',
            'date_modified' => 'Date Modified',
            'subject' => '主题编码',
            'subject_id'=>'主题ID',
            'image_url' => '图片',
        ];
    }
    //单品促销明细
    public function getDetails()
    {
        return $this->hasMany(PromotionDetail::className(), ['promotion_id' => 'promotion_id'])->where(['and','jr_promotion_detail.begin_date <= NOW()', 'jr_promotion_detail.end_date >= NOW()','status=1'])->orderBy('priority desc,promotion_detail_id asc ');
    }
    //订单促销明细
    public function getOrderDetails(){
        return $this->hasMany(PromotionOrder::className(), ['promotion_id' => 'promotion_id'])->where(['and','jr_promotion_order.begin_date <= NOW()', 'jr_promotion_order.end_date >= NOW()','jr_promotion_order.status=1'])->orderBy('priority desc,promotion_detail_id asc ');
    }
    public function getTopDetails($count=5){
        $model=PromotionDetail::find()->where(['and','promotion_id='.$this->promotion_id,'begin_date <= NOW()', 'end_date >= NOW()','status=1'])->orderBy('priority desc,promotion_detail_id asc')->limit($count)->all();
        return $model;
    }
}
