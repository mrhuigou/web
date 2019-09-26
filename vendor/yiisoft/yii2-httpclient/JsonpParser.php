<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\httpclient;

use yii\base\Object;
use yii\helpers\Json;

/**
 * JsonParser parses HTTP message content as JSON.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class JsonpParser extends Object implements ParserInterface
{
    /**
     * @inheritdoc
     */
    public function parse(Response $response)
    {
        $rawResponse=trim($response->getContent());
        $lpos = strpos($rawResponse, "(");
        $rpos = strrpos($rawResponse, ")");
        $rawResponse = substr($rawResponse, $lpos + 1, $rpos - $lpos -1);
        return Json::decode($rawResponse);
    }
}