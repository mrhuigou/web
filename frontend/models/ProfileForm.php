<?php
namespace frontend\models;

use api\models\V1\Customer;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class ProfileForm extends Model
{   public $nickname;
    public $signature;
    public $telephone;
    public $email;
    public $gender;
    public $birth_year;
    public $birth_month;
    public $birth_day;
    public $district_id;
    public $education;
    public $occupation;
    public $_user;

    public function __construct($config = [])
    {
        $this->_user=Customer::findOne(['customer_id'=>Yii::$app->user->identity->getId()]);

        if(!$this->_user){
           throw new InvalidParamException('no found user.');
        }else{
            $this->nickname = $this->_user->nickname;
            $this->signature = $this->_user->signature;
            $this->telephone = $this->_user->telephone;
            $this->email = $this->_user->email;
            $this->gender = $this->_user->gender;
            $birth = $this->_user->birthday?explode('-',$this->_user->birthday):explode('-','0000-00-00');
            $this->birth_year = $birth[0];
            $this->birth_month = $birth[1];
            $this->birth_day = $birth[2];
            $this->district_id = $this->_user->district_id;
            $this->education = $this->_user->education;
            $this->occupation = $this->_user->occupation;

        }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['nickname', 'filter', 'filter' => 'trim'],
            ['signature', 'filter', 'filter' => 'trim'],
            [['nickname','gender','district_id'], 'required'],
            [['birth_year','birth_month','birth_day','district_id'], 'number'],
            ['nickname', 'string', 'min' => 2],
            ['nickname', 'string', 'max' => 10],
            ['signature', 'string', 'max' => 50],
            [['education','occupation'],'string'],
        ];
    }
    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function save()
    {
        if ($this->validate()) {
            $user =$this->_user;
            if($user){
                $user->nickname=htmlspecialchars($this->nickname);
                $user->signature=htmlspecialchars($this->signature);
                $user->gender=$this->gender;
                $user->birthday=$this->birth_year.'-'.$this->birth_month.'-'.$this->birth_day;
                $user->district_id=$this->district_id;
                $user->education=htmlspecialchars($this->education);
                $user->occupation=htmlspecialchars($this->occupation);
                $user->save();
                return $user;
            }
        }
        return null;
    }
    public function attributeLabels(){
        return [
            'gender'=>'性别',
            'nickname'=>'昵称',
            'signature'=>'个性签名'
        ];
    }
}
