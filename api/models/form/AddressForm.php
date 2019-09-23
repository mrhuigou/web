<?php
namespace api\models\form;

use api\models\V1\Address;
use api\models\V1\Customer;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;
use common\component\response\Result;

/**
 * Signup form
 */
class AddressForm extends Model
{   public $firstname;
    public $telephone;
    public $district_id;
    public $address_1;
    public $postcode;
    public $is_default;
    public $address_id;
    private $_user;
    public function __construct($config = [])
    {
        // if (empty($token) || !is_string($token)) {
        //     throw new InvalidParamException('Password reset token cannot be blank.');
        // }
        // $this->_user = Address::findOne($token);
        // if (!$this->_user) {
        //     throw new InvalidParamException('Wrong password reset token.');
        // }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['telephone', 'filter', 'filter' => 'trim'],
            [['telephone','firstname','address_1','district_id'], 'required'],
            ['telephone', 'string', 'length' => 11],
            ['firstname', 'string', 'min' => 2],
            ['address_1', 'string', 'min' => 3],
            ['postcode', 'string', 'length' => 6],
            ['district_id', 'number', 'min'=>1],
            [['is_default','address_id'], 'number'],
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
            $user = new Address();
            $user->customer_id=Yii::$app->user->getId();
            $user->firstname=$this->firstname;
            $user->country_id=854;
            $user->zone_id=119;
            $user->city_id=10848;
            $user->district_id=$this->district_id;
            $user->telephone = $this->telephone;
            $user->address_1= $this->address_1;
            $user->postcode=$this->postcode;
            $user->date_added=date('Y-m-d H:i:s',time());
            $user->save();

            if($this->is_default == 1){
                $customer=Customer::findOne(Yii::$app->user->identity->getId());
                $customer->address_id=$user->address_id;
                $customer->save();
            }
            return $user;
    }

    public function update()
    {
        if($user = Address::findOne(['address_id'=>$this->address_id,'customer_id'=>\Yii::$app->user->getId()])){
            $user->firstname=$this->firstname;
            $user->district_id=$this->district_id;
            $user->telephone = $this->telephone;
            $user->address_1= $this->address_1;
            $user->postcode=$this->postcode;
            $user->date_modified=date('Y-m-d H:i:s',time());
            $user->save();

            if($this->is_default == 1){
                $customer=Customer::findOne(Yii::$app->user->identity->getId());
                $customer->address_id=$user->address_id;
                $customer->save();
            }
            return $user;
        }else{
            return Result::Error('参数错误');
        }

    }

}
