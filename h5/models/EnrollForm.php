<?php
namespace h5\models;
use api\models\V1\Address;
use api\models\V1\CustomerOther;
use common\models\User;
use yii\base\Model;
use Yii;

/**
 * EnrollForm
 * @property integer $customer_id
 * @property string $realname
 * @property string $telephone
 * @property string $company
 * @property integer $quantity
 * @property string $position
 * @property string $address
 * @property string $industry
 * @property string $service
 * @property string $remark
 */
class EnrollForm extends Model {
	public $customer_id;
	public $realname;
	public $telephone;
	public $company;
	public $quantity=1;
	public $position;
	public $address;
	public $industry;
	public $service;
	public $remark;
	public function __construct($config = [])
	{
		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['realname', 'telephone','quantity', 'company', 'position', 'address', 'industry'],'required'],
			[['customer_id','quantity'], 'integer'],
			[['service', 'remark'], 'string'],
			[['realname', 'telephone', 'company', 'position', 'address', 'industry'], 'string'],
			['telephone', 'string', 'length' => 11],
			['quantity','integer','min'=>1,'max'=>3],
			['realname', 'string', 'min' => 1,'max'=>20],
			[['address','company'], 'string', 'min' => 3,'max'=>200],
		];
	}

	/**
	 * AddressForm
	 *
	 * @return address|null the saved model or null if saving fails
	 */
	public function save()
	{
		if ($this->validate()) {
			$model=new CustomerOther();
			$model->customer_id = Yii::$app->user->getId();
			$model->realname = $this->realname;
			$model->telephone = $this->telephone;
			$model->company = $this->company;
			$model->quantity=$this->quantity;
			$model->position = $this->position;
			$model->address = $this->address;
			$model->industry = $this->industry;
			$model->service = $this->service;
			$model->remark = $this->remark;
			$model->data_added = date('Y-m-d H:i:s', time());
			$model->status=0;
			$model->save();
			return $model;
		}
		return null;
	}

	public function attributeLabels()
	{
		return [
			'id' => '编号',
			'customer_id' => '用户ID',
			'realname' => '真实姓名',
			'telephone' => '手机电话',
			'company' => '公司名称',
			'quantity'=>'参会人数',
			'position' => '职位',
			'address' => '公司地址',
			'industry' => '行业',
			'service' => '产品及服务',
			'remark' => '可提供资源',
			'data_added' => '创建时间',
		];
	}
}
