<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/14
 * Time: 14:48
 */
namespace affiliate\widgets;
use yii\bootstrap\Widget;
class TopMenu extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run()
    {

        return $this->render('TopMenu');
    }
}