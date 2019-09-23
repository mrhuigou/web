<?php

namespace backend\controllers;

use api\models\V1\Category;
use api\models\V1\CategoryDisplay;
use api\models\V1\CategoryDisplayDescription;
use backend\models\DisplaycateForm;
use yii\helpers\Json;
use Yii;
use yii\helpers\Url;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;
class DisplaycategoryController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@backend/web/upload/images'
            ],
        ];
    }
    public function actionIndex()
    {

        $model=CategoryDisplay::find()->select(['category_display_id','parent_id','sort_order','recommend','status'])->where(['is_delete'=>0])->orderBy('sort_order asc')->all();
        foreach($model as $value){
//            if($value->is_delete == 0){
                if($value->status == 0){
                    $content = "<s class='text-danger'>".$value->description->name."</s>";
                }else{
                    $content = $value->description->name;
                }
                $content .= "<a  class=\"fa fa-edit\" href='".Url::to(['/displaycategory/update','id'=>$value->category_display_id])."'></a>";
                if($value->categorys){
                    $content.="<b class='text-danger'>系统映射</b>";
                }
                if($value->recommend == 1){
                    $content.="<b class='text-danger' style='margin-left: 20px;'>首页推荐 </b>";
                }else{
                    //$content.="<b class='text-danger' style='margin-left: 20px;'>首页推荐 </b>";
                }
                if($value->status == 0){ //禁用的需要还原
                    $content .=" <b class='reset' style='float: right'>还原</b>";
                }

                $content.="<i class=\"fa fa-trash-o \" ></i>";
//            }

            $tree[]=[
                'id'=>$value->category_display_id,
                'pid'=>$value->parent_id,
                'sort'=>$value->sort_order,
                'content'=>$content,
            ];
        }

        $data=\common\component\Helper\Helper::genTree($tree);
        return $this->render('index',['data'=>$data]);
    }

    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate(){

        $model = new DisplaycateForm();
        if ($model->load(\Yii::$app->request->post())&& $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }
    public function actionUpdate($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    protected function findModel($id)
    {
        if (($model = new DisplaycateForm(['id'=>$id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionDel(){
        $model=CategoryDisplay::findOne(['category_display_id'=>\Yii::$app->request->get('cate_id')]);
        if($model){
            $model->status=0;
            $model->is_delete=1;
            $model->save();
            $parent_id=$model->parent_id;
            CategoryDisplay::updateAll(['parent_id'=>$parent_id],['parent_id'=>$model->category_display_id]);
            return $this->redirect('index');
        }
    }
    public function actionReset(){
        $model=CategoryDisplay::findOne(['category_display_id'=>\Yii::$app->request->get('cate_id')]);
        if($model){
            $model->status=1;
            $model->save();
            return $this->redirect('index');
        }
    }
    public function actionSave(){
        $data=Json::decode(\Yii::$app->request->post('cate_output'));
        $recommond = \Yii::$app->request->post('recommend');
        $recommond = trim($recommond,',');
        $recommond_arr = explode(',',$recommond);
        $this->SubmitData($data,0,$recommond_arr);
        return $this->redirect('index');

    }

    public function SubmitData($value,$pid=0,$recommond_arr = array()){
        foreach($value as $key=>$data){
            $model=CategoryDisplay::findOne(['category_display_id'=>$data['id']]);
            if($model) {
                if(in_array($model->category_display_id,$recommond_arr)){
                    $model->recommend = 1;
                }else{
                    $model->recommend = 0;
                }
                $model->parent_id = $pid;
                $model->sort_order = $key;
                $model->save();
                if(isset($data['children']) && $data['children']){
                    $this->SubmitData($data['children'],$data['id'],$recommond_arr);
                }
            }

        }
    }
    public function actionAutoComplete(){
        $data=[];
        if($query=Yii::$app->request->get('term')){
            if($Cate_data=CategoryDisplayDescription::find()->where("name like '%".trim($query)."%'")->orderBy('category_display_id asc')->all()){
                foreach($Cate_data as $value){
                    if($model=CategoryDisplay::findOne(['category_display_id'=>$value->category_display_id])){
                        $data[]=[
                            'value'=>$value->category_display_id,
                            'label'=>$this->getParentNames($model->parent_id,$value->name),
                        ];
                    }
                }
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    public function getParentNames($parent_id,$name=""){
        if($parent=CategoryDisplay::findOne(['category_display_id'=>$parent_id])){
            $name=$parent->description->name."-".$name;
            return $this->getParentNames($parent->parent_id,$name);
        }
        return $name;
    }
}
