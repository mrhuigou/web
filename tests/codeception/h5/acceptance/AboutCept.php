<?php
use tests\codeception\h5\AcceptanceTester;
use tests\codeception\h5\_pages\AboutPage;

$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that about works');
AboutPage::openBy($I);
$I->see('About', 'h1');
