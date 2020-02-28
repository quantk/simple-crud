<?php

namespace App\Tests;


use App\Segment\Domain\Point;
use App\Segment\Domain\Segment;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @return array
     */
    public function grabDecodedResponse()
    {
        return json_decode($this->grabResponse(), true);
    }

    public function haveSegment(Point $leftSide, Point $rightSide): Segment
    {
        $this->sendPOST('/segments/create', [
            'left_side' => $leftSide->toArray(),
            'right_side' => $rightSide->toArray()
        ]);

        $this->seeResponseCodeIsSuccessful();
        $response = $this->grabDecodedResponse();

        return Segment::create(
            $response['data']['segment_id'],
            $leftSide,
            $rightSide,
            );
    }
}
