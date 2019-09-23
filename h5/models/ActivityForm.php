<?php
namespace h5\models;

use api\models\V1\ClubActivity;
use api\models\V1\ClubActivityUser;
use yii\web\NotFoundHttpException;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class ActivityForm extends Model
{   public $id;
    public $activity_category_id;
    public $activity_category_title;
    public $title;
    public $description;
    public $image;
    public $signup_end;
    public $begin_datetime;
    public $end_datetime;
    public $alert_time;
    public $address;
    public $lng;
    public $lat;
    public $fee;
    private $_model;
    public function __construct($id = 0,$config = [])
    {
        if($id > 0 ){
            if($model = ClubActivity::findOne(['id'=>$id,'customer_id'=>\Yii::$app->user->getId()])){
                $this->id=$model->id;
                $this->activity_category_id = $model->activity_category_id;
                $this->title = $model->title;
                $this->description = $model->description;
                $this->image = $model->image;
                $this->signup_end = $model->signup_end;
                $this->begin_datetime = $model->begin_datetime;
                $this->end_datetime = $model->end_datetime;
                $this->alert_time = $model->alert_time;
                $this->address = $model->address;
                $this->fee = $model->fee;
                $this->lat = $model->lat;
                $this->lng = $model->lng;
                $this->activity_category_title = $model->activityCategory->title;
            }else{
                throw new NotFoundHttpException("没有找到活动！");
            }
        }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','signup_end','begin_datetime','end_datetime','activity_category_id','description'], 'required'],
            ['description', 'string', 'min' => 15],
            ['description', 'string', 'max' => 1000],
            ['title', 'string', 'max' => 30],
            [['activity_category_id','lng','lat','fee'],'number'],
            [['image','alert_time','address'],'safe'],
            [['signup_end','begin_datetime','end_datetime'], 'date', 'format' => 'yyyy-M-d H:m:s']

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
            $model = new ClubActivity();
            $model->customer_id=Yii::$app->user->getId();
            $model->activity_category_id=$this->activity_category_id;
            $model->title= htmlspecialchars($this->title);
            $model->description=$this->description;
            $model->image=$this->image;
            $model->signup_end = $this->signup_end;
            $model->begin_datetime = $this->begin_datetime;
            $model->end_datetime = $this->end_datetime;
            $model->address= htmlspecialchars($this->address);
            $model->lat= $this->lat;
            $model->lng= $this->lng;
            $model->fee= $this->fee;
            $model->creat_at=date('Y-m-d H:i:s',time());
            $model->update_at=date('Y-m-d H:i:s',time());
            if($this->alert_time){
                $model->alert_time=date('Y-m-d H:i:s',strtotime($this->begin_datetime)-$this->alert_time);
            }
            $model->save();
            $this->id=$model->id;
            return $model;
        }
        return null;
    }

     public function update()
    {
        if ($this->validate()) {
            $model = ClubActivity::findOne(['id'=>$this->id,'customer_id'=>\Yii::$app->user->getId()]);
            $model->activity_category_id=$this->activity_category_id;
            $model->title= htmlspecialchars($this->title);
            $model->description=$this->description;
            $model->image=$this->image;
            $model->signup_end = $this->signup_end;
            $model->begin_datetime = $this->begin_datetime;
            $model->end_datetime = $this->end_datetime;
            $model->address= htmlspecialchars($this->address);
            $model->lat= $this->lat;
            $model->lng= $this->lng;
            $model->fee= $this->fee;
            $model->update_at=date('Y-m-d H:i:s',time());
            if($this->alert_time){
                $model->alert_time=date('Y-m-d H:i:s',strtotime($this->begin_datetime)-$this->alert_time);
            }
            $model->save();
            return $model;
        }else{
            throw new NotFoundHttpException("没有找到活动！");
        }
        return null;
    }
    public function attributeLabels(){
        return ['title'=>'活动名称',
            'activity_category_id'=>'活动类别',
            'description'=>'活动详情描述',
            'signup_end'=>'截止时间',
            'begin_datetime'=>'开始时间',
            'end_datetime'=>'结束时间',
            'address'=>'活动地点',
            'image'=>'活动图片',
            'alert_time'=>'提醒时间',
            'fee'=>'费用',
        ];
    }
}
