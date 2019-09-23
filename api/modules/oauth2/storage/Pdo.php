<?php

namespace api\modules\oauth2\storage;

class Pdo extends \OAuth2\Storage\Pdo
{
    public $dsn;
    
    public $username;
    
    public $password;
    
    public $connection = 'db';
    
    public function __construct($connection = null, $config = array())
    {
        if($connection === null) {
            if(!empty($this->connection)) {
                $connection = \Yii::$app->get($this->connection);
                if(!$connection->getIsActive()) {
                    $connection->open();
                }
                $connection = $connection->pdo;
            } else {
                $connection = [
                    'dsn' => $this->dsn,
                    'username' => $this->username,
                    'password' => $this->password
                ];
            }
        }
        $config = array_merge(array(
            'client_table' => 'jr_oauth_clients',
            'access_token_table' => 'jr_oauth_access_tokens',
            'refresh_token_table' => 'jr_oauth_refresh_tokens',
            'code_table' => 'jr_oauth_authorization_codes',
            'user_table' => 'jr_oauth_users',
            'jwt_table'  => 'jr_oauth_jwt',
            'scope_table'  => 'jr_oauth_scopes',
            'public_key_table'  => 'jr_oauth_public_keys',
        ), $config);
        
        parent::__construct($connection, $config);
    }
}