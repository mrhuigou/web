<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%web_log_visit}}".
 *
 * @property string $id
 * @property string $title
 * @property string $url
 * @property string $pf
 * @property string $ua
 * @property string $language
 * @property string $screen
 * @property string $color_depth
 * @property string $source_ip
 * @property integer $time
 * @property string $refer
 * @property integer $time_in
 * @property integer $time_out
 * @property integer $customer_id
 * @property string $user_identity
 */
class WebLogVisit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%web_log_visit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url', 'ua', 'refer'], 'string'],
            [['time', 'time_in', 'time_out', 'customer_id'], 'integer'],
            [['title', 'pf', 'language', 'screen', 'color_depth', 'source_ip', 'user_identity'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'pf' => 'Pf',
            'ua' => 'Ua',
            'language' => 'Language',
            'screen' => 'Screen',
            'color_depth' => 'Color Depth',
            'source_ip' => 'Source Ip',
            'time' => 'Time',
            'refer' => 'Refer',
            'time_in' => 'Time In',
            'time_out' => 'Time Out',
            'customer_id' => 'Customer ID',
            'user_identity' => 'User Identity',
        ];
    }
}
