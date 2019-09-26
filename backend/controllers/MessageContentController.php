<?php

namespace backend\controllers;

use api\models\V1\Customer;
use api\models\V1\Message;
use api\models\V1\MessageContent;
use api\models\V1\MessageContentSearch;
use backend\models\MessageContentForm;
use Yii;
use api\models\V1\Page;
use api\models\V1\PageSearch;
use api\models\V1\PageDescription;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;

/**
 * PageController implements the CRUD actions for Page model.
 */
class MessageContentController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'Kupload' => [
                'class' => 'common\extensions\widgets\kindeditor\KindEditorAction',
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@backend/web/upload/images'
            ],
        ];
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MessageContentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     */
    // public function actionView($id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MessageContentForm();

        $model->date_added = date('Y-m-d H:i:s',time());
        if ($model->load(Yii::$app->request->post())) {
//            if($model->type == 'PACK'){
//                $model->item_id;
//                if(str_replace('，',',',$model->item_id)){
//                    $model->item_id = str_replace('，',',',$model->item_id);
//                }
//                if(str_replace(' ',',',$model->item_id)){
//                    $model->item_id = str_replace(' ',',',$model->item_id);
//                }
//
//                $model->item_id = trim($model->item_id);
//                $model->item_id = trim($model->item_id,',');
//            }
            $model->date_added = date('Y-m-d H:i:s');
            $model->save();

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //$model = $this->findModel($id);
        $message_content_form = new MessageContentForm(array('id'=>$id));
        if ($message_content_form->load(Yii::$app->request->post()) && $message_content_form->save() ) {
            return $this->redirect(['index']);
        } else {
            $message_content_form = new MessageContentForm(array('id'=>$id));
            return $this->render('update', [
                'model' => $message_content_form,

            ]);
        }
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

//    public function actionPush($page_id)
//    {
//        $model = $this->findModel($page_id);
//        if($model->is_push == 1){
//            return '已推送过';
//        }else{
//            $data = array(
//                'range_type' => 1,
//                'title' => $model->description->title,
//                'content'=>  $model->description->meta_description,
//                'type' => $model['push_type'],
//                'value'=> $model['push_value'],
//                'image' => $model['image'],
//                'time'=> date("Y-m-d H:i:s"),
//            );
//            $url = "http://api.mrhuigou.com/index.php?a=push&c=push";
//            curl_file_get_contents($url,http_build_query($data));
//            $model->is_push = 1;
//            $model->send_date = date("Y-m-d H:i:s");
//            $model->save();
//            return '推送成功';
//        }
//    }

    protected function findModel($id)
    {
        if (($model = MessageContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
