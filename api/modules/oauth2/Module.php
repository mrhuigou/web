<?php

namespace api\modules\oauth2;
use \Yii;
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\oauth2\controllers';
    public $options = [];
    public $storageMap = [];
    public $storageDefault = 'api\modules\oauth2\storage\Pdo';
    public $modelClasses = [];
    private $_server;
    private $_models = [];
    public function init()
    {
        parent::init();
        $this->modelClasses = array_merge($this->getDefaultModelClasses(), $this->modelClasses);
    }
    public function getServer($force = false){
        if($this->_server === null || $force === true) {
        $storages = $this->createStorages();
        $server = new \OAuth2\Server($storages,$this->options);
        $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storages['client_credentials']));
        $server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storages['authorization_code']));
        $server->addGrantType(new \OAuth2\GrantType\UserCredentials($storages['user_credentials']));
        $server->addGrantType(new \OAuth2\GrantType\RefreshToken($storages['refresh_token'], [
            'always_issue_new_refresh_token' => true
        ]));
            $this->_server = $server;
        }
        return $this->_server;
    }
    public function getRequest()
    {
        return \OAuth2\Request::createFromGlobals();
    }

    public function getResponse()
    {
        return new \OAuth2\Response();
    }
    public function createStorages()
    {


        $connection = Yii::$app->getDb();
        if(!$connection->getIsActive()) {
            $connection->open();
        }

        $storages = [];
        foreach($this->storageMap as $name => $storage) {
            $storages[$name] = Yii::createObject($storage);
        }
        $defaults = [
            'access_token',
            'authorization_code',
            'client_credentials',
            'client',
            'refresh_token',
            'user_credentials',
            'public_key',
            'jwt_bearer',
            'scope',
        ];
        foreach($defaults as $name) {
            if(!isset($storages[$name])) {
                $storages[$name] = Yii::createObject($this->storageDefault);
            }
        }

        return $storages;
    }
    /**
     * Get object instance of model
     * @param string $name
     * @param array $config
     * @return ActiveRecord
     */
    public function model($name, $config = [])
    {
        // return object if already created
        if(!empty($this->_models[$name])) {
            return $this->_models[$name];
        }

        // create object
        $className = $this->modelClasses[ucfirst($name)];
        $this->_models[$name] = Yii::createObject(array_merge(["class" => $className], $config));
        return $this->_models[$name];
    }
    /**
     * Get default model classes
     */
    protected function getDefaultModelClasses()
    {
        return [
            'Clients' => 'api\modules\oauth2\models\OauthClients',
            'AccessTokens' => 'api\modules\oauth2\models\OauthAccessTokens',
            'AuthorizationCodes' => 'api\modules\oauth2\models\OauthAuthorizationCodes',
            'RefreshTokens' => 'api\modules\oauth2\models\OauthRefreshTokens',
            'Scopes' => 'api\modules\oauth2\models\OauthScopes',
        ];
    }
}
