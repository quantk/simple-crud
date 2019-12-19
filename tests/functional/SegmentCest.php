<?php namespace App\Tests;
use App\Tests\FunctionalTester;

class SegmentCest
{
    public function _before(FunctionalTester $I)
    {
    }

    // tests
    public function tryToGetList(FunctionalTester $I)
    {
        $I->sendGET('/segments/list');
        $I->seeResponseCodeIsSuccessful();
    }
}
