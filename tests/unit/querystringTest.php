<?php

namespace ConditionTests;

use PHPUnit\Framework\TestCase;
use AOWD\SimpleAPI\API;

final class QueryStringTest extends TestCase
{
    /**
     * @dataProvider additionQueryStringProvider
     */
    public function testQueryString(string|null $processed, string|null $expected): void
    {
        $this->assertSame($expected, $processed);
    }

    public function additionQueryStringProvider(): array
    {
        API::setURL('/home/?param_1=foo&param_2=bar');

        return [
            [API::getQuery(), 'param_1=foo&param_2=bar'],
            [API::getQuery('param_1'), 'foo'],
            [API::getQuery('param_2'), 'bar'],
            [API::getQuery('param_3'), null],
            [API::getQuery('param_3', 'alpha'), 'alpha']
        ];
    }
}
