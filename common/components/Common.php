<?php

namespace common\components;

/**
 * Class Common
 * 公共方法
 * myh
 */

class Common
{
    /**
     * 获取网站联系方式
     * myh
     * @return string 网站电话
     *
     */
    public function getSiteMobile()
    {
        return \Yii::$app->params['site_info']['mobile'];
    }
}

