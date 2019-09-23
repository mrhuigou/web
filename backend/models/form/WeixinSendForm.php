<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/14
 * Time: 17:32
 */
namespace backend\models\form;
use api\models\V1\WeixinPush;
use yii\base\Model;
class WeixinSendForm extends Model {
	public $touser;
	public $msgtype='news';
	public $data;
	public $status=0;
	public $isNewRecord;
	public $model;
	public function __construct($id = 0, $config = [])
	{
		if ($id && $model = WeixinPush::findOne(['id' => $id])) {
			$this->touser=$model->touser;
			$this->msgtype = $model->msgtype;
			$this->data = $model->data;
			$this->status=$model->status;
			$this->isNewRecord=false;
			$this->model=$model;
		}else{
			$this->isNewRecord=true;
		}
		parent::__construct($config);
	}
	public function rules()
	{
		return [
			[['touser','msgtype','data'], 'required'],
			['touser', 'string'],
			['msgtype', 'string'],
			['data', 'string'],
			['status', 'integer'],
		];
	}
	public function save()
	{
		if ($this->validate()) {
			if($this->isNewRecord){
				$model=new WeixinPush();
				$model->create_at=time();
			}else{
				$model=WeixinPush::findOne($this->model->id);
			}
			$model->touser=$this->touser;
			$model->msgtype=$this->msgtype;
			$model->data=$this->data;
			$model->update_at=time();
			$model->status=$this->status;
			$model->save();
			return $model;
		}
		return null;
	}
	public function attributeLabels()
	{
		return [
			'touser' => '对象',
			'msgtype'=>'类型',
			'data'=>'内容',
			'status'=>'状态',
		];
	}
}