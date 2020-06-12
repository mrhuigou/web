<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%weixin2_scans}}".
 *
 * @property integer $id
 * @property integer $type
 * @property string $title
 * @property string $scene_str
 * @property string $data
 * @property string $ticket
 * @property integer $expire_seconds
 * @property string $url
 * @property integer $datetime
 * @property integer $code
 */
class Weixin2Scans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%weixin2_scans}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['scene_str', 'data', 'ticket', 'url'], 'string'],
            [['type','expire_seconds', 'datetime'], 'integer'],
            [['title','code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
	        'type'=>'类型',
            'title' => '场景名称',
            'scene_str' => '场景标识',
            'data' => '参数',
            'ticket' => '凭证',
            'expire_seconds' => '过期时间',
            'url' => '二维码源码',
            'datetime' => '生成时间',
        ];
    }
    public function getAffiliate(){
    	return $this->hasOne(AffiliateWeixinScans::className(),['weixin_scans_id'=>'id']);
    }
    public function getNews(){
    	return $this->hasMany(WeixinScansNews::className(),['weixin_scans_id'=>'id'])->orderBy('sort_order');
    }

}
