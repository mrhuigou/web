<?php

namespace api\modules\oauth2\controllers;
use Yii;
use yii\helpers\ArrayHelper;
use api\modules\oauth2\filters\ErrorToExceptionFilter;
class DefaultController extends \yii\rest\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => ErrorToExceptionFilter::className()
            ],
        ]);
    }
    public function actionToken()
    {
        $server=$this->module->getServer();
        $request=$this->module->getRequest();
        $response = $server->handleTokenRequest($request);
        return  $response->send(Yii::$app->getResponse()->format);
    }
    public function actionResource(){
        $server=$this->module->getServer();
        $request=$this->module->getRequest();
        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (!$server->verifyResourceRequest($request)) {
            $server->getResponse()->send(Yii::$app->getResponse()->format);
            die;
        }
        return json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
    }
    public function actionAuthorize(){
        $server=$this->module->getServer();
        $request=$this->module->getRequest();
        $response = $this->module->getResponse();
// validate the authorize request
        if (!$server->validateAuthorizeRequest($request, $response)) {
            return  $response->send(Yii::$app->getResponse()->format);
        }

    // display an authorization form
        if (empty($_POST)) {
            exit($this->render('index'));
        }
        // print the authorization code if the user has authorized your client
        $is_authorized = ($_POST['authorized'] === 'yes');
        $server->handleAuthorizeRequest($request, $response, $is_authorized);
        if ($is_authorized) {
            $this->redirect($response->getHttpHeader('Location'));
        }
        return $response->send(Yii::$app->getResponse()->format);
    }
}
