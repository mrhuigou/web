<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $page_id
 * @property string $type
 * @property integer $sort_order
 * @property integer $status
 * @property string $date_added
 * @property string $image
 */
class Page extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sort_order', 'status'], 'integer'],
            [['date_added'], 'safe'],
            [['type','image'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'type' => '类型',
            'sort_order' => '排序',
            'status' => '状态',
            'image' => '页面背景图片',
            'date_added' => '创建时间',
        ];
    }
    public function getDescription(){
        return $this->hasOne(PageDescription::className(),['page_id'=>'page_id']);
    }
}
