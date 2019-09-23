<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/13
 * Time: 20:34
 */
namespace frontend\modules\club\widgets;
use api\models\V1\ClubUserComment;
use yii\data\Pagination;
use yii\bootstrap\Widget;
class Comment extends Widget{
    public $type;
    public $type_id;
    public $route;
    public function init()
    {
        parent::init();
    }
    public function run()
    {
        $query=ClubUserComment::find()->where(['type'=>$this->type,'type_id'=>$this->type_id]);
        $pages = new Pagination(['totalCount' =>$query->count(),'pageSize' => '3','route'=>$this->route,'params'=>['type'=>$this->type,'type_id'=>$this->type_id]]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('comment',['model'=>$model,'pages' => $pages,'type'=>$this->type,'type_id'=>$this->type_id]);
    }
}