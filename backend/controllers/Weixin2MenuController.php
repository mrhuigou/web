<?php

namespace backend\controllers;

use api\models\V1\Weixin2Menu;
use common\component\Curl\Curl;
use common\component\Wx\WxSdk;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Weixin2MenuController implements the CRUD actions for Weixin2Menu model.
 */
class Weixin2MenuController extends Controller
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

    /**
     * Lists all Weixin2Menu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Weixin2Menu::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Weixin2Menu model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Weixin2Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Weixin2Menu();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Weixin2Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Weixin2Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionPublish(){
        $model=Weixin2Menu::find()->where(['status'=>1])->orderBy('sort asc')->asArray()->all();
        if($model) {

            foreach($model as $key=>$value){
                if($value['url']){
                    $model[$key]['url']=$value['url'];
                }
            }
            if(count($model) ==1){
                $button[] = $model[0];
            }else{
                $button = \common\component\Helper\Helper::genTree($model, 'id', 'pid', 'sub_button');
            }
            $result = Yii::$app->wechat2->createMenu($button);
	        if(isset($result['errmsg']) && $result['errmsg'] == 'ok'){
                Yii::$app->getSession()->setFlash('success',"<b>发布成功:</b> 您可以取消关注查看效果！");
            }else{
	            Yii::error("createmenu_error==========================>".json_encode($result));
                Yii::$app->getSession()->setFlash('error', "<b>发布错误:".json_encode($result)."</b>");
            }
            return $this->redirect(['index']);
        }
    }
    public function WeixinUrl($link){
      //  $appid=Yii::$app->params['weixin']['appid'];
      //  $base_url="https://open.weixin.qq.com/connect/oauth2/authorize?";
      //  $base_url.="appid=".$appid."&redirect_uri=".urlencode("http://m.mrhuigou.com/site/weixin?redirect=".$link)."&response_type=code&scope=snsapi_base&state=weixin#wechat_redirect";
	  $base_url="https://m.mrhuigou.com/site/login?redirect=".urlencode($link);
        return $base_url;
    }
    /**
     * Finds the Weixin2Menu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Weixin2Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Weixin2Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
