<?php
use tests\codeception\h5\FunctionalTester;
use tests\codeception\h5\_pages\AboutPage;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that about works');
AboutPage::openBy($I);
$I->see('About', 'h1');
