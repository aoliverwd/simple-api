<?php

namespace ConditionTests;

use PHPUnit\Framework\TestCase;
use AOWD\SimpleAPI\URLSegments;
use AOWD\SimpleAPI\API;

final class URLSegmentTest extends TestCase
{
    /**
     * @dataProvider additionSegmentProvider
     */
    public function testUrlSegment(string|null $processed, string|null $expected): void
    {
        $this->assertSame($expected, $processed);
    }

    public function additionSegmentProvider(): array
    {
        API::setURL('/users/alex/something');

        return [
            [API::getURLSegment(), 'users'],
            [API::getURLSegment(2), 'alex'],
            [API::getURLSegment(
                type: URLSegments::Start
            ), 'users'],
            [API::getURLSegment(
                type: URLSegments::End
            ), 'something'],
        ];
    }
}
