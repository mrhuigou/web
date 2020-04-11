<?php
namespace fx\models;
use api\models\V1\AffiliateTransaction;
use api\models\V1\AffiliateTransactionDraw;
use api\models\V1\AffiliateTransactionFlow;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerCommissionDraw;
use api\models\V1\CustomerCommissionFlow;
use api\models\V1\VerifyCode;
use common\component\Helper\OrderSn;
use common\models\User;
use yii\base\Model;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * RealNameForm
 */
class AffiliateTransactionForm extends Model
{
	public $amount;
	public $open_id;
	public $verifyCode;
	public function __construct($open_id = 0,$config = [])
	{
		if(!$this->open_id=$open_id){
			throw new NotFoundHttpException("非法操作！");
		}
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['amount', 'filter', 'filter' => 'trim'],
			['amount', 'required'],
			['amount','integer','min'=>1],
			['amount', 'validate_amount'],
			['verifyCode', 'required'],
			['verifyCode', 'string', 'min' => 4],
			['verifyCode', 'captcha'],
		];
	}
	public function checkcode($attribute, $params){
		if($model=VerifyCode::findOne(['phone'=>Yii::$app->user->identity->telephone,'code'=>$this->verifyCode,'status'=>0])){
			$model->status=1;
			$model->update();
		}else{
			$this->addError($attribute,'验证码不正确！');
		}
	}
	public function validate_amount($attribute, $params){
		if($this->amount>Yii::$app->user->identity->getAfCommission()){
			$this->addError($attribute,'金额不能大于收益总额！');
		}
	}
	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function save()
	{
		if ($this->validate()) {
			$transaction = \Yii::$app->db->beginTransaction();
			try {
				if($model=AffiliateTransaction::findOne(['affiliate_id'=>Yii::$app->user->identity->getAffiliateId()])){
					$amount=$model->amount-$this->amount;
					$model->amount=$amount;
					if (!$model->save()) {
						throw new \Exception(json_encode($model->errors));
					}
					$draw_model=new AffiliateTransactionDraw();
					$draw_model->code=OrderSn::generateNumber();
					$draw_model->affiliate_id=Yii::$app->user->identity->getAffiliateId();
					$draw_model->open_id=$this->open_id;
					$draw_model->amount=$this->amount;
					$draw_model->status=0;
					$draw_model->created_at=time();
					if (!$draw_model->save()) {
						throw new \Exception(json_encode($draw_model->errors));
					}
					$flow_model=new AffiliateTransactionFlow();
					$flow_model->type="draw";
					$flow_model->type_id=$draw_model->id;
					$flow_model->affiliate_id=Yii::$app->user->identity->getAffiliateId();
					$flow_model->title='提现';
					$flow_model->amount=-$this->amount;
					$flow_model->balance=$amount;
					$flow_model->status=0;
					$flow_model->remark="微信提现";
					$flow_model->create_at=time();
					if (!$flow_model->save()) {
						throw new \Exception(json_encode($flow_model->errors));
					}
				}
				$transaction->commit();
			} catch (\Exception $e) {
				$transaction->rollBack();
				throw new NotFoundHttpException($e->getMessage());
			}
			return $draw_model;
		}
		return false;
	}
	public function attributeLabels(){
		return ['amount'=>'金额',
			'verifyCode'=>'验证码'
		];
	}
}
