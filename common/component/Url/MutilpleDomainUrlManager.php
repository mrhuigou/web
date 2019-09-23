<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/12/7
 * Time: 11:42
 */
namespace common\components\Url;
use Yii;
class MutilpleDomainUrlManager extends \yii\web\UrlManager
{
    public $domains = array();
    public function createUrl($domain, $params = array()) {
        if (func_num_args() === 1) {
            $params = $domain;
            $domain = false;
        }
        $bak = $this->getBaseUrl();
        if ($domain) {
            if (!isset($this->domains[$domain])) {
                throw new \yii\base\InvalidConfigException('Please configure UrlManager of domain "' . $domain . '".');
            }
            $this->setBaseUrl($this->domains[$domain]);
        }
        $url = parent::createUrl($params);
        $this->setBaseUrl($bak);
        return $url;
    }
}