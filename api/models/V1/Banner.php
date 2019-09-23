<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%banner}}".
 *
 * @property integer $banner_id
 * @property string $name
 * @property integer $status
 * @property integer $delstatus
 * @property string $code
 */
class Banner extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%banner}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'status', 'delstatus'], 'required'],
            [['status', 'delstatus'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['code'], 'string', 'max' => 125]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'banner_id' => 'Banner ID',
            'name' => 'Name',
            'status' => 'Status',
            'delstatus' => 'Delstatus',
            'code' => '编码 KEY',
        ];
    }
}
