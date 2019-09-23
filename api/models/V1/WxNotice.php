<?php

namespace api\models\V1;

use Yii;

/**
 * This is the model class for table "{{%wx_notice}}".
 *
 * @property integer $id
 * @property integer $wx_notice_template_id
 * @property string $content
 * @property string $link_url
 * @property integer $push_date_type
 * @property string $push_begin_time
 * @property string $push_end_time
 * @property integer $push_status
 * @property integer $status
 */
class WxNotice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wx_notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['wx_notice_template_id', 'push_date_type', 'push_status', 'status'], 'integer'],
            [['content'], 'string'],
            [['push_begin_time', 'push_end_time'], 'safe'],
            [['link_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'wx_notice_template_id' => '消息模板',
            'content' => '内容串',
            'link_url' => '链接',
            'push_date_type' => '推荐类型',
            'push_begin_time' => '开始时间',
            'push_end_time' => '结束时间',
            'push_status' => '推送状态',
            'status' => '状态',
        ];
    }
    public function save($runValidation = true, $attributeNames = null)
    {
	    if(!$this->push_date_type){
		    $this->push_begin_time=date('Y-m-d H:i:s',time());
	    }
	    return parent::save($runValidation, $attributeNames); // TODO: Change the autogenerated stub
    }
    public function getTemplate(){
	    return $this->hasOne(WxNoticeTemplate::className(),['id'=>'wx_notice_template_id']);
    }
}
