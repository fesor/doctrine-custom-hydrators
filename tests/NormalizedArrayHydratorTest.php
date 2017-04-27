<?php

namespace Tests\Fesor\Doctrine\CustomHydrator;

use Fesor\Doctrine\CustomHydrator\NormalizedArrayHydrator;
use PHPUnit\Framework\TestCase;

class NormalizedArrayHydratorTest extends TestCase
{
    public function testItAggregatesFieldsUsingDelimeter()
    {
        $row = [
            'id' => 1,
            'foo.bar' => 'bar',
            'foo.baz' => 'baz',
            'bar.bar' => 'bar',
        ];

        $actualRow = $this->postProcessRow($row);

        self::assertEquals([
            'id' => 1,
            'foo' => [
                'bar' => 'bar',
                'baz' => 'baz',
            ],
            'bar' => [
                'bar' => 'bar'
            ]
        ], $actualRow);
    }

    public function testItShouldSupportUnlimitedLevelsOfNesting()
    {
        $row = [
            'foo.bar.baz' => 'value'
        ];

        $actualRow = $this->postProcessRow($row);

        self::assertEquals([
            'foo' => [
                'bar' => [
                    'baz' => 'value'
                ]
            ]
        ], $actualRow);
    }

    private function postProcessRow($row)
    {
        $hydrator = (new \ReflectionClass(NormalizedArrayHydrator::class))
            ->newInstanceWithoutConstructor();

        $invoker = \Closure::bind(function ($hydrator, $row) {
            $hydrator->postProcessRow($row);
            return $row;
        }, null, NormalizedArrayHydrator::class);

        return $invoker($hydrator, $row);
    }
}