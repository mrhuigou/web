<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%sale_promotion}}".
 *
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $ctype
 * @property string $sub_type
 * @property string $terminal
 * @property string $start_time
 * @property string $end_time
 * @property string $csource
 * @property string $thumbnail
 * @property string $description
 * @property integer $subject_id
 */
class SalePromotion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%sale_promotion}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['start_time', 'end_time'], 'safe'],
            [['description'], 'string'],
            [['subject_id'], 'integer'],
            [['code', 'name', 'terminal', 'csource', 'thumbnail'], 'string', 'max' => 255],
            [['ctype', 'sub_type'], 'string', 'max' => 30]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'ctype' => 'Ctype',
            'sub_type' => 'Sub Type',
            'terminal' => 'Terminal',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'csource' => 'Csource',
            'thumbnail' => 'Thumbnail',
            'description' => 'Description',
            'subject_id' => 'Subject ID',
        ];
    }
}
