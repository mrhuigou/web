<?php
namespace frontend\modules\club\widgets;
use api\models\V1\ClubUserComment;
use yii\data\Pagination;
use yii\bootstrap\Widget;
class ClubComment extends Widget{
    public $type_name_id;
    public $content_id;
    public $route;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $query = \api\models\V1\ClubComment::find()->where(['type_name_id'=>$this->type_name_id,'content_id'=>$this->content_id,'reference_id'=>0])->orderBy('create_time desc');
        $pages = new Pagination(['totalCount' =>$query->count(),'pageSize' => '3','route'=>$this->route,'params'=>['type_name_id'=>$this->type_name_id,'content_id'=>$this->content_id]]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('club-comment',['model'=>$model,'pages' => $pages,'type_name_id'=>$this->type_name_id,'content_id'=>$this->content_id]);
    }
}