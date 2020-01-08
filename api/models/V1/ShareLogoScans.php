<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%share_logo_scans}}".
 *
 * @property integer $share_logo_scans_id
 * @property integer $weixin_scans_id
 * @property integer $type
 * @property string $parameter
 * @property string $logo_url
 */
class ShareLogoScans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%share_logo_scans}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['weixin_scans_id', 'type'], 'integer'],
            [['title','parameter', 'logo_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'share_logo_scans_id' => 'ID',
            'title' => '标题',
            'weixin_scans_id' => '二维码场景',
            'type' => '类型',
            'parameter' => '参数',
            'logo_url' => 'logo 图片',
        ];
    }
    public function getScan(){
        return $this->hasOne(WeixinScans::className(),['id'=>'weixin_scans_id']);
    }
}
