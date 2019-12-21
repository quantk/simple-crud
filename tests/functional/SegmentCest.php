<?php namespace App\Tests;
use App\Domain\Segment\Point;
use App\Domain\Segment\Segment;

class SegmentCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function tryToCreate(FunctionalTester $I)
    {
        $x1 = 1.0;
        $y1 = 1.0;
        $x2 = 2.0;
        $y2 = 2.0;
        $segment = $I->haveSegment(Point::create($x1, $y1), Point::create($x2, $y2));
        $I->assertSame($segment->leftSide->x, $x1);
        $I->assertSame($segment->leftSide->y, $y1);
        $I->assertSame($segment->rightSide->x, $x2);
        $I->assertSame($segment->rightSide->y, $y2);
    }

    public function tryToCreateAndGet(FunctionalTester $I)
    {
        $x1 = 1.0;
        $y1 = 1.0;
        $x2 = 2.0;
        $y2 = 2.0;
        $segment = $I->haveSegment(Point::create($x1, $y1), Point::create($x2, $y2));

        $I->sendGET("/segments/{$segment->uid}");
        $I->seeResponseCodeIsSuccessful();
    }

    public function tryToGetList(FunctionalTester $I)
    {
        $I->sendGET('/segments/list');
        $I->seeResponseCodeIsSuccessful();
    }

    public function tryToRemove(FunctionalTester $I)
    {
        $x1 = 1.0;
        $y1 = 1.0;
        $x2 = 2.0;
        $y2 = 2.0;
        $segment = $I->haveSegment(Point::create($x1, $y1), Point::create($x2, $y2));

        $I->sendGET("/segments/{$segment->uid}");
        $I->seeResponseCodeIsSuccessful();

        $I->sendPOST("/segments/{$segment->uid}/remove");
        $I->seeResponseCodeIs(201);

        $I->sendGET("/segments/{$segment->uid}");
        $I->seeResponseCodeIs(404);
    }

    public function tryToCheckPostion(FunctionalTester $I)
    {
        $x1 = 1.0;
        $y1 = 1.0;
        $x2 = 5.0;
        $y2 = 5.0;
        $segment = $I->haveSegment(Point::create($x1, $y1), Point::create($x2, $y2));

        $I->sendGET("/segments/{$segment->uid}");
        $I->seeResponseCodeIsSuccessful();

        $I->sendGET("/segments/{$segment->uid}/point_position", [
            'x1' => 3,
            'y1' => 4
        ]);
        $I->seeResponseCodeIsSuccessful();
        $response = $I->grabDecodedResponse();
        $I->assertSame(Segment::UP_POSITION, $response['data']['position']);

        $I->sendGET("/segments/{$segment->uid}/point_position", [
            'x1' => 3,
            'y1' => 2
        ]);
        $I->seeResponseCodeIsSuccessful();
        $response = $I->grabDecodedResponse();
        $I->assertSame(Segment::DOWN_POSITION, $response['data']['position']);

        $I->sendGET("/segments/{$segment->uid}/point_position", [
            'x1' => 3,
            'y1' => 3
        ]);
        $I->seeResponseCodeIsSuccessful();
        $response = $I->grabDecodedResponse();
        $I->assertSame(Segment::CUT_POSITION, $response['data']['position']);
    }
}
