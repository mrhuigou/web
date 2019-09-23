<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_weixin_scans}}".
 *
 * @property integer $id
 * @property integer $affiliate_id
 * @property integer $weixin_scans_id
 */
class AffiliateWeixinScans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_weixin_scans}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'weixin_scans_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'affiliate_id' => 'Affiliate ID',
            'weixin_scans_id' => 'Weixin Scans ID',
        ];
    }
    public function getAffiliate(){
    	return $this->hasOne(Affiliate::className(),['affiliate_id','affiliate_id']);
    }
    public function getWeixinScans(){
    	return $this->hasOne(WeixinScans::className(),['id'=>'weixin_scans_id']);
    }
}
