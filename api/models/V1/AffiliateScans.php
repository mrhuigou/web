<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%affiliate_scans}}".
 *
 * @property integer $id
 * @property integer $affiliate_id
 * @property string $ticket
 * @property integer $expire_seconds
 * @property string $url
 * @property integer $datetime
 */
class AffiliateScans extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%affiliate_scans}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['affiliate_id', 'expire_seconds', 'datetime'], 'integer'],
            [['ticket', 'url'], 'string'],
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
            'ticket' => 'Ticket',
            'expire_seconds' => 'Expire Seconds',
            'url' => 'Url',
            'datetime' => 'Datetime',
        ];
    }
}
