<?php

namespace tests\codeception\h5\_pages;

use yii\codeception\BasePage;

/**
 * Represents about page
 * @property \codeception_h5\AcceptanceTester|\codeception_h5\FunctionalTester $actor
 */
class AboutPage extends BasePage
{
    public $route = 'site/about';
}
