<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/28
 * Time: 15:29
 */
namespace backend\controllers;
use api\models\V1\CategoryDisplay;
use backend\models\DisplayCateBannerForm;
use yii\helpers\Json;
use Yii;
use yii\helpers\Url;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;
class DisplayCategoryBannerController extends \yii\web\Controller{
	public function actions()
	{
		return [
			'fileapi-upload' => [
				'class' => FileAPIUpload::className(),
				'path' => '@backend/web/upload/images'
			],
		];
	}
	public function actionIndex(){
		$model=CategoryDisplay::find()->select(['category_display_id','parent_id','sort_order','recommend','status'])->where(['status'=>1,'parent_id'=>0])->orderBy('sort_order asc')->all();
		return $this->render('index',['model'=>$model]);
	}
	public function actionUpdate($id){
		$model = new DisplayCateBannerForm($id);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {

			return $this->redirect('index');
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
}