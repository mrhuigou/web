<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/14
 * Time: 17:32
 */
namespace backend\models\form;
use api\models\V1\WeixinScans;
use common\component\Wx\WxScans;
use yii\base\Model;
use Yii;
class WeixinScansForm extends Model {
	public $title;
	public $code;
	public $type;
	public $isNewRecord;
	protected $data=null;
	public function __construct($id = 0, $config = [])
	{
		if ($id && $model = WeixinScans::findOne(['id' => $id])) {
			$this->data=$model;
			$this->title = $model->title;
			$this->code = $model->code;
			$this->type = $model->type ? $model->type : 1;
			$this->isNewRecord=false;
		}else{
			$this->isNewRecord=true;
		}
		parent::__construct($config);
	}
	public function rules()
	{
		return [
			['title', 'required'],
            ['type', 'integer'],
            [['title', 'code'], 'string', 'max' => 255],
		];
	}
	public function save()
	{
		if ($this->validate()) {
			if (!$this->data) {
				$scan=new WxScans();
				$scene_str=md5(serialize(time()));
				if($data=$scan->creatScan($scene_str)){
					$model=new WeixinScans();
                    $model->type=$this->type;
                    $model->code=$this->code;
					$model->title=$this->title;
					$model->scene_str=$scene_str;
					$model->data=serialize($scene_str);
					$model->expire_seconds=isset($data['expire_seconds'])?$data['expire_seconds']:0;
					$model->ticket=$data['ticket'];
					$model->url=$data['url'];
					$model->datetime=time();
					$model->save();
				}
			}
			return $model;
		}
		return null;
	}
	public function attributeLabels()
	{
		return ['title' => '场景标题'];
	}
}