<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 9:46
 */
namespace h5\models;
use api\models\V1\ClubActivity;
use api\models\V1\ClubTag;
use api\models\V1\ClubTry;
use api\models\V1\ClubUserComment;
use api\models\V1\ClubUserCommentTag;
use api\models\V1\ClubVote;
use api\models\V1\ClubVoteItem;
use api\models\V1\Tag;
use yii\base\Model;
use Yii;
class ClubCommentForm extends Model{
    public $type;
    public $type_id;
    public $content;
    public $tag;
    public $images;
    public $address;
    public $taglist=[];

    public function __construct($type,$type_id,$config = [])
    {
        $this->type=$type;
        $this->type_id=$type_id;
        $data=Tag::find()->where(['type'=>$type,'type_id'=>$type_id])->all();
        if($data){
            $taglist=[];
            foreach($data as $v){
                $this->tag[]=$v['id'];
                $taglist[$v['id']]=$v['name'];
            }
            $this->taglist=$taglist;
        }
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_id'], 'integer'],
            ['content','required'],
            [['content', 'images','address'], 'string'],
            ['tag','safe'],
            [['type'], 'string', 'max' => 32]
        ];
    }

    public function save(){
        if($this->validate()){
            $model=new ClubUserComment();
            $model->type=$this->type;
            $model->type_id=$this->type_id;
            $model->customer_id=Yii::$app->user->getId();
            $model->content=$this->content;
            $model->images=$this->images;
            $model->address=$this->address;
            $model->creat_at=date('Y-m-d H:i:s',time());
            $model->status=1;
            $model->like_count=0;
            $model->save();
            if($this->tag){
                foreach($this->tag as $tag){
                    if($tagmodel=Tag::findOne(['id'=>$tag])){
                        $tagmodel->total_count=$tagmodel->total_count+1;
                        $tagmodel->save();
                    }
                    $comment_tag=new ClubUserCommentTag();
                    $comment_tag->comment_id=$model->id;
                    $comment_tag->tag_id=$tag;
                    $comment_tag->save();
                }
            }
            if($this->type=='try'){
                $mod=ClubTry::findOne(['id'=>$this->type_id]);
                if($mod){
                    $mod->comment_count= ($mod->comment_count)+1;
                    $mod->save();
                }
            }
            if($this->type=='activity'){
                $mod=ClubActivity::findOne(['id'=>$this->type_id]);
                if($mod){
                    $mod->comment_count= ($mod->comment_count)+1;
                    $mod->save();
                }
            }
            if($this->type=='vote'){
                $mod=ClubVote::findOne(['id'=>$this->type_id]);
                if($mod){
                    $mod->comment_count= ($mod->comment_count)+1;
                    $mod->save();
                }
            }
            if($this->type=='vote_item'){
                $mod=ClubVoteItem::findOne(['id'=>$this->type_id]);
                if($mod){
                    $mod->comment_count= ($mod->comment_count)+1;
                    $mod->save();
                }
            }
            return $model;
        }else{
            return null;
        }

    }
    public function attributeLabels()
    {
        return [
            'content' => '体验内容',
            'tag'=>'标签'
        ];
    }
}