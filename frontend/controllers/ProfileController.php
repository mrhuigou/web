<?php

namespace frontend\controllers;

use api\models\V1\Customer;
use api\models\V1\Education;
use api\models\V1\Occupation;
use Yii;
use frontend\models\ProfileForm;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;

class ProfileController extends \yii\web\Controller
{
    public $layout = 'main-user';
    
	public $district = [7607=>'市南区',7608=>'市北区',7609=>'四方区',7611=>'崂山区',9390=>'李沧区'];

	public function actions()
	{
	    return [
	        'fileapi-upload' => [
	            'class' => FileAPIUpload::className(),
	            'path' => '@frontend/web/upload/images'
	        ],
	    ];
	}

    public function actionIndex()
    {

        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        $model = new ProfileForm();
        $education = Education::find()->asArray()->all();
        $occupation = Occupation::find()->asArray()->all();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	$model->save();
            \Yii::$app->getSession()->setFlash('success', '修改成功！');
            return $this->redirect(['index']);
        } else {
            return $this->render('index', ['model' => $model,'district' => $this->district,'education'=>$education,'occupation'=>$occupation]);
        }
    }

    public function actionAvatar()
    {

        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }

        $model = Customer::findOne(Yii::$app->user->identity->getId());
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        	$post_data = Yii::$app->request->post();
        	$model->photo = $post_data["Customer"]["photo"];
        	$model->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('avatar', ['model'=>$model]);
       }
    }

}
