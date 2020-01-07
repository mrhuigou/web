<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%information_to_platform}}".
 *
 * @property integer $information_id
 * @property integer $platform_id
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
            [['weixin_scans_id', 'type'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'share_logo_scans_id' => 'Information ID',
            'weixin_scans_id' => 'Platform ID',
            'type' => 'Platform ID',
            'parameter' => 'Platform ID',
            'logo_url' => 'Platform ID',
        ];
    }
}
