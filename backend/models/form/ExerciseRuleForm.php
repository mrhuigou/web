<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/20
 * Time: 16:59
 */
namespace backend\models\form;
use api\models\V1\ExerciseRule;
use yii\base\Model;

class ExerciseRuleForm extends Model {
	public $exercise_id;
	public $is_subcription;
	public $order_days;
	public $order_count;
	public $order_total;
	public $product_datas;
	public $start_time;
	public $end_time;
	public $status;
	public $isNewRecord;

	public function __construct($id = 0, $config = [])
	{
		if ($model = ExerciseRule::findOne($id)) {
			$this->exercise_id = $model->exercise_id;
			$this->is_subcription = $model->is_subcription;
			$this->order_count = $model->order_count;
			$this->product_datas = $model->product_datas;
			$this->start_time = $model->start_time;
			$this->end_time = $model->end_time;
			$this->status=$model->status;
		} else {
			$this->isNewRecord = true;
		}
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['exercise_id', 'is_subcription', 'order_days', 'order_count', 'status'], 'integer'],
			[['order_total'], 'number'],
			[['product_datas'], 'string'],
			[['start_time', 'end_time'], 'safe'],
		];
	}
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'exercise_id' => 'Exercise ID',
			'is_subcription' => 'Is Subcription',
			'order_days' => 'Order Days',
			'order_count' => 'Order Count',
			'order_total' => 'Order Total',
			'product_datas' => 'Product Datas',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'status' => 'Status',
		];
	}
	public function save(){
		if ($this->validate()) {
			return true;
		}
		return false;
	}

}